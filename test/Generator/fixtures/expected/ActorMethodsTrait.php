<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Actor;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Movie;

trait ActorMethodsTrait
{
    /**
     * Get movies
     *
     * @return \Doctrine\Common\Collections\Collection | Movie[]
     * @throws \InvalidArgumentException
     */
    public function getMovies()
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

        return $this->movies;
    }

    /**
     * Add movie
     *
     * @param Movie $movie
     * @return Actor
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function addMovie(Movie $movie)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addMovies() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->movies === null) {
            $this->movies = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->movies->contains($movie)) {
            return $this;
        }

        $this->movies->add($movie);
        $method = new \ReflectionMethod($movie, 'addActor');
        $method->setAccessible(true);
        $method->invoke($movie, $this);
        $method->setAccessible(false);
        return $this;
    }

    /**
     * Remove movie
     *
     * @param Movie $movie
     * @return Actor
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function removeMovie(Movie $movie)
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

        $method = new \ReflectionMethod($movie, 'removeActor');
        $method->setAccessible(true);
        $method->invoke($movie, $this);
        $method->setAccessible(false);
        return $this;
    }
}
