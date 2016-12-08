<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Annotations\DocParser;
use Doctrine\Common\Inflector\Inflector;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\DoctrineAnnotationProcessor;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\GenerateAnnotationProcessor;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\PropertyInformation;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\PropertyInformationInterface;
use Hostnet\Component\AccessorGenerator\Generator\Exception\TypeUnknownException;
use Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionClass;
use Hostnet\Component\AccessorGenerator\Twig\CodeGenerationExtension;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Generates Trait files with accessor methods and places them in a "Generated"
 * folder and namespace relative to the file they are created for.
 */
class CodeGenerator implements CodeGeneratorInterface
{
    /**
     * @var string
     */
    private $namespace = 'Generated';

    /**
     * @var string
     */
    private $name_suffix = 'MethodsTrait';

    /**
     * @var \Twig_TemplateInterface
     */
    private $add;

    /**
     * @var \Twig_TemplateInterface
     */
    private $set;

    /**
     * @var \Twig_TemplateInterface
     */
    private $get;

    /**
     * @var \Twig_TemplateInterface
     */
    private $remove;

    /**
     * @var \Twig_TemplateInterface
     */
    private $trait;

    /**
     * @var array
     */
    private $encryption_aliases = [];

    /**
     * Initialize Twig and templates.
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     */
    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../Resources/templates');
        $twig   = new \Twig_Environment($loader);
        $twig->addExtension(new CodeGenerationExtension());

        $this->get    = $twig->loadTemplate('get.php.twig');
        $this->set    = $twig->loadTemplate('set.php.twig');
        $this->add    = $twig->loadTemplate('add.php.twig');
        $this->remove = $twig->loadTemplate('remove.php.twig');
        $this->trait  = $twig->loadTemplate('trait.php.twig');
    }

    /**
     * {@inheritdoc}
     */
    public function writeTraitForClass(ReflectionClass $class)
    {
        $data = $this->generateTraitForClass($class);
        $fs   = new Filesystem();

        if ($data) {
            $path     = dirname($class->getFilename()) . DIRECTORY_SEPARATOR . $this->namespace;
            $filename = $path . DIRECTORY_SEPARATOR . $class->getName() . $this->name_suffix . '.php';

            $fs->mkdir($path);
            $fs->dumpFile($filename, $data);

            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function generateTraitForClass(ReflectionClass $class)
    {
        $code                  = '';
        $add_collection_import = false;

        try {
            $properties = $class->getProperties();
            $imports    = $class->getUseStatements();
        } catch (ClassDefinitionNotFoundException $e) {
            return '';
        }

        $imports[] = $class->getNamespace() . '\\' . $class->getName();

        $generate_processor = new GenerateAnnotationProcessor();
        $doctrine_processor = new DoctrineAnnotationProcessor();

        foreach ($properties as $property) {
            $parser = new DocParser();
            $info   = new PropertyInformation($property, $parser);
            $info->registerAnnotationProcessor($generate_processor);
            $info->registerAnnotationProcessor($doctrine_processor);
            $info->processAnnotations();

            // Check if we have anything to do. If not, continue to the next
            // property.
            if (!$info->willGenerateAdd()
                && !$info->willGenerateGet()
                && !$info->willGenerateRemove()
                && !$info->willGenerateSet()
            ) {
                continue;
            }

            // Complex Type within current namespace. Since our trait is in a
            // sub-namespace we have to import those as well. Theoretically no
            // harm could come from these imports unless the types are of a
            // *methodsTrait type. Which will break anyway.
            self::addImportForProperty($info, $imports);

            // Parse and add fully qualified type information to the info
            // object for use in doc blocks to make IDE's understand the types properly.
            $info->setFullyQualifiedType(self::fqcn($info->getTypeHint(), $imports));

            $code .= $this->generateAccessors($info);

            // Detected that the ImmutableCollection is used and should be imported.
            if ($info->willGenerateGet() && $info->isCollection()) {
                $add_collection_import = true;
            }
        }

        // Add import for ImmutableCollection if we generate any functions that
        // make use of this collection wrapper.
        if ($add_collection_import) {
            $imports[] = 'Hostnet\Component\AccessorGenerator\Collection\ImmutableCollection';
        }

        if ($code) {
            $code = $this->trait->render(
                [
                    'namespace' => $class->getNamespace() . '\\' . $this->namespace,
                    'name'      => $class->getName() . $this->name_suffix,
                    'uses'      => $this->getUniqueImports($imports),
                    'methods'   => rtrim($code),
                    'username'  => get_current_user(),
                    'hostname'  => gethostname(),
                ]
            );
        }

        return $code;
    }

    /**
     * {@inheritdoc}
     */
    public function setEncryptionAliases(array $encryption_aliases)
    {
        $this->encryption_aliases = $encryption_aliases;
    }

    /**
     * @param PropertyInformation $info
     * @param string[]            &$imports
     */
    private static function addImportForProperty(PropertyInformation $info, array &$imports)
    {
        if ($info->isComplexType()) {
            $type      = $info->getType();
            $type_hint = $info->getTypeHint();
            if (strpos($type_hint, '\\') !== 0) {
                self::addImportForType($type_hint, $info->getNamespace(), $imports);
            }
            if ($type !== $type_hint && strpos($type, '\\') !== 0) {
                self::addImportForType($type, $info->getNamespace(), $imports);
            }
        }

        $default = strstr($info->getDefault(), '::', true);
        if ($default) {
            self::addImportForType($default, $info->getNamespace(), $imports);
        }
    }

    /**
     * @param string   $type
     * @param string   $namespace
     * @param string[] &$imports
     */
    private static function addImportForType($type, $namespace, array &$imports)
    {
        if (!self::isAliased($type, $imports)) {
            $first_part = strstr($type, '\\', true);
            if ($first_part) {
                // Sub namespace;
                $imports[$first_part] = $namespace . '\\' . $first_part;
            } else {
                // Inside own namespace
                if (!self::getPlainImportIfExists($type, $imports)) {
                    // Not already imported
                    $imports[] = $namespace . '\\' . $type;
                }
            }
        }
    }

    /**
     * Returns true if the given class name is in an aliased namespace, false
     * otherwise.
     *
     * @param  string   $name
     * @param  string[] $imports
     * @return boolean
     */
    private static function isAliased($name, array $imports)
    {
        $aliases = array_keys($imports);
        foreach ($aliases as $alias) {
            if (strpos($name, $alias) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  string   $type
     * @param  string[] $imports
     * @return string|null
     */
    private static function getPlainImportIfExists($type, $imports)
    {
        foreach ($imports as $alias => $import) {
            if (is_numeric($alias) && substr($import, -1 - strlen($type)) === '\\' . $type) {
                return $import;
            }
        }

        return null;
    }

    /**
     * Return the fully qualified class name based on the use statements in
     * the current file.
     *
     * @param  string   $name
     * @param  string[] $imports
     * @return string
     */
    private static function fqcn($name, array $imports)
    {
        // Already FQCN
        if ($name[0] === '\\') {
            return $name;
        }

        // Aliased
        if (array_key_exists($name, $imports)) {
            return '\\' . $imports[$name];
        }

        // Check other imports
        if ($plain = self::getPlainImportIfExists($name, $imports)) {
            return '\\' . $plain;
        }

        // Not a complex type, or otherwise unknown.
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function generateAccessors(PropertyInformationInterface $info)
    {
        $code = '';

        // Check if there is enough information to generate accessors.
        if ($info->getType() === null) {
            throw new TypeUnknownException(
                sprintf(
                    'Property %s in class %s\%s has no type set, nor could it be inferred. %s',
                    $info->getName(),
                    $info->getNamespace(),
                    $info->getClass(),
                    'Did you forget to import Doctrine\ORM\Mapping as ORM?'
                )
            );
        }

        $public_key = isset($this->encryption_aliases[$info->getEncryptionAlias()]['public-key'])
            ? 'file://' . getcwd() . '/' . $this->encryption_aliases[$info->getEncryptionAlias()]['public-key']
            : null;
        $private_key = isset($this->encryption_aliases[$info->getEncryptionAlias()]['private-key'])
            ? 'file://' . getcwd() . '/' . $this->encryption_aliases[$info->getEncryptionAlias()]['private-key']
            : null;

        // Generate a get method.
        if ($info->willGenerateGet()) {
            // Compute the name of the get method. For boolean values
            // an is method is generated instead of a get method.
            if ($info->getType() === 'boolean') {
                if (preg_match('/^is[_A-Z0-9]/', $info->getName())) {
                    $getter = Inflector::camelize($info->getName());
                } else {
                    $getter = 'is' . Inflector::classify($info->getName());
                }
            } else {
                $getter = 'get' . Inflector::classify($info->getName());
            }

            // Render the get/is method.
            $code .= $this->get->render(
                [
                    'property'     => $info,
                    'private_key'  => $private_key,
                    'getter'       => $getter,
                    'PHP_INT_SIZE' => PHP_INT_SIZE,
                ]
            ) . PHP_EOL;
        }

        // Render add/remove methods for collections and set methods for
        // non collection values.
        if ($info->isCollection()) {
            // Generate an add method.
            if ($info->willGenerateAdd()) {
                $code .= $this->add->render(['property' => $info]) . PHP_EOL;
            }
            // Generate a remove method.
            if ($info->willGenerateRemove()) {
                $code .= $this->remove->render(['property' => $info]) . PHP_EOL;
            }
        } else {
            // No collection thus, generate a set method.
            if ($info->willGenerateSet()) {
                $code .= $this->set->render(
                    [
                        'property'     => $info,
                        'public_key'   => $public_key,
                        'PHP_INT_SIZE' => PHP_INT_SIZE,
                    ]
                ) . PHP_EOL;
            }
        }

        return $code;
    }

    /**
     * Make sure our use statements are sorted alphabetically and unique. The
     * array_unique function can not be used because it does not take values
     * with different array keys into account. This loop does exactly that.
     * This is useful when a specific class name is imported and aliased as
     * well.
     *
     * @param  string[] $imports
     * @return string[]
     */
    private function getUniqueImports(array $imports)
    {
        uksort(
            $imports,
            function ($a, $b) use ($imports) {
                $alias_a = is_numeric($a) ? " as $a;" : '';
                $alias_b = is_numeric($b) ? " as $b;" : '';

                return strcmp($imports[$a] . $alias_a, $imports[$b] . $alias_b);
            }
        );

        $unique_imports = [];
        $next           = null;
        do {
            $key   = key($imports);
            $value = current($imports);
            $next  = next($imports);
            if ($value !== $next || $key !== key($imports)) {
                if ($key) {
                    $unique_imports[$key] = $value;
                } else {
                    $unique_imports[] = $value;
                }
            }
        } while ($next !== false);

        return $unique_imports;
    }
}
