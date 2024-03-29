<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Collection;

use Closure;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;

/**
 * Wrapper for Doctrine collections to make them immutable. Implements the
 * ConstCollectionInterface for code completion and use in type hints.
 * Implements Collection for compatibility with Doctrine.
 */
class ImmutableCollection implements Collection, ConstCollectionInterface, Selectable
{
    /**
     * @var bool
     */
    private $is_clone = false;

    /**
     * @var Collection|Selectable|null
     */
    private $collection;

    /**
     * Wrap a collection to make it immutable.
     *
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Do not use, this collection is immutable.
     *
     * {@inheritdoc}
     *
     * @throws \LogicException if the collection not cloned.
     */
    public function add($element): bool
    {
        if ($this->is_clone) {
            return $this->collection->add($element);
        }

        throw new \LogicException('This collection is immutable');
    }

    /**
     * Do not use, this collection is immutable.
     *
     * {@inheritdoc}
     *
     * @throws \LogicException if the collection is not cloned.
     */
    public function clear(): void
    {
        if (!$this->is_clone) {
            throw new \LogicException('This collection is immutable');
        }

        $this->collection->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function contains($element): bool
    {
        return $this->collection->contains($element);
    }

    public function isEmpty(): bool
    {
        return $this->collection->isEmpty();
    }

    /**
     * Do not use, this collection is immutable.
     * {@inheritdoc}
     * @throws \LogicException if the collection is not cloned.
     * @return mixed
     */
    public function remove($key)
    {
        if ($this->is_clone) {
            return $this->collection->remove($key);
        }

        throw new \LogicException('This collection is immutable');
    }

    /**
     * Do not use, this collection is immutable.
     * {@inheritdoc}
     * @throws \LogicException if the collection is not cloned.
     */
    public function removeElement($element): bool
    {
        if ($this->is_clone) {
            return $this->collection->removeElement($element);
        }

        throw new \LogicException('This collection is immutable');
    }

    /**
     * {@inheritdoc}
     */
    public function containsKey($key): bool
    {
        return $this->collection->containsKey($key);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return $this->collection->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function getKeys(): array
    {
        return $this->collection->getKeys();
    }

    /**
     * {@inheritdoc}
     */
    public function getValues(): array
    {
        return $this->collection->getValues();
    }

    public function set($key, $value): void
    {
        if (!$this->is_clone) {
            throw new \LogicException('This collection is immutable');
        }

        $this->collection->set($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return $this->collection->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function first()
    {
        return $this->collection->first();
    }

    /**
     * {@inheritdoc}
     */
    public function last()
    {
        return $this->collection->last();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->collection->key();
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->collection->current();
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return $this->collection->next();
    }

    public function exists(Closure $predicate): bool
    {
        return $this->collection->exists($predicate);
    }

    public function filter(Closure $predicate): Collection
    {
        return $this->collection->filter($predicate);
    }

    public function forAll(Closure $predicate): bool
    {
        return $this->collection->forAll($predicate);
    }

    public function map(Closure $func): Collection
    {
        return $this->collection->map($func);
    }

    /**
     * {@inheritdoc}
     */
    public function partition(Closure $predicate): array
    {
        return $this->collection->partition($predicate);
    }

    /**
     * {@inheritdoc}
     */
    public function indexOf($element)
    {
        return $this->collection->indexOf($element);
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset, $length = null): array
    {
        return $this->collection->slice($offset, $length);
    }

    public function count(): int
    {
        return $this->collection->count();
    }

    public function getIterator(): \Traversable
    {
        return $this->collection->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return $this->collection->offsetExists($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset): mixed
    {
        return $this->collection->offsetGet($offset);
    }

    /**
     * Do not use, this collection is immutable.
     * {@inheritdoc}
     * @throws \LogicException if the collection is not cloned.
     */
    public function offsetSet($offset, $value): void
    {
        if (!$this->is_clone) {
            throw new \LogicException('This collection is immutable');
        }

        $this->collection->offsetSet($offset, $value);
    }

    /**
     * Do not use, this collection is immutable.
     * {@inheritdoc}
     * @throws \LogicException if the collection is not cloned.
     */
    public function offsetUnset($offset): void
    {
        if (!$this->is_clone) {
            throw new \LogicException('This collection is immutable');
        }

        $this->collection->offsetUnset($offset);
    }

    public function matching(Criteria $criteria): Collection
    {
        return $this->collection->matching($criteria);
    }

    public function __clone()
    {
        $this->collection = clone $this->collection;
        $this->is_clone   = true;
    }
}
