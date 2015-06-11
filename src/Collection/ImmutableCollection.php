<?php
namespace Hostnet\Component\AccessorGenerator\Collection;

use Closure;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;

/**
 * Wrapper for Doctrine collections to make them immutable.
 * Implements the ConstCollectionInterface for code completion
 * and use in type hints. Implements Collection for compatebillity
 * with Doctrine.
 *
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class ImmutableCollection implements Collection, ConstCollectionInterface, Selectable
{

    /**
     * @var bool
     */
    private $is_clone = false;

    /**
     * @var Collection|Selectable
     */
    private $collection = null;

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
     * {@inheritDoc}
     * @throws \LogicException when not cloned
     */
    public function add($element)
    {
        if ($this->is_clone) {
            return $this->collection->add($element);
        } else {
            throw new \LogicException('This collection is immutable');
        }
    }

    /**
     * Do not use, this collection is immutable.
     * {@inheritDoc}
     * @throws \LogicException when not cloned
     */
    public function clear()
    {
        if ($this->is_clone) {
            $this->collection->clear();
        } else {
            throw new \LogicException('This collection is immutable');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function contains($element)
    {
        return $this->collection->contains($element);
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty()
    {
        return $this->collection->isEmpty();
    }

    /**
     * Do not use, this collection is immutable.
     * {@inheritDoc}
     * @throws \LogicException when not cloned
     */
    public function remove($key)
    {
        if ($this->is_clone) {
            return $this->collection->remove($key);
        } else {
            throw new \LogicException('This collection is immutable');
        }
    }

    /**
     * Do not use, this collection is immutable.
     * {@inheritDoc}
     * @throws \LogicException when not cloned
     */
    public function removeElement($element)
    {
        if ($this->is_clone) {
            return $this->collection->removeElement($element);
        } else {
            throw new \LogicException('This collection is immutable');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function containsKey($key)
    {
        return $this->collection->containsKey($key);
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        return $this->collection->get($key);
    }

    /**
     * {@inheritDoc}
     */
    public function getKeys()
    {
        return $this->collection->getKeys();
    }

    /**
     * {@inheritDoc}
     */
    public function getValues()
    {
        return $this->collection->getValues();
    }

    public function set($key, $value)
    {
        if ($this->is_clone) {
            $this->collection->set($key, $value);
        } else {
            throw new \LogicException('This collection is immutable');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return $this->collection->toArray();
    }

    /**
     * {@inheritDoc}
     */
    public function first()
    {
        return $this->collection->first();
    }

    /**
     * {@inheritDoc}
     */
    public function last()
    {
        return $this->collection->last();
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return $this->collection->key();
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return $this->collection->current();
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        return $this->collection->next();
    }

    /**
     * {@inheritDoc}
     */
    public function exists(Closure $p)
    {
        return $this->collection->exists($p);
    }

    /**
     * {@inheritDoc}
     */
    public function filter(Closure $p)
    {
        return $this->collection->filter($p);
    }

    /**
     * {@inheritDoc}
     */
    public function forAll(Closure $p)
    {
        return $this->collection->forAll($p);
    }

    /**
     * {@inheritDoc}
     */
    public function map(Closure $func)
    {
        return $this->collection->map($func);
    }

    /**
     * {@inheritDoc}
     */
    public function partition(Closure $p)
    {
        return $this->collection->partition($p);
    }

    /**
     * {@inheritDoc}
     */
    public function indexOf($element)
    {
        return $this->collection->indexOf($element);
    }

    /**
     * {@inheritDoc}
     */
    public function slice($offset, $length = null)
    {
        return $this->collection->slice($offset, $length);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return $this->collection->count();
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return $this->collection->getIterator();
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return $this->collection->offsetExists($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->collection->offsetGet($offset);
    }

    /**
     * Do not use, this collection is immutable.
     * {@inheritDoc}
     * @throws \LogicException when not cloned
     */
    public function offsetSet($offset, $value)
    {
        if ($this->is_clone) {
            $this->collection->offsetSet($offset, $value);
        } else {
            throw new \LogicException('This collection is immutable');
        }
    }

    /**
     * Do not use, this collection is immutable.
     * {@inheritDoc}
     * @throws \LogicException when not cloned
     */
    public function offsetUnset($offset)
    {
        if ($this->is_clone) {
            $this->collection->offsetUnset($offset);
        } else {
            throw new \LogicException('This collection is immutable');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function matching(Criteria $criteria)
    {
        return $this->collection->matching($criteria);
    }

    public function __clone()
    {
        $this->collection = clone $this->collection;
        $this->is_clone   = true;
    }
}
