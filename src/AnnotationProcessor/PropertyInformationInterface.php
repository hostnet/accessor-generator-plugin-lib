<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

use Hostnet\Component\AccessorGenerator\Annotation\Enumerator;

/**
 * Represents information about a property that is useful for generating
 * accessor methods.
 *
 * Some of the information is only applicable when the data is persisted in
 * some kind of secondary storage like a RDBMS (e.g. MySQL).
 */
interface PropertyInformationInterface
{
    /**
     * Returns the name of the property, without the leading $ sign.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the name of the class implementing this property.
     *
     * This is useful for creating fluent interfaces that make use of method
     * chaining in code generation.
     *
     * Returns an empty string if the class is unknown or not resolvable.
     *
     * @return string
     */
    public function getClass(): string;

    /**
     * Returns the namespace of the class implementing this property.
     *
     * @return string
     */
    public function getNamespace(): string;

    /**
     * Returns the default assigned value for this property for usage in
     * setters.
     *
     * @return null|string
     */
    public function getDefault(): ?string;

    /**
     * Get the type of this property.
     * Can be one of:
     *   'boolean',
     *   'integer',
     *   'float',
     *   'string',
     *   'array',
     *   'resource'
     * or a class name starting with a namespace separator (\) or a capital
     * letter.
     *
     * @return null|string
     */
    public function getType(): ?string;

    /**
     * Returns the type hint for this property.
     *
     * Can be a class name starting with a namespace separator (\), or an
     * imported - or local type - starting with a capital letter. The type hint
     * is only set if getType also returns a class type.
     *
     * Returns an empty string if this information is undefined.
     *
     * @return string
     */
    public function getTypeHint(): string;

    /**
     * Returns true if this property uses the @Generate annotation.
     *
     * @return bool
     */
    public function isGenerator(): bool;

    /**
     * Returns the fully qualified name of the type, including the complete
     * namespace, prefixed with am additional namespace separator (\).
     *
     * Returns nothing if {isComplexType()} returns false.
     *
     * @return string
     */
    public function getFullyQualifiedType(): string;

    /**
     * Get the encryption alias.
     *
     * @return null|string
     */
    public function getEncryptionAlias(): ?string;

    /**
     * Returns true if the type is a complex type, like an object or array or
     * false if it represents a scalar such as integer, string or boolean.
     *
     * @return bool
     */
    public function isComplexType(): bool;

    /**
     * Returns the maximum length for this property.
     *
     * This is only applicable for string types that are persisted in a
     * database. A value of 0 means the length is unrestricted.
     *
     * @return int
     */
    public function getLength(): int;

    /**
     * Returns the number of bits used for storage of the number.
     *
     * This is different form precision where digits are counted and not bits.
     * This is only applicable when the type is integer.
     *
     * @return int
     */
    public function getIntegerSize(): int;

    /**
     * Returns true if this property represents a fixed point number.
     *
     * This is used to distinguish between floating point and fixed point when
     * the PHP type is float.
     *
     * @return bool
     */
    public function isFixedPointNumber(): bool;

    /**
     * Returns the total amount of significant digits including those following
     * the decimal point (found by getScale).
     *
     * Only valid when {isFixedPointNumber()} returns true.
     *
     * @return int
     */
    public function getPrecision(): int;

    /**
     * Returns the amount of digits after the decimal point. Use in combination
     * with {getPrecision()}.
     *
     * Only applicable when {isFixedPointNumber()} returns true.
     *
     * @return int
     */
    public function getScale(): int;

    /**
     * Returns the documentation part of the doc block for this property, which
     * is everything until the first reference to an annotation.
     *
     * @return string
     */
    public function getDocumentation(): string;

    /**
     * Returns true if a value in this collection may only appear once.
     *
     * Only applicable if {isCollection()} returns true.
     * Returns null if not set explicitly; null can be interpreted as false.
     *
     * @return bool|null
     */
    public function isUnique(): ?bool;

    /**
     * Returns true if this property is nullable, thus may consist of a
     * NULL-value.
     *
     * Returns null if not set explicitly; null can be interpreted as false.
     *
     * @return bool|null
     */
    public function isNullable(): ?bool;

    /**
     * Returns true if the property is of a collection type like array or
     * DoctrineCollection.
     *
     * @return bool
     */
    public function isCollection(): bool;

    /**
     * Returns the referenced property for this association on the other side
     * of the association.
     *
     * @return string
     */
    public function getReferencedProperty(): string;

    /**
     * Return true if this ManyTo{One,Many} relation should be indexed by a
     * specific column.
     *
     * In doctrine this behaviour is used by putting an IndexBy property on a
     * ManyToOne or ManyToMany annotation.
     *
     * @return null|string
     */
    public function getIndex(): ?string;

    /**
     * Returns true whenever this property is part of a bidirectional
     * association where the referenced part is a collection; a many side of
     * the relationship.
     *
     * @return bool
     */
    public function isReferencingCollection(): bool;

    /**
     * Returns true if generated getters will throw logic exceptions if the
     * object is not in a valid state according to the nullable columns.
     *
     * Returns null if not set explicitly; null can be interpreted as false.
     *
     * @return bool
     */
    public function willGenerateStrict(): bool;

    /**
     * Returns true if a getter method should be generated.
     *
     * @return bool
     */
    public function willGenerateGet(): bool;

    /**
     * Returns the string representation of method visibility, e.g. private,
     * protected or public.
     *
     * @return null|string
     */
    public function getGetVisibility(): ?string;

    /**
     * Returns true if a setter method should be generated.
     *
     * Returns null if not set explicitly; null can be interpreted as false.
     *
     * @return bool
     */
    public function willGenerateSet(): bool;

    /**
     * Returns the string representation of method visibility, e.g. private,
     * protected or public.
     *
     * @return null|string
     */
    public function getSetVisibility(): ?string;

    /**
     * Returns true if an "add"-method should be generated.
     *
     * Returns null if not set explicitly; null can be interpreted as false.
     *
     * @return bool
     */
    public function willGenerateAdd(): bool;

    /**
     * Returns the string representation of method visibility, e.g. private,
     * protected or public.
     *
     * @return null|string
     */
    public function getAddVisibility(): ?string;

    /**
     * Returns a list of FQCN's to generate enumerator accessors for.
     *
     * @return Enumerator[]
     */
    public function getEnumeratorsToGenerate(): array;

    /**
     * Returns true if an enumerator accessor will be generated for this property.
     *
     * @return bool
     */
    public function willGenerateEnumeratorAccessors(): bool;

    /**
     * Returns true if a "remove"-method should be generated.
     *
     * Returns null if not set explicitly; null can be interpreted as false.
     *
     * @return bool
     */
    public function willGenerateRemove(): bool;

    /**
     * Returns the string representation of method visibility, e.g. private,
     * protected or public.
     *
     * @return null|string
     */
    public function getRemoveVisibility(): ?string;
}
