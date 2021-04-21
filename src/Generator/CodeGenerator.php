<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Inflector\Inflector;
use Hostnet\Component\AccessorGenerator\Annotation\Enumerator;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\DoctrineAnnotationProcessor;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\EnumItemInformation;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\GenerateAnnotationProcessor;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\PropertyInformation;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\PropertyInformationInterface;
use Hostnet\Component\AccessorGenerator\Collection\ImmutableCollection;
use Hostnet\Component\AccessorGenerator\Enum\EnumeratorCompatibleEntityInterface;
use Hostnet\Component\AccessorGenerator\Generator\Exception\ReferencedClassNotFoundException;
use Hostnet\Component\AccessorGenerator\Generator\Exception\TypeUnknownException;
use Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionClass;
use Hostnet\Component\AccessorGenerator\Twig\CodeGenerationExtension;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\Template;

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
     * @var Template
     */
    private $add;

    /**
     * @var Template
     */
    private $set;

    /**
     * @var Template
     */
    private $get;

    /**
     * @var Template
     */
    private $enum_get;

    /**
     * @var Template
     */
    private $enum_class;

    /**
     * @var Template
     */
    private $remove;

    /**
     * @var Template
     */
    private $trait;

    /**
     * @var Template
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
     * @throws LoaderError
     * @throws SyntaxError
     * @throws RuntimeError
     */
    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../Resources/templates');
        $twig   = new Environment($loader);
        $twig->addExtension(new CodeGenerationExtension());

        $this->get        = $twig->load('get.php.twig');
        $this->set        = $twig->load('set.php.twig');
        $this->add        = $twig->load('add.php.twig');
        $this->remove     = $twig->load('remove.php.twig');
        $this->trait      = $twig->load('trait.php.twig');
        $this->keys       = $twig->load('keys.php.twig');
        $this->enum_get   = $twig->load('enum_get.php.twig');
        $this->enum_class = $twig->load('enum_class.php.twig');
    }

    /**
     * {@inheritdoc}
     */
    public function writeTraitForClass(ReflectionClass $class): bool
    {
        $data = $this->generateTraitForClass($class);

        if ($data) {
            $path     = dirname($class->getFilename()) . DIRECTORY_SEPARATOR . $this->namespace;
            $filename = $path . DIRECTORY_SEPARATOR . $class->getName() . $this->name_suffix . '.php';

            $fs = new Filesystem();
            $fs->mkdir($path);
            $fs->dumpFile($filename, $data);

            return true;
        }

        return false;
    }

    public function writeEnumeratorAccessorsForClass(ReflectionClass $class): array
    {
        $metadata  = $this->getMetadataForClass($class);
        $fs        = new Filesystem();
        $generated = [];

        foreach ($metadata['properties'] as $info) {
            /** @var $info PropertyInformation */
            if (! $info->willGenerateEnumeratorAccessors()) {
                continue;
            }

            foreach ($info->getEnumeratorsToGenerate() as $enumerator) {
                $cache_id = $enumerator->getEnumeratorClass();
                if (isset($this->enum_class_cache[$cache_id])) {
                    // Do not generate the same class twice.
                    continue;
                }

                $this->enum_class_cache[$cache_id] = $this->generateEnumeratorAccessors($enumerator, $info);

                $reflector   = new \ReflectionClass($enumerator->getEnumeratorClass());
                $generated[] = $enumerator->getEnumeratorClass();
                $path        = dirname($reflector->getFileName()) . DIRECTORY_SEPARATOR . $this->namespace;
                $filename    = $path
                    . DIRECTORY_SEPARATOR
                    . $reflector->getShortName()
                    . $this->enum_name_suffix
                    . '.php';

                if (empty($info->getType()) && ! empty($enumerator->getType())) {
                    $info->setType($enumerator->getType());
                }
                $this->validateEnumEntity($info->getType());

                $fs->mkdir($path);
                $fs->dumpFile($filename, $this->enum_class_cache[$cache_id]);
            }
        }

        return $generated;
    }

    /**
     * @throws \ReflectionException
     * @throws \Throwable
     * @throws ReferencedClassNotFoundException
     * @param Enumerator          $enumerator
     * @param PropertyInformation $info
     * @return string
     */
    public function generateEnumeratorAccessors(Enumerator $enumerator, PropertyInformation $info): string
    {
        $enum_class = $enumerator->getEnumeratorClass();
        if (! class_exists($enum_class)) {
            throw new ReferencedClassNotFoundException(sprintf(
                'Enumerator accessor for "%s" was not generated because the enum class "%s" does not exist.',
                $info->getClass(),
                $enumerator->getEnumeratorClass()
            ));
        }

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

    private function getMetadataForClass(ReflectionClass $class): array
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
            $info = new PropertyInformation($property);
            $info->registerAnnotationProcessor($generate_processor);
            $info->registerAnnotationProcessor($doctrine_processor);
            $info->processAnnotations();

            $this->metadata_cache[$cache_key]['properties'][$info->getName()] = $info;
        }

        // Pre-pass to link enumerators to their associated collections. It is imperative that this is executed once
        // because if we dont, the same Enumerator instance could be assigned to the same collection multiple times.
        // Skip this process if we're currently dealing with a trait.
        if (strpos($class->getFilename(), 'Trait.php') === false) {
            $this->linkEnumeratorsToAssociatedCollections($this->metadata_cache[$cache_key]);
        }

        return $this->metadata_cache[$cache_key];
    }

    private function linkEnumeratorsToAssociatedCollections(array $metadata): void
    {
        foreach ($metadata['properties'] as $info) {
            /** @var $info PropertyInformation */
            if (! $info->willGenerateEnumeratorAccessors() || ! $info->isGenerator()) {
                continue;
            }

            foreach ($info->getEnumeratorsToGenerate() as $enumerator) {
                // Ensure the name of the enumerator refers to an existing property in this class.
                if (! isset($metadata['properties'][$enumerator->getName()])) {
                    if (! $info->getType()) {
                        throw new \LogicException(sprintf(
                            'The name "%s" in Enumerator for "%s" does not exist as a property in the class "%s".',
                            $enumerator->getName(),
                            $info->getName(),
                            $info->getClass()
                        ));
                    }
                    $enumerator->name = $info->getName();
                }

                $collection = $metadata['properties'][$enumerator->getName()];
                /** @var $collection PropertyInformation */
                // Ensure the referenced property is a collection.
                if (! $collection->isCollection()) {
                    throw new \LogicException(sprintf(
                        'The property "%s" referenced in the enumerator "%s" is not a collection in the class "%s".',
                        $collection->getName(),
                        $info->getName(),
                        $info->getClass()
                    ));
                }

                $collection->addEnumeratorToGenerate($enumerator);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function generateTraitForClass(ReflectionClass $class): string
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
            if ($info->isGenerator()) {
                $info->setFullyQualifiedType(self::fqcn($info->getTypeHint(), $imports));
            }

            // If the property information has an encryption alias defined store
            // some information to generate the KeyRegistry classes afterwards.
            if ($info->getEncryptionAlias()) {
                $dir_name = dirname($class->getFilename());
                if (! isset($this->key_registry_data[$dir_name])) {
                    $this->key_registry_data[$dir_name] = [];
                }

                $keys = $this->encryption_aliases[$info->getEncryptionAlias()] ?? null;

                $this->key_registry_data[$dir_name]['namespace']                         = $class->getNamespace();
                $this->key_registry_data[$dir_name]['keys'][$info->getEncryptionAlias()] = $keys;
            }

            $code .= $this->generateAccessors($info);

            // Detected that the ImmutableCollection is used and should be imported.
            if (!$info->willGenerateGet() || !$info->isCollection()) {
                continue;
            }

            $add_collection_import = true;
        }

        // Add import for ImmutableCollection if we generate any functions that
        // make use of this collection wrapper.
        if ($add_collection_import && !\in_array(ImmutableCollection::class, $imports, true)) {
            $imports[] = ImmutableCollection::class;
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
    public function setEncryptionAliases(array $encryption_aliases): void
    {
        $this->encryption_aliases = $encryption_aliases;
    }

    /**
     * @param PropertyInformation $info
     * @param string[]            &$imports
     */
    private static function addImportForProperty(PropertyInformation $info, array &$imports): void
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

        if (false === ($default = strstr($info->getDefault(), '::', true))) {
            return;
        }

        self::addImportForType($default, $info->getNamespace(), $imports);
    }

    /**
     * @param string   $type
     * @param string   $namespace
     * @param string[] &$imports
     */
    private static function addImportForType($type, $namespace, array &$imports): void
    {
        if (self::isAliased($type, $imports)) {
            return;
        }

        if (false !== ($first_part = strstr($type, '\\', true))) {
            // Sub namespace;
            $imports[$first_part] = $namespace . '\\' . $first_part;

            return;
        }

        // Inside own namespace
        if (self::getPlainImportIfExists($type, $imports)) {
            return;
        }

        // Not already imported
        $imports[] = $namespace . '\\' . $type;
    }

    /**
     * Returns true if the given class name is in an aliased namespace, false
     * otherwise.
     *
     * @param string   $name
     * @param string[] $imports
     * @return bool
     */
    private static function isAliased($name, array $imports): bool
    {
        // Imports with alias have the alias as key, otherwise it has a numerical index
        $aliases = array_filter(array_keys($imports), 'is_string');
        foreach ($aliases as $alias) {
            if (strpos($name, $alias) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string   $type
     * @param string[] $imports
     * @return string|null
     */
    private static function getPlainImportIfExists($type, $imports): ?string
    {
        foreach ($imports as $alias => $import) {
            if (is_numeric($alias) && substr($import, -1 - \strlen($type)) === '\\' . $type) {
                return $import;
            }
        }

        return null;
    }

    /**
     * Return the fully qualified class name based on the use statements in
     * the current file.
     *
     * @param string   $name
     * @param string[] $imports
     * @return string
     */
    private static function fqcn($name, array $imports): string
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
    public function generateAccessors(PropertyInformationInterface $info): string
    {
        $code = '';

        // Check if there is enough information to generate accessors.
        if ($info->isGenerator() && $info->getType() === null) {
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
            $generated_enumerator_accessors = [];
            foreach ($info->getEnumeratorsToGenerate() as $enumerator) {
                // Unhandy way to get the short name of the class since we can't use native ReflectionClass here because
                // the composer autoload process is not finalized at this point.
                $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $enumerator->getEnumeratorClass());
                $class_ns   = str_replace(DIRECTORY_SEPARATOR, '\\', dirname($class_path)) . '\\' . $this->namespace;
                $class_name = $class_ns . '\\' . basename($class_path) . $this->enum_name_suffix;
                $property   = $enumerator->getPropertyName() ?? Inflector::tableize(
                    basename($class_path) . $this->enum_name_suffix
                );

                if (isset($generated_enumerator_accessors[$property])) {
                    continue;
                }
                $generated_enumerator_accessors[$property] = $enumerator->getEnumeratorClass();

                $code .= $this->enum_get->render([
                    'property'      => $info->isGenerator() ? $info->getName() : $enumerator->getName(),
                    'name'          => Inflector::classify($property),
                    'class_name'    => $class_name,
                    'info'          => $info,
                    'enum_class'    => $enumerator->getEnumeratorClass(),
                    'enum_property' => $property,
                    'add_property'  => empty($enumerator->getPropertyName()),
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
                $code .= $this->set->render(['property' => $info, 'PHP_INT_SIZE' => PHP_INT_SIZE]) . PHP_EOL;
            }
        }

        return $code;
    }

    /**
     * {@inheritdoc}
     */
    public function writeKeyRegistriesForPackage(): bool
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
                'hostname'  => gethostname(),
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
     * @param string[] $imports
     * @return string[]
     */
    private function getUniqueImports(array $imports): array
    {
        uksort($imports, function ($a, $b) use ($imports) {
            $alias_a = is_numeric($a) ? " as $a;" : '';
            $alias_b = is_numeric($b) ? " as $b;" : '';

            return strcmp($imports[$a] . $alias_a, $imports[$b] . $alias_b);
        });

        $unique_imports = [];
        $next           = null;
        do {
            $key   = key($imports);
            $value = current($imports);
            $next  = next($imports);

            if ($value === $next && $key === key($imports)) {
                continue;
            }

            if ($key) {
                $unique_imports[$key] = $value;
                continue;
            }

            $unique_imports[] = $value;
        } while ($next !== false);

        return $unique_imports;
    }

    /**
     * Ensures the entity class complies to the "standard" for holding parameters.
     *
     * @param string $entity_class
     */
    private function validateEnumEntity(string $entity_class): void
    {
        if (!\class_exists($entity_class)) {
            return;
        }
        if (false === \in_array(EnumeratorCompatibleEntityInterface::class, class_implements($entity_class))) {
            throw new \LogicException(sprintf(
                'The entity "%s" must implement "%s" in order to use it with enumerator accessor classes.',
                $entity_class,
                EnumeratorCompatibleEntityInterface::class
            ));
        }
    }
}
