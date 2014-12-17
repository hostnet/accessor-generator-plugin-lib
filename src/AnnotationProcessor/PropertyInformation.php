<?php

namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\DocParser;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionProperty;

/**
 * Gather all the information needed for accessor methods code
 * generation. It is possbile to register various annotation
 * processors that will process information from the doc blocks
 * and add it to the PropertyInformation.
 *
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class PropertyInformation implements PropertyInformationInterface
{
    /**
     * @see PropertyInformationInterface::getType()
     * @var string
     */
    private $type = 'string';

    /**
     * @see PropertyInformationInterface::getFullyQualifiedType()
     * @var string
     */
    private $fully_qualified_type = '';

    /**
     * @see PropertyInformationInterface::getIntegerSize()
     * @var int
     */
    private $integer_size = 32; // Be on the save side for database interaction.

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
     * @var bool
     */
    private $nullable = false;

    /**
     * @see PropertyInformationInterface::isUnique()
     * @var bool
     */
    private $unique = false;

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
     * @see PropertyInformationInterface::willGenerateGet()
     * @var bool
     */
    private $generate_get = false;

    /**
     * @see PropertyInformationInterface::willGenerateSet()
     * @var bool
     */
    private $generate_set = false;

    /**
     * @see PropertyInformationInterface::willGenerateAdd()
     * @var bool
     */
    private $generate_add = false;

    /**
     * @see PropertyInformationInterface::willGenerateRemove()
     * @var bool
     */
    private $generate_remove = false;

    /**
     * Information parsed from the PHP
     *
     * @var ReflectionProperty
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
     * Create new PropertyInformation object based
     * on a Reflected property from PHP source.
     *
     * @param ReflectionProperty $property
     */
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
    public function registerAnnotationProcessor(AnnotationProcessorInterface $processor)
    {
        $this->annotation_processors[] = $processor;
    }

    /**
     * Start the processing of aprocessAnnotations
     *
     * @param  array $annotations
     * @return void
     */
    public function processAnnotations()
    {
        $class    = $this->property->getClass();
        $imports  = $class ? array_change_key_case($class->getUseStatements()) : [];
        $filename = $class ? $class->getFileName() : 'memory';

        $this->parser->setImports($imports);
        $this->parser->setIgnoreNotImportedAnnotations(true);

        $annotations = $this->parser->parse($this->property->getDocComment(), $filename);

        foreach ($this->annotation_processors as $processor) {
            foreach ($annotations as $annotation) {
                $processor->processAnnotation($annotation, $this);
            }
        }
    }

    /**
     * @see PropertyInformationInterface::getDocumentation()
     * @return string
     */
    public function getDocumentation()
    {
        $block = strstr($this->property->getDocComment(), '@', true);
        $block = preg_replace('/^\/\*\*\n/m', '', $block);
        $block = preg_replace('/\n[ \t]*[ ]?\*\/$/', '', $block);
        $block = preg_replace('/\n\n/', '', $block);
        $block = preg_replace('/^[ \t]*\*[ ]?/m', '', $block);
        $block = preg_replace('/\n[ \t]*$/', '', $block);

        return $block;
    }

    /**
     * @see PropertyInformationInterface::getName()
     * @return string
     */
    public function getName()
    {
        return $this->property->getName();
    }

    /**
     * @see PropertyInformationInterface::getClass()
     * @return string
     */
    public function getClass()
    {
        return $this->property->getClass()->getName();
    }

    /**
     * @see PropertyInformationInterface::getNamespace()
     * @return string
     */
    public function getNamespace()
    {
        return $this->property->getClass()->getNamespace();
    }

    /**
     * @see PropertyInformationInterface::getDefault()
     * @return string
     */
    public function getDefault()
    {
        return $this->property->getDefault();
    }

    /**
     * @see PropertyInformationInterface::getType()
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @see PropertyInformationInterface::getFullyQualifiedType()
     * @return string
     */
    public function getFullyQualifiedType()
    {
        return $this->fully_qualified_type;
    }

    /**
     * Set the type for this property.
     * The type must be one of the set returned
     * by getValidTypes.
     *
     * @see http://php.net/manual/en/language.types.php
     * @param  string $type
     * @throws \DomainException
     * @return PropertyInformation
     */
    public function setType($type)
    {
        if (! is_string($type)) {
            throw new \InvalidArgumentException(sprintf('$type is not of type string but of %s', gettype($type)));
        } elseif (empty($type)) {
            throw new \DomainException(sprintf('A type name may not be empty'));
        } elseif ((int)($type)) {
            throw new \DomainException(sprintf('A type name may not start with a number. Found %s', $type));
        } elseif (in_array($type, $this->getValidTypes())) {
            $this->type = $type;
        } elseif (ctype_upper($type[0]) || $type[0] === '\\') {
            $this->type = $type;
        } else {
            throw new \DomainException(sprintf('The type %s is not supported for code generation', $type));
        }

        return $this;
    }

    /**
     * Set the fully qualified type for this property.
     * The type must be a valid class name starting from
     * the root namespace, so it should start with a \
     *
     * @param  string $type
     * @throws \DomainException
     * @return PropertyInformation
     */
    public function setFullyQualifiedType($type)
    {
        if (! is_string($type)) {
            throw new \InvalidArgumentException(sprintf('$type is not of type string but of %s', gettype($type)));
        } elseif (empty($type)) {
            $this->fully_qualified_type = '';
        } elseif ((int)($type)) {
            throw new \DomainException(sprintf('A type name may not start with a number. Found %s', $type));
        } elseif ($type[0] === '\\') {
            $this->fully_qualified_type = $type;
        } else {
            throw new \DomainException(sprintf('The type %s is not a valid fully qualified class name', $type));
        }

        return $this;
    }

    /**
     * @see PropertyInformationInterface::getLength()
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set the maximum length for this property,
     * if set to 0 it means the length is un-
     * boundend. Typically a length is between
     * 1 to 255 (including) for varchar fields
     * and 0 (unbounded) for Text, Blob and
     * Binary fields.
     *
     * @throws \InvalidArgumentException
     * @throws \RangeException
     * @return PropertyInformation
     */
    public function setLength($length)
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
     * @see PropertyInformationInterface::getIntegerSize()
     * @return int
     */
    public function getIntegerSize()
    {
        return $this->integer_size;
    }

    /**
     * Set the size of the integer that will be stored, in bits.
     *
     * @param int $integer_size
     * @throws \InvalidArgumentException
     * @throws \RangeException
     */
    public function setIntegerSize($integer_size)
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

    /**
    * @see PropertyInformationInterface::getReferencedProperty()
    * return string
    */
    public function getReferencedProperty()
    {
        return $this->referenced_property;
    }

    /**
     * @see PropertyInformationInterface::getReferencedProperty()
     * return string
     */
    public function setReferencedProperty($referenced_property)
    {
        // Check string.
        if (! is_string($referenced_property)) {
            throw new \InvalidArgumentException(
                sprintf('$referenced_property is not of excpect type string but of %s)', gettype($referenced_property))
            );
        }
        // Check valid property name
        if ($referenced_property && !ctype_alpha(substr($referenced_property, 0, 1))) {
            throw new \DomainException(
                sprintf('$referenced_property (%s) does not start with a alpha character)', $referenced_property)
            );
        }

        $this->referenced_property = $referenced_property;
        return $this;
    }

    /**
     * @see PropertyInformationInterface::isCollection()
     * @return bool
     */
    public function isCollection()
    {
        return $this->is_collection;
    }

    /**
     * @see PropertyInformationInterface::isReferencingCollection()
     * @return bool
     */
    public function isReferencingCollection()
    {
        return $this->is_referencing_collection;
    }

    /**
     * @see PropertyInformationInterface::isComplexType()
     * @return boolean
     */
    public function isComplexType()
    {
        return !in_array($this->type, self::getValidTypes());
    }

    /**
     * Set to true whenever this property
     * is a collecion type like an array or
     * DoctrineCollection.
     *
     * @param bool $is_collection
     * @return PropertyInformation
     */
    public function setCollection($is_collection)
    {
        $this->is_collection = $is_collection == true;
        return $this;
    }

    /**
     * Set to true whenever this property
     * is part of a bidirectional association
     * where the referenced part is a collection
     * (a many side of the relationship).
     *
     * @param bool $is_referencing_collection
     * @return PropertyInformation
     */
    public function setReferencingCollection($is_referencing_collection)
    {
        $this->is_referencing_collection = $is_referencing_collection == true;
        return $this;
    }

    /**
     * @see PropertyInformationInterface::isFixedPointNumber()
     * @return bool
     */
    public function isFixedPointNumber()
    {
        return $this->is_fixed_point_number;
    }

    /**
     * @see PropertyInformationInterface::isFixedPointNumber()
     * @param bool $is_fixed_point_number
     */
    public function setFixedPointNumber($is_fixed_point_number)
    {
        $this->is_fixed_point_number = $is_fixed_point_number == true;
        return $this;
    }
    /**
     * @see PropertyInformationInterface::getPrecision()
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * Set the number of significant digits for a
     * decimal number. Only applicable to fixed
     * point storage. The type will be string in
     * that case (PHP has no fixed point numbers).
     *
     * @see http://dev.mysql.com/doc/refman/5.7/en/precision-math-decimal-characteristics.html
     * @param int $precicion
     * @throws \InvalidArgumentException
     * @throws \RangeException It has a range of 1 to 65.
     * @return PropertyInformation
     */
    public function setPrecision($precision)
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
     * @see PropertyInformationInterface::getScale()
     * @return number
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * Set the number of significant digits afer the
     * decimal point. Only applicable to fixed
     * point storage. The type will be float in
     * that case (PHP has no fixed point numbers).
     *
     * @see http://dev.mysql.com/doc/refman/5.7/en/precision-math-decimal-characteristics.html
     * @param int $scale
     * @throws \InvalidArgumentException
     * @throws \RangeException
     * @return PropertyInformation
     */
    public function setScale($scale)
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
     * @see PropertyInformationInterface::isNullable()
     * @return boolean
     */
    public function isNullable()
    {
        return $this->nullable || strtolower($this->getDefault()) === 'null';
    }

    /**
     * @see PropertyInformationInterface::isNullable()
     * @param bool $nullable
     * @return PropertyInformation
     */
    public function setNullable($nullable)
    {
        $this->nullable = $nullable == true;
        return $this;
    }

    /**
     * @see PropertyInformationInterface::isUnique()
     * @return boolean
     */
    public function isUnique()
    {
        return $this->unique;
    }

    /**
     * @see PropertyInformationInterface::isUnique()
     * @param bool $unique
     * @return PropertyInformation
     */
    public function setUnique($unique)
    {
        $this->unique = $unique == true;
        return $this;
    }

    /**
     * @see PropertyInformationInterface::willGenerateGet()
     * @return bool
     */
    public function willGenerateGet()
    {
        return $this->generate_get;
    }

    /**
     * @see PropertyInformationInterface::willGenerateGet()
     * @param bool $generate_get
     * @return PropertyInformation
     */
    public function setGenerateGet($generate_get)
    {
        $this->generate_get = $generate_get == true;
        return $this;
    }

    /**
     * @see PropertyInformationInterface::willGenerateSet()
     * @return bool
     */
    public function willGenerateSet()
    {
        return $this->generate_set;
    }

    /**
     * @see PropertyInformationInterface::willGenerateSet()
     * @param bool $generate_set
     * @return PropertyInformation
     */
    public function setGenerateSet($generate_set)
    {
        $this->generate_set = $generate_set == true;
        return $this;
    }

    /**
     * @see PropertyInformationInterface::willGenerateAdd()
     * @return bool
     */
    public function willGenerateAdd()
    {
        return $this->generate_add;
    }

    /**
     * @see PropertyInformationInterface::willGenerateAdd()
     * @param bool $generate_add
     * @return PropertyInformation
     */
    public function setGenerateAdd($generate_add)
    {
        $this->generate_add = $generate_add == true;
        return $this;
    }

    /**
     * @see PropertyInformationInterface::willGenerateRemove()
     * @return bool
     */
    public function willGenerateRemove()
    {
        return $this->generate_remove;
    }

    /**
     * @see PropertyInformationInterface::willGenerateRemove()
     * @param bool $generate_remove
     * @return PropertyInformation
     */
    public function setGenerateRemove($generate_remove)
    {
        $this->generate_remove = $generate_remove == true;
        return $this;
    }

    /**
     * Return the valid PHP types for validation purposes.
     *
     * @see http://php.net/manual/en/language.types.php
     * @see http://php.net/manual/en/function.gettype.php (double vs float)
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
}
