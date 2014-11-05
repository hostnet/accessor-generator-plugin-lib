<?php

namespace Hostnet\Component\AccessorGenerator;

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
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::getType()
     * @var string
     */
    private $type = 'string';

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::getIntegerSize()
     * @var int
     */
    private $integer_size = 32; // Be on the save side for database interaction.

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::getLength()
     * @var int
     */
    private $length = 0;

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::getPrecision()
     * @var int
     */
    private $precision = 0;

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::getScale()
     * @var int
     */
    private $scale = 0;

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::isNullable()
     * @var bool
     */
    private $nullable = false;

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::isUnique()
     * @var bool
     */
    private $unique = false;

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::isFixedPointNumber()
     * @var bool
     */
    private $is_fixed_point_number = false;

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::isCollection()
     * @var bool
     */
    private $is_collection = false;

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::willGenerateGet()
     * @var bool
     */
    private $generate_get = false;

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::willGenerateSet()
     * @var bool
     */
    private $generate_set = false;

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::willGenerateAdd()
     * @var bool
     */
    private $generate_add = false;

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::willGenerateRemove()
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
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::getDocumentation()
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
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::getName()
     * @return string
     */
    public function getName()
    {
        return $this->property->getName();
    }


    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::getDefault()
     * @return string
     */
    public function getDefault()
    {
        return $this->property->getDefault();
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::getType()
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the type for this property.
     * The type must be one of the set returned
     * by getValidTypes.
     *
     * @see http://php.net/manual/en/language.types.php
     * @param  string $type
     * @throws \DomainException
     * @return \Hostnet\Component\AccessorGenerator\PropertyInformation
     */
    public function setType($type)
    {
        if (in_array($type, $this->getValidTypes())) {
            $this->type = $type;
        } elseif (is_string($type) && substr($type, 0, 1) === '\\') {
            $this->type = $type;
        } else {
            throw new \DomainException(sprintf('The type %s is not supported for code generation', $type ?: 'empty'));
        }

        return $this;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::getLength()
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
     * @return \Hostnet\Component\AccessorGenerator\PropertyInformation
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
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::getIntegerSize()
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
            throw new \InvalidArgumentException(sprintf('Size "%s" is not an integer.', $integer_size ?: 'empty'));
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
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::isCollection()
     * @return bool
     */
    public function isCollection()
    {
        return $this->is_collection;
    }

    /**
     * Set to true whenever this property
     * is a collecion type like an array or
     * DoctrineCollection.
     *
     * @param bool $is_collection
     * @return \Hostnet\Component\AccessorGenerator\PropertyInformation
     */
    public function setCollection($is_collection)
    {
        $this->is_collection = $is_collection == true;
        return $this;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::isFixedPointNumber()
     * @return bool
     */
    public function isFixedPointNumber()
    {
        return $this->is_fixed_point_number;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::isFixedPointNumber()
     * @param bool $is_fixed_point_number
     */
    public function setFixedPointNumber($is_fixed_point_number)
    {
        $this->is_fixed_point_number = $is_fixed_point_number == true;
        return $this;
    }
    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::getPrecision()
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * Set the number of significant digits for a
     * decimal number. Only applicable to fixed
     * point storage. The type will be float in
     * that case (PHP has no fixed point numbers).
     *
     * @param int $precicion
     * @throws \InvalidArgumentException
     * @return \Hostnet\Component\AccessorGenerator\PropertyInformation
     */
    public function setPrecision($precision)
    {
        // Check type.
        if (!is_int($precision)) {
            throw new \InvalidArgumentException(sprintf('Precision "%s" is not an integer.', $precision ?: 'empty'));
        }

        $max_precision = self::numberOfSignificantDecimalDigitsFloat();

        // Check range.
        if ($precision < 0 || $precision > $max_precision) {
            throw new \RangeException(
                sprintf('Precision %d, should be in interval [0,%d]', $precision, $max_precision)
            );
        }
        $this->precision = $precision;
        return $this;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::getScale()
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
     * @param int $scale
     * @throws \InvalidArgumentException
     * @return \Hostnet\Component\AccessorGenerator\PropertyInformation
     */
    public function setScale($scale)
    {
        // Check type.
        if (!is_int($scale)) {
            throw new \InvalidArgumentException(sprintf('Scale "%s" is not an integer.', $scale ?: 'empty'));
        }

        // Check range.
        $max_scale = self::numberOfSignificantDecimalDigitsFloat();
        if ($scale < 0 || $scale > $max_scale) {
            throw new \RangeException(sprintf('Scale %d, should be in interval [0,%d]', $scale, $max_scale));
        }

        $this->scale = $scale;
        return $this;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::isNullable()
     * @return boolean
     */
    public function isNullable()
    {
        return $this->nullable;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::isNullable()
     * @param bool $nullable
     * @return \Hostnet\Component\AccessorGenerator\PropertyInformation
     */
    public function setNullable($nullable)
    {
        $this->nullable = $nullable == true;
        return $this;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::isUnique()
     * @return boolean
     */
    public function isUnique()
    {
        return $this->unique;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::isUnique()
     * @param bool $unique
     * @return \Hostnet\Component\AccessorGenerator\PropertyInformation
     */
    public function setUnique($unique)
    {
        $this->unique = $unique == true;
        return $this;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::willGenerateGet()
     * @return bool
     */
    public function willGenerateGet()
    {
        return $this->generate_get;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::willGenerateGet()
     * @param bool $generate_get
     * @return \Hostnet\Component\AccessorGenerator\PropertyInformation
     */
    public function setGenerateGet($generate_get)
    {
        $this->generate_get = $generate_get == true;
        return $this;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::willGenerateSet()
     * @return bool
     */
    public function willGenerateSet()
    {
        return $this->generate_set;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::willGenerateSet()
     * @param bool $generate_set
     * @return \Hostnet\Component\AccessorGenerator\PropertyInformation
     */
    public function setGenerateSet($generate_set)
    {
        $this->generate_set = $generate_set == true;
        return $this;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::willGenerateAdd()
     * @return bool
     */
    public function willGenerateAdd()
    {
        return $this->generate_add;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::willGenerateAdd()
     * @param bool $generate_add
     * @return \Hostnet\Component\AccessorGenerator\PropertyInformation
     */
    public function setGenerateAdd($generate_add)
    {
        $this->generate_add = $generate_add == true;
        return $this;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::willGenerateRemove()
     * @return bool
     */
    public function willGenerateRemove()
    {
        return $this->generate_remove;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\PropertyInformationInterface::willGenerateRemove()
     * @param bool $generate_remove
     * @return \Hostnet\Component\AccessorGenerator\PropertyInformation
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

    final public static function numberOfSignificantDecimalDigitsFloat()
    {
        // In PHP IEEE 754 floats are used.

        // In a 64 bit system it has a mantissa
        // of 52 explicitly stored bits with
        // a significance of 53 bits.
        //
        // decimal_positions = 15 < ln(2^53)/ln(2) < 16.
        // @see http://en.wikipedia.org/wiki/Single-precision_floating-point_format
        //
        //
        // In a 32 bit system it has a mantissa
        // of 23 explicitly stored bits with
        // a significance of 24 bits.
        //
        // decimal_positions = 6 < ln(2^24)/ln(2) < 7.
        // @see http://en.wikipedia.org/wiki/Double-precision_floating-point_format
        return PHP_INT_SIZE === 8 ? 15 : 7;
    }
}
