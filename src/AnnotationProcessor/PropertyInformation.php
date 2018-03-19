<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);


namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

use Doctrine\Common\Annotations\DocParser;
use Doctrine\ORM\Mapping\Column;
use Hostnet\Component\AccessorGenerator\Annotation\Enumerator;
use Hostnet\Component\AccessorGenerator\Annotation\Generate;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionProperty;

/**
 * Gather all the information needed for code generation for the accessor
 * methods. It is possible to register various annotation processors that will
 * process information from the doc blocks and add it to the
 * PropertyInformation.
 */
class PropertyInformation implements PropertyInformationInterface
{
    /**
     * @see PropertyInformationInterface::getType()
     * @var string|null
     */
    private $type;

    /**
     * @see PropertyInformationInterface::getTypeHint()
     * @var string
     */
    private $type_hint = '';

    /**
     * @see PropertyInformationInterface::getFullyQualifiedType()
     * @var string
     */
    private $fully_qualified_type = '';

    /**
     * @see PropertyInformationInterface::getEncryptionAlias()
     * @var string
     */
    private $encryption_alias;

    /**
     * @see PropertyInformationInterface::getIntegerSize()
     * @var int
     */
    private $integer_size = 32; // Be on the safe side for database interaction.

    /**
     * @see PropertyInformationInterface::getLength()
     * @var int
     */
    private $length = 0;

    /**
     * @see PropertyInformationInterface::getPrecision()
     * @var int
     */
    private $precision = 0;

    /**
     * @see PropertyInformationInterface::getScale()
     * @var int
     */
    private $scale = 0;

    /**
     * @see PropertyInformationInterface::isNullable()
     * @var bool|null
     */
    private $nullable;

    /**
     * @see PropertyInformationInterface::isUnique()
     * @var bool|null
     */
    private $unique;

    /**
     * @see PropertyInformationInterface::isGenerator()
     * @var bool
     */
    private $is_generator = false;

    /**
     * @see PropertyInformationInterface::isFixedPointNumber()
     * @var bool
     */
    private $is_fixed_point_number = false;

    /**
     * @see PropertyInformationInterface::getReferencedProperty()
     * @var string
     */
    private $referenced_property = '';

    /**
     * @see PropertyInformationInterface::isCollection()
     * @var bool
     */
    private $is_collection = false;

    /**
     * @see PropertyInformationInterface::isReferencingCollection()
     * @var bool
     */
    private $is_referencing_collection = false;

    /**
     * @see PropertyInformationInterface::willGenerateStrict()
     * @var bool
     */
    private $generate_strict = true;

    /**
     * @see PropertyInformationInterface::willGenerateGet()
     * @var string|null
     */
    private $generate_get;

    /**
     * @see PropertyInformationInterface::willGenerateSet()
     * @var string|null
     */
    private $generate_set;

    /**
     * @see PropertyInformationInterface::willGenerateAdd()
     * @var string|null
     */
    private $generate_add;

    /**
     * @see PropertyInformationInterface::getEnumeratorsToGenerate()
     * Generate enumerator accessors for the given classes.
     *
     * @var string[]
     */
    private $enums_to_generate = [];

    /**
     * @see PropertyInformationInterface::getIndex()
     * @var string|null
     */
    private $index;

    /**
     * @see PropertyInformationInterface::willGenerateRemove()
     * @var string|null
     */
    private $generate_remove;

    /**
     * Information parsed from the PHP
     *
     * @var \ReflectionProperty
     */
    private $property;

    /**
     * Doc Comment parser to parse all
     * the annotations inside a doc block.
     *
     * @var DocParser
     */
    private $parser;

    /**
     * List of registered annotation processors
     * that will be used in the parsing of the
     * doc blocks.
     *
     * @var AnnotationProcessorInterface[]
     */
    private $annotation_processors;

    public function __construct(ReflectionProperty $property)
    {
        $this->property = $property;

        // Not injected because it has no interface and is final
        $this->parser = new DocParser();
    }

    /**
     * Register an AnnotationParser that will be called for every
     * found annotation and may or may not extract information and
     * add it to this object.
     *
     * After all annotation processors are registered call
     * processAnnotations().
     *
     * @param AnnotationProcessorInterface $processor
     */
    public function registerAnnotationProcessor(AnnotationProcessorInterface $processor): void
    {
        $this->annotation_processors[] = $processor;
    }

    /**
     * Start the processing of processAnnotations
     *
     * @return void
     * @throws \OutOfBoundsException
     * @throws \Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException
     * @throws \RuntimeException
     */
    public function processAnnotations(): void
    {
        $class    = $this->property->getClass();
        $imports  = $class ? array_change_key_case($class->getUseStatements()) : [];
        $filename = $class ? $class->getFilename() : 'memory';

        // Get all the namespaces in which annotations reside.
        $namespaces = [];
        foreach ($this->annotation_processors as $processor) {
            $namespaces[] = $processor->getProcessableAnnotationNamespace();
        }

        // Filter all imports that could lead to non loaded annotations,
        // this would let the DocParser explode with an Exception, while
        // the goal is to ignore other annotations besides the one explicitly
        // loaded.
        $without_foreign_annotations = array_filter(
            $imports,
            function ($import) use ($namespaces) {
                foreach ($namespaces as $namespace) {
                    if (stripos($namespace, $import) === 0) {
                        return true;
                    }
                }

                return false;
            }
        );

        $this->parser->setImports($without_foreign_annotations);
        $this->parser->setIgnoreNotImportedAnnotations(true);

        $annotations = $this->parser->parse($this->property->getDocComment(), $filename);

        // If the property is encrypted, column type MUST be string.
        $is_encrypted = false;
        $is_string    = true;
        foreach ($this->annotation_processors as $processor) {
            foreach ($annotations as $annotation) {
                $processor->processAnnotation($annotation, $this);

                if ($annotation instanceof Generate
                    && isset($annotation->encryption_alias)
                ) {
                    $is_encrypted = true;
                }

                if ($annotation instanceof Column
                    && isset($annotation->type)
                    && !in_array($annotation->type, ['string', 'text'])
                ) {
                    $is_string = false;
                }
            }
        }

        if ($is_encrypted && !$is_string) {
            throw new \RuntimeException(sprintf(
                'Property %s in class %s\%s has an encryption_alias set, but is not declared as column type \'string\'',
                $this->getName(),
                $this->getNamespace(),
                $this->getClass()
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDocumentation(): string
    {
        $block = strstr($this->property->getDocComment(), '@', true);
        $block = preg_replace('/\/\*\*\n/m', '', $block);
        $block = preg_replace('/\n[ \t]*[ ]?\*\/$/', '', $block);
        $block = preg_replace('/\n\n/', '', $block);
        $block = preg_replace('/^[ \t]*\*[ ]?/m', '', $block);
        $block = preg_replace('/\n[ \t]*$/', '', $block);

        return $block;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->property->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getClass(): string
    {
        if ($this->property->getClass() !== null) {
            return $this->property->getClass()->getName();
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace(): string
    {
        if ($this->property->getClass()) {
            return $this->property->getClass()->getNamespace();
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefault(): ?string
    {
        return $this->property->getDefault();
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeHint(): string
    {
        return $this->type_hint;
    }

    /**
     * {@inheritdoc}
     */
    public function getFullyQualifiedType(): string
    {
        return $this->fully_qualified_type;
    }

    /**
     * Throw exceptions for invalid types or return the valid type.
     *
     * @see http://php.net/manual/en/language.types.php
     *
     * @param string $type
     *
     * @return string
     * @throws \DomainException
     * @throws \InvalidArgumentException
     */
    private function validateType($type): string
    {
        if (!is_string($type)) {
            throw new \InvalidArgumentException(sprintf('$type is not of type string but of %s', gettype($type)));
        }

        if ('' === $type) {
            throw new \DomainException(sprintf('A type name may not be empty'));
        }

        if ((int)$type) {
            throw new \DomainException(sprintf('A type name may not start with a number. Found %s', $type));
        }

        if (in_array($type, static::getValidTypes(), true)) {
            // Scalar.
            return $type;
        }

        if ('\\' === $type[0] || ctype_upper($type[0])) {
            // Class.
            return $type;
        }

        throw new \DomainException(sprintf('The type %s is not supported for code generation', $type));
    }

    /**
     * Set the type for this property.
     * The type must be one of the set returned
     * by getValidTypes.
     *
     * @see http://php.net/manual/en/language.types.php
     *
     * @param string $type
     *
     * @throws \DomainException
     * @throws \InvalidArgumentException
     * @return PropertyInformation
     */
    public function setType($type): self
    {
        $this->type                          = $this->validateType($type);
        $this->type_hint || $this->type_hint = $this->type;

        return $this;
    }

    /**
     * Manually set the type hint for this property
     * The type hint must be a valid class name starting
     * with \ or a capital letter or one of the set returned
     * by getValidTypes.
     *
     * Only use this method if you are not pleased
     * by the automatic type hint that was already
     * set by the setType method.
     *
     * @see http://php.net/manual/en/language.types.php
     *
     * @param string $type_hint
     *
     * @throws \DomainException
     * @throws \InvalidArgumentException
     * @return PropertyInformation
     */
    public function setTypeHint($type_hint): self
    {
        $this->type_hint = $this->validateType($type_hint);

        return $this;
    }

    /**
     * Set the fully qualified type for this property.
     * The type must be a valid class name starting from
     * the root namespace, so it should start with a \
     *
     * @param string $type
     *
     * @throws \DomainException
     * @throws \InvalidArgumentException
     * @return PropertyInformation
     */
    public function setFullyQualifiedType($type): self
    {
        if (!is_string($type)) {
            throw new \InvalidArgumentException(sprintf('$type is not of type string but of %s', gettype($type)));
        }

        if ('' === $type) {
            $this->fully_qualified_type = '';
        } elseif ((int)$type) {
            throw new \DomainException(sprintf('A type name may not start with a number. Found %s', $type));
        } elseif ($type[0] === '\\') {
            $this->fully_qualified_type = $type;
        } else {
            throw new \DomainException(sprintf('The type %s is not a valid fully qualified class name', $type));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEncryptionAlias(): ?string
    {
        return $this->encryption_alias;
    }

    /**
     * Set the encryption alias for this property, used to encrypt the value before storing.
     *
     * The alias must be added to the composer.json of the app, with the proper keys defined (depending
     * on if the app will encrypt, decrypt or do both).
     *
     * "extra": {
     *     "accessor-generator": {
     *         $encryption_alias: {
     *             public-key:
     *             private-key:
     * ...
     *
     * @param string $encryption_alias
     *
     * @throws \InvalidArgumentException
     * @return PropertyInformation
     */
    public function setEncryptionAlias($encryption_alias): self
    {
        if (!is_string($encryption_alias)) {
            throw new \InvalidArgumentException(sprintf(
                'encryption_alias must be of type string but is of type %s',
                gettype($encryption_alias)
            ));
        }

        if (empty($encryption_alias)) {
            throw new \InvalidArgumentException(sprintf('encryption_alias must not be empty %s', $encryption_alias));
        }

        $this->encryption_alias = $encryption_alias;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * Set the maximum length for this property, if set to 0 it means the
     * length is unbound. Typically a length is between 1 to 255 (including)
     * for varchar fields and 0 (unbounded) for Text, Blob and  Binary fields.
     *
     * @throws \RangeException
     * @throws \InvalidArgumentException
     *
     * @param int $length
     *
     * @return PropertyInformation
     */
    public function setLength($length): self
    {
        // Check type.
        if (!is_int($length)) {
            throw new \InvalidArgumentException(sprintf('Length "%s", is not an integer.', $length));
        }

        // Check Range.
        if ($length < 0) {
            throw new \RangeException(sprintf('Length %d, should be bigger or equal to 0', $length));
        }

        $this->length = $length;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIntegerSize(): int
    {
        return $this->integer_size;
    }

    /**
     * Set the size of the integer that will be stored, in bits.
     *
     * @throws \InvalidArgumentException
     * @throws \RangeException
     *
     * @param int $integer_size
     *
     * @return PropertyInformation
     */
    public function setIntegerSize($integer_size): self
    {
        // Check type.
        if (!is_int($integer_size)) {
            throw new \InvalidArgumentException(sprintf('Size is not an integer but "%s".', gettype($integer_size)));
        }

        // Check Range.
        $max_int_size = PHP_INT_SIZE << 3;
        if ($integer_size <= 0 || $integer_size > $max_int_size) {
            throw new \RangeException(
                sprintf('Integer size %d, does not fit in domain (0, %d]', $integer_size, $max_int_size)
            );
        }

        // Assign.
        $this->integer_size = $integer_size;

        return $this;
    }

    public function setIsGenerator(bool $bool): void
    {
        $this->is_generator = $bool;
    }

    /**
     * {@inheritdoc}
     */
    public function isGenerator(): bool
    {
        return $this->is_generator;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferencedProperty(): string
    {
        return $this->referenced_property;
    }

    /**
     * @see PropertyInformationInterface::getReferencedProperty()
     *
     * @param string $referenced_property
     *
     * @return PropertyInformation
     * @throws \DomainException
     * @throws \InvalidArgumentException
     */
    public function setReferencedProperty($referenced_property): self
    {
        // Check string.
        if (!is_string($referenced_property)) {
            throw new \InvalidArgumentException(
                sprintf('$referenced_property is not of expected type string but of %s)', gettype($referenced_property))
            );
        }
        // Check valid property name
        if ($referenced_property && !ctype_alpha($referenced_property[0])) {
            throw new \DomainException(
                sprintf('$referenced_property (%s) does not start with a alpha character)', $referenced_property)
            );
        }

        $this->referenced_property = $referenced_property;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isCollection(): bool
    {
        return $this->is_collection;
    }

    /**
     * {@inheritdoc}
     */
    public function isReferencingCollection(): bool
    {
        return $this->is_referencing_collection;
    }

    /**
     * {@inheritdoc}
     */
    public function isComplexType(): bool
    {
        return $this->type && !in_array($this->type, self::getValidTypes(), true);
    }

    /**
     * Set to true whenever this property is a collection type like an array or
     * a DoctrineCollection.
     *
     * @param bool $is_collection
     *
     * @return PropertyInformation
     */
    public function setCollection($is_collection): self
    {
        $this->is_collection = false !== $is_collection;

        return $this;
    }

    /**
     * Set to true whenever this property is part of a bidirectional
     * association where the referenced part is a collection - a many side of
     * the relationship.
     *
     * @param bool $is_referencing_collection
     *
     * @return PropertyInformation
     */
    public function setReferencingCollection($is_referencing_collection): self
    {
        $this->is_referencing_collection = false !== $is_referencing_collection;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isFixedPointNumber(): bool
    {
        return $this->is_fixed_point_number;
    }

    /**
     * @see PropertyInformationInterface::isFixedPointNumber()
     *
     * @param bool $is_fixed_point_number
     *
     * @return PropertyInformation
     */
    public function setFixedPointNumber($is_fixed_point_number): self
    {
        $this->is_fixed_point_number = false !== $is_fixed_point_number;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrecision(): int
    {
        return $this->precision;
    }

    /**
     * Set the number of significant digits for a decimal number.
     *
     * This is only applicable to fixed point storage. The type will be a
     * string in that case because PHP has no fixed point numbers.
     *
     * @see http://dev.mysql.com/doc/refman/5.7/en/precision-math-decimal-characteristics.html
     *
     * @throws \InvalidArgumentException
     * @throws \RangeException It has a range of 1 to 65.
     *
     * @param int $precision
     *
     * @return PropertyInformation
     */
    public function setPrecision($precision): self
    {
        // Check type.
        if (!is_int($precision)) {
            throw new \InvalidArgumentException(
                sprintf('Precision is not an integer but of type %s.', gettype($precision))
            );
        }

        // Check range.
        if ($precision < 0 || $precision > 65) {
            throw new \RangeException(
                sprintf('Precision %d, should be in interval [1,65]', $precision)
            );
        }
        $this->precision = $precision;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getScale(): int
    {
        return $this->scale;
    }

    /**
     * Set the number of significant digits after the decimal point.
     * This is only applicable to fixed point storage. The type will be a float
     * in that case because PHP has no fixed point numbers.
     *
     * @see http://dev.mysql.com/doc/refman/5.7/en/precision-math-decimal-characteristics.html
     *
     * @throws \InvalidArgumentException
     * @throws \RangeException
     *
     * @param int $scale
     *
     * @return PropertyInformation
     */
    public function setScale($scale): self
    {
        // Check type.
        if (!is_int($scale)) {
            throw new \InvalidArgumentException(sprintf('Scale is not an integer but of type "%s".', gettype($scale)));
        }

        // Check range.
        if ($scale < 0 || $scale > 30) {
            throw new \RangeException(sprintf('Scale "%d", should be in interval [0,30]', $scale));
        }

        $this->scale = $scale;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): ?bool
    {
        if (null === $this->nullable) {
            return null;
        }

        return $this->nullable;
    }

    /**
     * @see PropertyInformationInterface::isNullable()
     *
     * @param bool $nullable
     *
     * @return PropertyInformation
     */
    public function setNullable($nullable): self
    {
        $this->nullable = false !== $nullable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isUnique(): ?bool
    {
        return $this->unique;
    }

    /**
     * @see PropertyInformationInterface::isUnique()
     *
     * @param bool $unique
     *
     * @return PropertyInformation
     */
    public function setUnique($unique): self
    {
        $this->unique = false !== $unique;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function willGenerateStrict(): bool
    {
        return $this->generate_strict;
    }

    /**
     * @see PropertyInformationInterface::willGenerateStrict()
     *
     * @param bool $generate_strict
     *
     * @return PropertyInformation
     */
    public function setGenerateStrict($generate_strict): self
    {
        $this->generate_strict = false !== $generate_strict;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function willGenerateGet(): bool
    {
        return $this->generate_get && Generate::VISIBILITY_NONE !== $this->generate_get;
    }

    /**
     * @param string $visibility
     *
     * @return PropertyInformation
     */
    public function limitMaximumGetVisibility($visibility): self
    {
        $this->generate_get = Generate::getMostLimitedVisibility($this->generate_get, $visibility);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function willGenerateSet(): bool
    {
        return $this->generate_set && $this->generate_set !== Generate::VISIBILITY_NONE;
    }

    /**
     * {@inheritdoc}
     */
    public function getIndex(): ?string
    {
        return $this->index;
    }

    /**
     * @see PropertyInformationInterface::getIndex()
     *
     * @param string|null $index
     *
     * @return PropertyInformation
     */
    public function setIndex($index = null): self
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @param string $visibility
     *
     * @return PropertyInformation
     */
    public function limitMaximumSetVisibility($visibility): self
    {
        $this->generate_set = Generate::getMostLimitedVisibility($this->generate_set, $visibility);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function willGenerateAdd(): bool
    {
        return $this->generate_add && $this->generate_add !== Generate::VISIBILITY_NONE;
    }

    /**
     * @param string $visibility
     *
     * @return PropertyInformation
     */
    public function limitMaximumAddVisibility($visibility): self
    {
        $this->generate_add = Generate::getMostLimitedVisibility($this->generate_add, $visibility);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function willGenerateRemove(): bool
    {
        return $this->generate_remove && $this->generate_remove !== Generate::VISIBILITY_NONE;
    }

    /**
     * @param string $visibility
     *
     * @return PropertyInformation
     */
    public function limitMaximumRemoveVisibility($visibility): self
    {
        $this->generate_remove = Generate::getMostLimitedVisibility($this->generate_remove, $visibility);

        return $this;
    }

    /**
     * Returns the valid PHP types for validation purposes.
     *
     * @see http://php.net/manual/en/language.types.php
     * @see http://php.net/manual/en/function.gettype.php (double vs float)
     *
     * @return string[]
     */
    private static function getValidTypes()
    {
        return [
            'boolean',
            'integer',
            'float',
            'string',
            'array',
            'resource',
            'object',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getGetVisibility(): ?string
    {
        return $this->generate_get;
    }

    /**
     * {@inheritdoc}
     */
    public function getSetVisibility(): ?string
    {
        return $this->generate_set;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddVisibility(): ?string
    {
        return $this->generate_add;
    }

    /**
     * {@inheritdoc}
     */
    public function getRemoveVisibility(): ?string
    {
        return $this->generate_remove;
    }

    /**
     * {@inheritdoc}
     *
     * @return Enumerator[]
     */
    public function getEnumeratorsToGenerate(): array
    {
        return $this->enums_to_generate;
    }

    public function addEnumeratorToGenerate(Enumerator $enumerator): void
    {
        $this->enums_to_generate[] = $enumerator;
    }

    /**
     * {@inheritdoc}
     */
    public function willGenerateEnumeratorAccessors(): bool
    {
        return count($this->enums_to_generate) > 0;
    }
}
