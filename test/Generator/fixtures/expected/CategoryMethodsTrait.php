<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Collection\ImmutableCollection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Category;

trait CategoryMethodsTrait
{
    /**
     * Gets children
     *
     * @throws \BadMethodCallException
     *
     * @return \Hostnet\Component\AccessorGenerator\Generator\fixtures\Category[]|ImmutableCollection
     */
    public function getChildren(): iterable
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getChildren() has no arguments but %d given.',
                    \func_num_args()
                )
            );
        }

        if ($this->children === null) {
            $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        }

        return new ImmutableCollection($this->children);
    }

    /**
     * Adds the given child to this collection.
     *
     * @throws \BadMethodCallException if the number of arguments is not correct.
     * @throws \LogicException         if a member was added that already exists within the collection.
     * @throws \Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException
     *
     * @param Category $child
     *
     * @return $this|Category
     */
    public function addChild(Category $child)
    {
        if (\func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addChildren() has one argument but %d given.',
                    \func_num_args()
                )
            );
        }

        /* @var $this->children \Doctrine\Common\Collections\ArrayCollection */
        if ($this->children === null) {
            $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->children->contains($child)) {
            return $this;
        }

        $this->children->add($child);
        try {
            $property = new \ReflectionProperty(Category::class, 'parent');
        } catch (\ReflectionException $e) {
            throw new \Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
        $property->setAccessible(true);
        $value = $property->getValue($child);
        if ($value && $value !== $this) {
            throw new \LogicException('Child can not be added to more than one Category.');
        }
        $property->setValue($child, $this);
        $property->setAccessible(false);

        return $this;
    }

    /**
     * Removes the given child from this collection.
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     *
     * @param Category $child
     *
     * @return $this|Category
     */
    public function removeChild(Category $child)
    {
        if (\func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'removeChildren() has one argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if (! $this->children instanceof \Doctrine\Common\Collections\Collection
            || ! $this->children->contains($child)
        ) {
            return $this;
        }

        $this->children->removeElement($child);

        $property = new \ReflectionProperty(Category::class, 'parent');
        $property->setAccessible(true);
        $property->setValue($child, null);
        $property->setAccessible(false);

        return $this;
    }

    /**
     * Gets parent
     *
     * @throws \BadMethodCallException
     *
     * @return Category|null
     */
    public function getParent(): ?Category
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getParent() has no arguments but %d given.',
                    \func_num_args()
                )
            );
        }

        return $this->parent;
    }

    /**
     * Sets parent
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     *
     * @param Category $parent
     *
     * @return $this|Category
     */
    public function setParent(Category $parent = null)
    {
        if (\func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setParent() has one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }
        // Create reflection property.
        $property = new \ReflectionProperty(Category::class, 'children');
        $property->setAccessible(true);

        // Unset old value and set the new value
        if ($this->parent) {
            $value = $property->getValue($this->parent);
            $value && $value->removeElement($this);
        }

        // keeping the inverse side up-to-date.
        if ($parent) {
            $value = $property->getValue($parent);
            if ($value) {
                $parent && $value->add($this);
            } else {
                $property->setValue($parent, new \Doctrine\Common\Collections\ArrayCollection([$this]));
            }
        }

        // Update the accessible flag to disallow further again.
        $property->setAccessible(false);

        $this->parent = $parent;

        return $this;
    }
}
