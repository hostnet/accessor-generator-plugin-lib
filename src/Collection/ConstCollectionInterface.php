<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Collection;

use Doctrine\Common\Collections\Collection;

/**
 * Constant Collection/Array/OrderedMap interface.
 *
 * A constant collection can be read but not changed, functions like filter and
 * partition are still possible because their behaviour is to return a copy
 * rather than to change the collection and return a self-reference. This
 * behaviour is the same for both mutable and immutable collections.
 */
interface ConstCollectionInterface extends \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * Checks whether an element is contained in the collection.
     * This is an O(n) operation, where n is the size of the collection.
     *
     * @param mixed $element The element to search for.
     *
     * @return bool TRUE if the collection contains the element, FALSE otherwise.
     */
    public function contains($element): bool;

    /**
     * Checks whether the collection is empty (contains no elements).
     *
     * @return bool TRUE if the collection is empty, FALSE otherwise.
     */
    public function isEmpty(): bool;

    /**
     * Returns true if the collection contains an element with the specified
     * key/index, false otherwise.
     *
     * @param string|int $key The key/index to check for.
     */
    public function containsKey($key): bool;

    /**
     * Returns the element at the specified key/index.
     *
     * @param string|int $key The key/index of the element to retrieve.
     *
     * @return mixed
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint
     */
    public function get($key);

    /**
     * Returns the keys/indices of the collection, in the order of the
     * corresponding elements in the collection.
     *
     * @return string[]
     */
    public function getKeys(): array;

    /**
     * Returns the values of all elements in the collection, in the order they
     * appear in the collection.
     *
     * @return mixed[]
     */
    public function getValues(): array;

    /**
     * Returns a native PHP array representation of the collection. The array
     * is a copy, as is the case for all arrays in PHP.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Sets the internal iterator to the first element in the collection and
     * returns this element.
     *
     * @return mixed
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint
     */
    public function first();

    /**
     * Sets the internal iterator to the last element in the collection and
     * returns this element.
     *
     * @return mixed
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint
     */
    public function last();

    /**
     * Returns the key/index of the element at the current iterator position.
     *
     * @return int|string
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint
     */
    public function key();

    /**
     * Returns the element of the collection at the current iterator position.
     *
     * @return mixed
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint
     */
    public function current();

    /**
     * Moves the internal iterator position to the next element and returns
     * this element.
     *
     * @return mixed
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint
     */
    public function next();

    /**
     * Returns true if the predicate is true for at least one element, false otherwise.
     *
     * @param \Closure $predicate
     */
    public function exists(\Closure $predicate): bool;

    /**
     * Returns all the elements of this collection that satisfy the given predicate.
     * The order of the elements is preserved.
     *
     * @param \Closure $predicate
     */
    public function filter(\Closure $predicate): Collection;

    /**
     * Returns true if the given predicate is satisfied by all elements in this
     * collection, false otherwise.
     *
     * @param \Closure $predicate
     */
    public function forAll(\Closure $predicate): bool;

    /**
     * Applies the given function to each element in the collection and returns
     * a new collection with the elements returned by the function.
     *
     * @param \Closure $func
     */
    public function map(\Closure $func): Collection;

    /**
     * Partitions this collection in two collections according to a predicate.
     * Keys are preserved in the resulting collections.
     *
     * The first returned element contains the collection of elements where
     * the predicate returned TRUE, the second returned element contains the
     * collection of elements where the predicate returned FALSE.
     *
     * @param \Closure $predicate
     *
     * @return Collection[]
     */
    public function partition(\Closure $predicate): array;

    /**
     * Returns the index/key of a given element or false if the requested
     * element could not be found. The comparison of two elements is strict,
     * that means not only the value but also the type must match. For objects
     * this means reference equality.
     *
     * @param mixed $element The element to search for.
     *
     * @return int|string|bool
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint
     */
    public function indexOf($element);

    /**
     * Extracts a slice of {$length} elements starting at position {$offset}
     * from the Collection.
     *
     * If $length is null it returns all elements from $offset to the end of
     * the Collection.
     *
     * Keys have to be preserved by this method. Calling this method will only
     * return the selected slice and NOT change the elements contained in the
     * collection slice is called on.
     *
     * @param int $offset
     * @param int|null $length
     *
     * @return mixed[]
     */
    public function slice($offset, $length = null): array;
}
