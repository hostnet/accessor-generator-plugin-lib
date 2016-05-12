<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Collection\ImmutableCollection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Actor;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Movie;

trait MovieMethodsTrait
{
    /**
     * Get a
     *
     * @return \Hostnet\Component\AccessorGenerator\Generator\fixtures\Actor[]
     * @return \Hostnet\Component\AccessorGenerator\Collection\ConstCollectionInterface
     * @throws \InvalidArgumentException
     */
    public function getA()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getA() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->a === null) {
            $this->a = new \Doctrine\Common\Collections\ArrayCollection();
        }

        return new ImmutableCollection($this->a);
    }

    /**
     * Add a
     *
     * @param Actor $a
     * @return Movie
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function addA(Actor $a)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addA() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->a === null) {
            $this->a = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->a->contains($a)) {
            return $this;
        }

        $this->a->add($a);
        $property = new \ReflectionProperty(Actor::class, 'movies');
        $property->setAccessible(true);
        if (method_exists(Actor::class, 'addMovie')) {
            $adder = new \ReflectionMethod(Actor::class, 'addMovie');
            $adder->setAccessible(true);
            $adder->invoke($a, $this);
            $adder->setAccessible(false);
        } else {
            $collection = $property->getValue($a);
            if (!$collection) {
                $collection = new \Doctrine\Common\Collections\ArrayCollection();
                $property->setValue($a, $collection);
            }
            $collection->add($this);
        }
        $property->setAccessible(false);
        return $this;
    }

    /**
     * Remove a
     *
     * @param Actor $a
     * @return Movie
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function removeA(Actor $a)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'removeA() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (! $this->a instanceof \Doctrine\Common\Collections\Collection
            || ! $this->a->contains($a)
        ) {
            return $this;
        }

        $this->a->removeElement($a);

        $property = new \ReflectionProperty(Actor::class, 'movies');
        $property->setAccessible(true);
        $collection = $property->getValue($a);
        if ($collection) {
            $collection->removeElement($this);
        }
        $property->setAccessible(false);
        return $this;
    }
}
