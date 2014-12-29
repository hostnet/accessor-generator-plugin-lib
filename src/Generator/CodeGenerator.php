<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Annotations\DocParser;
use Doctrine\Common\Inflector\Inflector;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\DoctrineAnnotationProcessor;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\GenerateAnnotationProcessor;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\PropertyInformation;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\PropertyInformationInterface;
use Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionClass;
use Hostnet\Component\AccessorGenerator\Twig\CodeGenerationExtension;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Generate Trait files with accessor methods.
 * Put them in a Generated folder and namespace
 * relative to the file they are created for.
 *
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class CodeGenerator implements CodeGeneratorInterface
{
    private $namespace   = 'Generated';
    private $name_suffix = 'MethodsTrait';

    /**
     * Initialize Twig and templates
     */
    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../Resources/templates');
        $twig   = new \Twig_Environment($loader);
        $twig->clearTemplateCache();
        $twig->addExtension(new CodeGenerationExtension());

        $this->get    = $twig->loadTemplate('get.php.twig');
        $this->set    = $twig->loadTemplate('set.php.twig');
        $this->add    = $twig->loadTemplate('add.php.twig');
        $this->remove = $twig->loadTemplate('remove.php.twig');
        $this->trait  = $twig->loadTemplate('trait.php.twig');
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\Generator\CodeGeneratorInterface::writeTraitForClass()
     * @param ReflectionClass $class
     * @return boolean
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
     * @see \Hostnet\Component\AccessorGenerator\Generator\CodeGeneratorInterface::generateTraitForClass()
     * @param ReflectionClass $class
     * @return string
     */
    public function generateTraitForClass(ReflectionClass $class)
    {
        $code = '';
        $add_collection_import = false;

        try {
            $properties = $class->getProperties();
            $imports    = $class->getUseStatements();
        } catch (ClassDefinitionNotFoundException $e) {
            return '';
        }

        $imports[] = $class->getNamespace() . '\\' . $class->getName();

        foreach ($properties as $property) {
            $parser = new DocParser();
            $info   = new PropertyInformation($property, $parser);
            $info->registerAnnotationProcessor(new GenerateAnnotationProcessor());
            $info->registerAnnotationProcessor(new DoctrineAnnotationProcessor());
            $info->processAnnotations();
            $type = $info->getType();

            // Complex Type within curent namespace. Since our trait is in a sub
            // namespace we have to import those aswell (php does not no .. in namespace).
            // In principle no harm could come from these imports unless the types
            // are of a *methodsTrait type. Which will break anyway.
            self::addImportForProperty($info, $imports);

            // Parse and add fully qualified type information to the info object for use
            // in docblocks to make eclipse understand the types.
            $info->setFullyQualifiedType(self::fqcn($type, $imports));

            $code .= $this->generateAccessors($info);

            // Detected that the ImmutableCollection is used and should be imported.
            if ($info->willGenerateGet() && $info->isCollection()) {
                $add_collection_import = true;
            }
        }

        // Add import for ImmutableCollection if we generate any funtions that make use of this
        // collection wrapper.
        if ($add_collection_import) {
            $imports[] = "Hostnet\Component\AccessorGenerator\Collection\ImmutableCollection";
        }

        // Make sure our use statemens are sorted alphabetically and unique.
        asort($imports);
        $imports = array_unique($imports);

        if ($code) {
            $code = $this->trait->render([
                'namespace' => $class->getNamespace() . '\\' . $this->namespace,
                'name'      => $class->getName() . $this->name_suffix,
                'uses'      => $imports,
                'methods'   => rtrim($code),
                'username'  => get_current_user(),
                'hostname'  => gethostname()
            ]);
        }

        return $code;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\Generator\CodeGeneratorInterface::generateAccessors()
     * @param PropertyInformationInterface $info
     * @return string
     */
    public function generateAccessors(PropertyInformationInterface $info)
    {
        $code = '';

        if ($info->willGenerateSet() == false) {
            $default = 'null';
        } else {
            $default = $info->getDefault();
        }

        if ($info->willGenerateGet()) {
            if ($info->getType() == 'boolean') {
                if (preg_match('/^is[_A-Z0-9]/', $info->getName())) {
                    $getter = Inflector::camelize($info->getName());
                } else {
                    $getter = 'is' . Inflector::classify($info->getName());
                }
            } else {
                $getter = 'get' . Inflector::classify($info->getName());
            }
            $code .= $this->get->render([
                    'property' => $info,
                    'default' => $default,
                    'getter' => $getter,
                    'PHP_INT_SIZE' => PHP_INT_SIZE
            ]) . PHP_EOL;
        }

        if ($info->isCollection()) {
            if ($info->willGenerateAdd() || $info->getReferencedProperty()) {
                $code .= $this->add->render(['property' => $info]). PHP_EOL;
            }
            if ($info->willGenerateRemove() || $info->getReferencedProperty()) {
                $code .= $this->remove->render(['property' => $info]). PHP_EOL;
            }
        } else {
            if ($info->willGenerateSet() || $info->getReferencedProperty()) {
                $code .= $this->set->render([
                        'property' => $info,
                        'default' => $default,
                        'PHP_INT_SIZE' => PHP_INT_SIZE
                ]). PHP_EOL;
            }
        }

        return $code;
    }

    /**
     * Return the fully qualified class name based on the
     * use statments in the current file.
     *
     * @param $name class name
     * @param array $imports
     * @return string
     */
    private static function fqcn($name, array $imports)
    {
        // Already FQCN
        if (substr($name, 0, 1) === '\\') {
            return $name;
        }

        // Aliased
        if (isset($imports[$name])) {
            return '\\' . $imports[$name];
        }

        // Check other imports
        if (($plain = self::getPlainImportIfExists($name, $imports))) {
            return '\\' .  $plain;
        }

        // Not a complex type, or otherwise unkown.
        return '';
    }

    /**
     * Returns if this class is in an
     * aliassed namespace.
     *
     * @param class name $name
     * @param array $imports
     * @return boolean
     */
    private static function isAliased($name, array $imports)
    {
        $aliasses = array_keys($imports);
        foreach ($aliasses as $alias) {
            if (strpos($name, $alias) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     *
     * @param string $type
     * @param array $imports
     * @return string|null
     */
    private static function getPlainImportIfExists($type, $imports)
    {
        foreach ($imports as $alias => $import) {
            if (is_numeric($alias)) {
                if (substr($import, -1 - strlen($type)) == '\\' . $type) {
                    return $import;
                }
            }
        }
    }

    /**
     *
     * @param PropertyInformation $info
     * @param array $imports
     */
    private static function addImportForProperty(PropertyInformation $info, array &$imports)
    {
        if ($info->isComplexType()) {
            $type = $info->getType();
            if (strpos($type, '\\') !== 0) {
                self::addImportForType($type, $info->getNamespace(), $imports);
            }
        }

        $default = strstr($info->getDefault(), '::', true);
        if ($default) {
            self::addImportForType($default, $info->getNamespace(), $imports);
        }
    }

    /**
     *
     * @param string $type
     * @param string $namespace
     * @param array $imports
     */
    private static function addImportForType($type, $namespace, array &$imports)
    {
        if (!self::isAliased($type, $imports)) {
            $first_part = strstr($type, '\\', true);
            if ($first_part) {
                // Subnamespace;
                $imports[$first_part] = $namespace . '\\' . $first_part;
            } else {
                // Inside own namespace
                if (! self::getPlainImportIfExists($type, $imports)) {
                    // Not already imported
                    $imports[] = $namespace . '\\' . $type;
                }
            }
        }
    }
}
