<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Collection\ImmutableCollection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Actor;

trait ActorMethodsTrait
{
    /**
     * Gets movies
     *
     * @throws \BadMethodCallException
     *
     * @return \Hostnet\Component\AccessorGenerator\Generator\fixtures\Movie[]|ImmutableCollection
     */
    public function getMovies(): iterable
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getMovies() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->movies === null) {
            $this->movies = new \Doctrine\Common\Collections\ArrayCollection();
        }

        return new ImmutableCollection($this->movies);
    }

    /**
     * Adds the given movie to this collection.
     *
     * @throws \BadMethodCallException if the number of arguments is not correct.
     * @throws \Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException
     *
     * @param  \Hostnet\Component\AccessorGenerator\Generator\fixtures\Movie $movie
     * @return $this|Actor
     */
    public function addMovie(\Hostnet\Component\AccessorGenerator\Generator\fixtures\Movie $movie)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addMovies() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        /* @var $this->movies \Doctrine\Common\Collections\ArrayCollection */
        if ($this->movies === null) {
            $this->movies = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->movies->contains($movie)) {
            return $this;
        }

        $this->movies->add($movie);
        try {
            $property = new \ReflectionProperty(\Hostnet\Component\AccessorGenerator\Generator\fixtures\Movie::class, 'a');
        } catch (\ReflectionException $e) {
            throw new \Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
        $property->setAccessible(true);
        if (method_exists(\Hostnet\Component\AccessorGenerator\Generator\fixtures\Movie::class, 'addA')) {
            $adder = new \ReflectionMethod(\Hostnet\Component\AccessorGenerator\Generator\fixtures\Movie::class, 'addA');
            $adder->setAccessible(true);
            $adder->invoke($movie, $this);
            $adder->setAccessible(false);
        } else {
            $collection = $property->getValue($movie);
            if (!$collection) {
                $collection = new \Doctrine\Common\Collections\ArrayCollection();
                $property->setValue($movie, $collection);
            }
            $collection->add($this);
        }
        $property->setAccessible(false);

        return $this;
    }

    /**
     * Removes the given movie from this collection.
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     *
     * @param  \Hostnet\Component\AccessorGenerator\Generator\fixtures\Movie $movie
     * @return $this|Actor
     */
    public function removeMovie(\Hostnet\Component\AccessorGenerator\Generator\fixtures\Movie $movie)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'removeMovies() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (! $this->movies instanceof \Doctrine\Common\Collections\Collection
            || ! $this->movies->contains($movie)
        ) {
            return $this;
        }

        $this->movies->removeElement($movie);

        $property = new \ReflectionProperty(\Hostnet\Component\AccessorGenerator\Generator\fixtures\Movie::class, 'a');
        $property->setAccessible(true);
        $collection = $property->getValue($movie);
        if ($collection) {
            $collection->removeElement($this);
        }
        $property->setAccessible(false);

        return $this;
    }
}
