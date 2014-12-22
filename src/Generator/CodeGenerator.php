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
            if ($type[0] != '\\' && $info->isComplexType() && ! $this->isAliased($type, $imports)) {
                if (strpos($info->getType(), '\\') === false) {
                    $imports[] = $class->getNamespace() .  '\\' . $type;
                } else {
                    $info->setType('\\' . $type);
                }
            }

            // Parse and add fully qualified type information to the info object for use
            // in docblocks to make eclipse understand the types.
            $info->setFullyQualifiedType($this->fqcn($type, $imports));

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
    private function fqcn($name, array $imports)
    {
        // Already FQCN
        if (substr($name, 0, 1) === '\\') {
            return $name;
        }

        // No complex type
        if (ctype_lower(substr($name, 0, 1))) {
            return '';
        }

        // Aliased
        if (isset($imports[$name])) {
            return '\\' . $imports[$name];
        }

        // Check other imports
        foreach ($imports as $alias => $import) {
            if (is_numeric($alias)) {
                if (substr($import, -1 - strlen($name)) == '\\' . $name) {
                    return '\\' . $import;
                }
            }
        }

        return '\\' . $name;
    }

    /**
     * Returns if this class is in an
     * aliassed namespace.
     *
     * @param class name $name
     * @param array $imports
     * @return boolean
     */
    private function isAliased($name, array $imports)
    {
        $aliasses = array_keys($imports);
        foreach ($aliasses as $alias) {
            if (strpos($name, $alias) === 0) {
                return true;
            }
        }
        return false;
    }
}
