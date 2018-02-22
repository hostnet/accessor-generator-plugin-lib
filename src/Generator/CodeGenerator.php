<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Annotations\DocParser;
use Doctrine\Common\Inflector\Inflector;
use Hostnet\Component\AccessorGenerator\Annotation\Enumerator;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\DoctrineAnnotationProcessor;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\EnumItemInformation;
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
     * @var string
     */
    private $enum_name_suffix = 'Enum';

    /**
     * @var array
     */
    private $enum_class_cache = [];

    /**
     * @var string
     */
    private $key_registry_class = 'KeyRegistry';

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
    private $enum_get;

    /**
     * @var \Twig_Template
     */
    private $enum_class;

    /**
     * @var \Twig_TemplateInterface
     */
    private $remove;

    /**
     * @var \Twig_TemplateInterface
     */
    private $trait;

    /**
     * @var \Twig_TemplateInterface
     */
    private $keys;

    /**
     * @var array
     */
    private $metadata_cache = [];

    /**
     * @var array
     */
    private $encryption_aliases = [];

    /**
     * Contains the namespace and corresponding encryption aliases indexed on unique class directory.
     *
     * @var array
     */
    private $key_registry_data = [];

    /**
     * Initialize Twig and templates.
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     */
    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../Resources/templates');
        $twig   = new \Twig_Environment($loader);
        $twig->addExtension(new CodeGenerationExtension());

        $this->get        = $twig->loadTemplate('get.php.twig');
        $this->set        = $twig->loadTemplate('set.php.twig');
        $this->add        = $twig->loadTemplate('add.php.twig');
        $this->remove     = $twig->loadTemplate('remove.php.twig');
        $this->trait      = $twig->loadTemplate('trait.php.twig');
        $this->keys       = $twig->loadTemplate('keys.php.twig');
        $this->enum_get   = $twig->loadTemplate('enum_get.php.twig');
        $this->enum_class = $twig->loadTemplate('enum_class.php.twig');
    }

    /**
     * {@inheritdoc}
     */
    public function writeTraitForClass(ReflectionClass $class)
    {
        $data = $this->generateTraitForClass($class);

        if ($data) {
            $path     = dirname($class->getFilename()) . DIRECTORY_SEPARATOR . $this->namespace;
            $filename = $path . DIRECTORY_SEPARATOR . $class->getName() . $this->name_suffix . '.php';

            $fs = new Filesystem();
            $fs->mkdir($path);
            $fs->dumpFile($filename, $data);

            return true;
        } else {
            return false;
        }
    }

    public function writeEnumeratorAccessorsForClass(ReflectionClass $class): array
    {
        $metadata  = $this->getMetadataForClass($class);
        $fs        = new Filesystem();
        $generated = [];

        foreach ($metadata['properties'] as $info) {
            /* @var $info PropertyInformation */
            if (! $info->willGenerateEnumeratorAccessors()) {
                continue;
            }

            foreach ($info->getEnumeratorsToGenerate() as $enumerator) {
                $cache_id = $enumerator->getEnumeratorClass();
                if (isset($this->enum_class_cache[$cache_id])) {
                    // Do not generate the same class twice.
                    continue;
                }

                $this->enum_class_cache[$cache_id] = $this->generateEnumeratorAccessors($enumerator);

                $reflector   = new \ReflectionClass($enumerator->getEnumeratorClass());
                $generated[] = $enumerator->getEnumeratorClass();
                $path        = dirname($reflector->getFileName()) . DIRECTORY_SEPARATOR . $this->namespace;
                $filename    = $path
                    . DIRECTORY_SEPARATOR
                    . $reflector->getShortName()
                    . $this->enum_name_suffix
                    . '.php';

                $this->validateEnumEntity($info->getType());

                $fs->mkdir($path);
                $fs->dumpFile($filename, $this->enum_class_cache[$cache_id]);
            }
        }

        return $generated;
    }

    /**
     * @param Enumerator $enumerator
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function generateEnumeratorAccessors(Enumerator $enumerator)
    {
        $enum_class = $enumerator->getEnumeratorClass();
        $reflector  = new \ReflectionClass($enum_class);
        $class_name = $reflector->getShortName() . $this->enum_name_suffix;
        $namespace  = $reflector->getNamespaceName() . '\\Generated';
        $properties = [];

        foreach ($reflector->getReflectionConstants() as $reflection_constant) {
            $properties[] = new EnumItemInformation($reflection_constant);
        }

        return $this->enum_class->render([
            'enum_class' => $enum_class,
            'class_name' => $class_name,
            'properties' => $properties,
            'namespace'  => $namespace,
            'username'   => get_current_user(),
            'hostname'   => gethostname(),
        ]);
    }

    private function getMetadataForClass(ReflectionClass $class)
    {
        $cache_key = (string) $class->getFilename();
        if (isset($this->metadata_cache[$cache_key])) {
            return $this->metadata_cache[$cache_key];
        }

        try {
            $properties = $class->getProperties();
            $imports    = $class->getUseStatements();
        } catch (ClassDefinitionNotFoundException $e) {
            return ['properties' => [], 'imports' => []];
        }

        $imports[] = $class->getNamespace() . '\\' . $class->getName();

        $generate_processor = new GenerateAnnotationProcessor();
        $doctrine_processor = new DoctrineAnnotationProcessor();

        $this->metadata_cache[$cache_key]['imports']    = $imports;
        $this->metadata_cache[$cache_key]['properties'] = [];

        foreach ($properties as $property) {
            $parser = new DocParser();
            $info   = new PropertyInformation($property, $parser);
            $info->registerAnnotationProcessor($generate_processor);
            $info->registerAnnotationProcessor($doctrine_processor);
            $info->processAnnotations();

            $this->metadata_cache[$cache_key]['properties'][$info->getName()] = $info;
        }

        return $this->metadata_cache[$cache_key];
    }

    /**
     * {@inheritdoc}
     */
    public function generateTraitForClass(ReflectionClass $class)
    {
        $code                  = '';
        $add_collection_import = false;

        $metadata = $this->getMetadataForClass($class);
        $imports  = $metadata['imports'];

        foreach ($metadata['properties'] as $info) {

            // Check if we have anything to do. If not, continue to the next
            // property.
            if (! $info->willGenerateAdd()
                && ! $info->willGenerateGet()
                && ! $info->willGenerateRemove()
                && ! $info->willGenerateSet()
                && ! $info->willGenerateEnumeratorAccessors()
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

            // If the property information has an encryption alias defined store
            // some information to generate the KeyRegistry classes afterwards.
            if ($info->getEncryptionAlias()) {
                $dir_name = dirname($class->getFilename());
                if (! isset($this->key_registry_data[$dir_name])) {
                    $this->key_registry_data[$dir_name] = [];
                }

                $keys = isset($this->encryption_aliases[$info->getEncryptionAlias()])
                    ? $this->encryption_aliases[$info->getEncryptionAlias()]
                    : null;

                $this->key_registry_data[$dir_name]['namespace']                         = $class->getNamespace();
                $this->key_registry_data[$dir_name]['keys'][$info->getEncryptionAlias()] = $keys;
            }

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
        if (! self::isAliased($type, $imports)) {
            $first_part = strstr($type, '\\', true);
            if ($first_part) {
                // Sub namespace;
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

    /**
     * Returns true if the given class name is in an aliased namespace, false
     * otherwise.
     *
     * @param  string   $name
     * @param  string[] $imports
     * @return bool
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

        if ($info->willGenerateEnumeratorAccessors()) {
            foreach ($info->getEnumeratorsToGenerate() as $enum_to_generate) {
                // Unhandy way to get the short name of the class since we can't use native ReflectionClass here because
                // the composer autoload process is not finalized at this point.
                $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $enum_to_generate->getEnumeratorClass());
                $class_ns   = str_replace(DIRECTORY_SEPARATOR, '\\', dirname($class_path)) . '\\' . $this->namespace;
                $class_name = $class_ns . '\\' . basename($class_path) . $this->enum_name_suffix;

                $code .= $this->enum_get->render([
                    'property'      => $info->getName(),
                    'name'          => $enum_to_generate->getName(),
                    'class_name'    => $class_name,
                    'info'          => $info,
                    'enum_class'    => $enum_to_generate->getEnumeratorClass(),
                    'enum_property' => Inflector::tableize($enum_to_generate->getName() . '_instance')
                ]);
            }
        }

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
                            'PHP_INT_SIZE' => PHP_INT_SIZE,
                        ]
                    ) . PHP_EOL;
            }
        }

        return $code;
    }

    /**
     * {@inheritdoc}
     */
    public function writeKeyRegistriesForPackage()
    {
        foreach ($this->key_registry_data as $directory => $data) {
            $path     = $directory . DIRECTORY_SEPARATOR . $this->namespace;
            $filename = $path . DIRECTORY_SEPARATOR . $this->key_registry_class . '.php';
            $fs       = new Filesystem();

            // make sure they are sorted
            ksort($data['keys']);

            $data = $this->keys->render([
                'namespace' => $data['namespace'] . '\\' . $this->namespace,
                'keys'      => $data['keys'],
                'base_path' => getcwd(),
                'username'  => get_current_user(),
                'hostname'  => gethostname()
            ]);

            $fs->mkdir($path);
            $fs->dumpFile($filename, $data);
        }

        // Clear the key registry data.
        $this->key_registry_data = [];

        return true;
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

    /**
     * Ensures the enity class complies to the "standard" for holding parameters.
     *
     * @param string $entity_class
     */
    private function validateEnumEntity(string $entity_class)
    {
        $reflector = new \ReflectionClass($entity_class);

        if (! $reflector->hasMethod('__construct')) {
            throw new \LogicException(sprintf(
                'Entity class "%s" must implement __construct($owning_entity, $name) in order to be Enum-compatible.',
                $entity_class
            ));
        }

        if (! $reflector->hasMethod('setValue')) {
            throw new \LogicException(sprintf(
                'Entity class "%s" must implement setValue($value) in order to be Enum-compatible.',
                $entity_class
            ));
        }

        if (! $reflector->hasMethod('getValue')) {
            throw new \LogicException(sprintf(
                'Entity class "%s" must implement getValue() in order to be Enum-compatible.',
                $entity_class
            ));
        }

        // Verify __constructor signature.
        $constructor_args = $reflector->getMethod('__construct')->getParameters();
        if (count($constructor_args) !== 3) {
            throw new \LogicException(sprintf(
                'The constructor of entity "%s" must implement 2 parameters: %s',
                $entity_class,
                '<object> owning_entity, <string> name, <?string> value'
            ));
        }
    }
}
