<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Actor;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Movie;

trait MovieMethodsTrait
{
    /**
     * Get actors
     *
     * @return \Doctrine\Common\Collections\Collection | Actor[]
     * @throws \InvalidArgumentException
     */
    public function getActors()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getActors() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->actors === null) {
            $this->actors = new \Doctrine\Common\Collections\ArrayCollection();
        }

        return $this->actors;
    }

    /**
     * Add actor
     *
     * @param Actor $actor
     * @return Movie
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function addActor(Actor $actor)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addActors() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->actors === null) {
            $this->actors = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->actors->contains($actor)) {
            return $this;
        }

        $this->actors->add($actor);
        $method = new \ReflectionMethod($actor, 'addMovie');
        $method->setAccessible(true);
        $method->invoke($actor, $this);
        $method->setAccessible(false);
        return $this;
    }

    /**
     * Remove actor
     *
     * @param Actor $actor
     * @return Movie
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function removeActor(Actor $actor)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'removeActors() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (! $this->actors instanceof \Doctrine\Common\Collections\Collection
            || ! $this->actors->contains($actor)
        ) {
            return $this;
        }

        $this->actors->removeElement($actor);

        $method = new \ReflectionMethod($actor, 'removeMovie');
        $method->setAccessible(true);
        $method->invoke($actor, $this);
        $method->setAccessible(false);
        return $this;
    }
}
