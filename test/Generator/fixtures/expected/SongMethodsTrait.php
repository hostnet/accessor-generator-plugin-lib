<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Collection\ImmutableCollection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Genre;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Song;

trait SongMethodsTrait
{
    /**
     * Gets genres
     *
     * @throws \BadMethodCallException
     *
     * @return \Hostnet\Component\AccessorGenerator\Generator\fixtures\Genre[]|ImmutableCollection
     */
    public function getGenres()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getGenres() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->genres === null) {
            $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
        }

        return new ImmutableCollection($this->genres);
    }

    /**
     * Adds the given genre to this collection.
     *
     * @throws \BadMethodCallException if the number of arguments is not correct.
     *
     * @param  Genre $genre
     * @return $this|Song
     */
    public function addGenre(Genre $genre)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addGenres() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        /* @var $this->genres \Doctrine\Common\Collections\ArrayCollection */
        if ($this->genres === null) {
            $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->genres->contains($genre)) {
            return $this;
        }

        $this->genres->add($genre);

        return $this;
    }

    /**
     * Removes the given genre from this collection.
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     *
     * @param  Genre $genre
     * @return $this|Song
     */
    public function removeGenre(Genre $genre)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'removeGenres() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (! $this->genres instanceof \Doctrine\Common\Collections\Collection
            || ! $this->genres->contains($genre)
        ) {
            return $this;
        }

        $this->genres->removeElement($genre);


        return $this;
    }
}
