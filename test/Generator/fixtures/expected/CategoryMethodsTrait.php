<?php
// Generated at 2014-12-10 17:27:16 by hboomsma on se18-03-73-3f-9f-e0

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Category;

trait CategoryMethodsTrait
{
    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection | Category[]
     * @throws \InvalidArgumentException
     */
    public function getChildren()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getChildren() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->children === null) {
            $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        }

        return $this->children;
    }

    /**
     * Add child
     *
     * @param Category $children
     * @return Category
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function addChild(Category $child)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addChildren() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->children === null) {
            $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->children->contains($child)) {
            return $this;
        }

        $this->children->add($child);
        $method = new \ReflectionMethod($child, 'setParent');
        $method->setAccessible(true);
        $method->invoke($child, $this);
        $method->setAccessible(false);
        return $this;
    }

    /**
     * Remove child
     *
     * @param Category $children
     * @return Category
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function removeChild(Category $child)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'removeChildren() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (! $this->children instanceof \Doctrine\Common\Collections\Collection
            || ! $this->children->contains($child)
        ) {
            return $this;
        }

        $this->children->removeElement($child);

        $method = new \ReflectionMethod($child, 'setParent');
        $method->setAccessible(true);
        $method->invoke($child, null);
        $method->setAccessible(false);
        return $this;
    }

    /**
     * Set parent
     *
     * @param Category $parent
     * @return Category
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \LogicException if the association constraints are violated
     * @access friends with Category
     */
    private function setParent(Category $parent = null)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setParent() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($parent && ! $parent->getChildren()->contains($this)) {
            throw new \LogicException('Please use Parent::addChild().');
        } elseif ($parent && $this->parent) {
            throw new \LogicException('Category objects can not be added to more than one Category.');
        }

        $this->parent = $parent;
        return $this;
    }
}
