<?php
namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

/**
 * Information about a property that is usefull
 * for generating accessor methods.
 *
 * Some of the information is only applicable
 * when the data is persisted in some kind of
 * secondary storage like a RDBMS (ex MySQL).
 *
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
interface PropertyInformationInterface
{

    /**
     * Get the name of the property,
     * without the leading $ sign.
     *
     * @return string
     */
    public function getName();

    /**
     * Get the name of the class implementing
     * this property. Usefull for fluent interfaces
     * that make use of method chaining.
     *
     * @return string
     */
    public function getClass();

    /**
     * Get the namespace of the class implementing
     * this property.
     *
     * @return string
     */
    public function getNamespace();

    /**
     * Get the default assigned value
     * for this property for usage in
     * setters.
     *
     * @return string
     */
    public function getDefault();

    /**
     * Get the type of this property.
     * Can be one of:
     *   'boolean',
     *   'integer',
     *   'float',
     *   'string',
     *   'array',
     *   'resource'
     * or a class name starting with \
     * or a capital letter
     *
     * @return string
     */
    public function getType();

    /**
     * Get the fully qualified name of the
     * type, including the complete namespace
     * and starting with a \.
     *
     * Will return nothing if isComplexType
     * returns false.
     *
     * @return string
     */
    public function getFullyQualifiedType();

    /**
     * Returns if the propery is a scalar
     * type from the php language as boolean
     * integer, array or that is is a complex
     * class type like \DateTime or a user
     * defined class.
     *
     * @return bool
     */
    public function isComplexType();

    /**
     * Get the maximum length for this property.
     * Applicable for string types that are
     * persisted in a database. A value of 0
     * means the length is unrestricted.
     *
     * @return string
     */
    public function getLength();

    /**
     * Get the number of bits used for storage
     * of the number. This is different form
     * precision where digits are counted and
     * not bits.
     *
     * Only applicable when the type is integer.
     *
     * @return int
     */
    public function getIntegerSize();
    /**
     * Returns if this property is a fixed
     * point number to distinguish between
     * floating point and fixed point when
     * the PHP type is float.
     */
    public function isFixedPointNumber();

    /**
     * Get the total amount of significant
     * digits including those following the
     * decimal point (found by getScale).
     *
     * Only valid when isFixedPointNumber
     * returns true.
     *
     * @return int
     */
    public function getPrecision();

    /**
     * Get the amount of digets after
     * the decimal point. Use in combination
     * with getPrecision.
     *
     * Only valid when isFixedPointNumber
     * returns true.
     *
     * @return int
     */
    public function getScale();

    /**
     * Returns the documentation part
     * of the docblock for this property
     * thus the part before the first
     * annotation.
     *
     * @return string
     */
    public function getDocumentation();

    /**
     * If a value in this collection may
     * only appear once. Thus only valid
     * if isCollection returns true.
     *
     * Returns null if not set explicitly,
     * null can be interperted as false.
     *
     *
     * @return bool|null
     */
    public function isUnique();

    /**
     * Returns if this property may be set to
     * null.
     *
     * Returns null if not set explicitly,
     * null can be interperted as false.
     * @return bool|null
     */
    public function isNullable();

    /**
     * Returns if the property is of a collection
     * type like array or DoctrineCollection.
     * @return bool
     */
    public function isCollection();

    /**
     * The property referened for this association
     * on the other side of the association
     * @return string
     */
    public function getReferencedProperty();

    /**
     * Set to true whenever this property
     * is part of a bidirectional association
     * where the referenced part is a collection
     * (a many side of the relationship).

     * @return string
     */
    public function isReferencingCollection();

    /**
     * If set to true getters will throw logic
     * exceptions if the object is not in a valid
     * state according to the nullable columns.
     *
     * Returns null if not set explicitly,
     * null can be interperted as false.
     *
     * @return bool|null
     */
    public function willGenerateStrict();

    /**
     * If a get function should be generated.
     *
     * @return bool
     */
    public function willGenerateGet();

    /**
     * If a set function should be generated.
     *
     * Returns null if not set explicitly,
     * null can be interperted as false.
     *
     * @return bool|null

     */
    public function willGenerateSet();

    /**
     * If a add function should be generated.
     *
     * Returns null if not set explicitly,
     * null can be interperted as false.
     *
     * @return bool|null

     */
    public function willGenerateAdd();

    /**
     * If a remove function should be generated.
     *
     * Returns null if not set explicitly,
     * null can be interperted as false.
     *
     * @return bool|null

     */
    public function willGenerateRemove();
}
