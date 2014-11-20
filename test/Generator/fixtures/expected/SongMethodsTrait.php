<?php
// Generated at 2014-12-10 17:27:16 by hboomsma on se18-03-73-3f-9f-e0

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Genre;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Song;

trait SongMethodsTrait
{
    /**
     * Get genres
     *
     * @return \Doctrine\Common\Collections\Collection | Genre[]
     * @throws \InvalidArgumentException
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

        return $this->genres;
    }

    /**
     * Add genre
     *
     * @param Genre $genres
     * @return Song
     * @throws \BadMethodCallException if the number of arguments is not correct
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

        if ($this->genres === null) {
            $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->genres->contains($genre)) {
            return $this;
        }

        $this->genres->add($genre);
        return $this;
    }

    /**
     * Remove genre
     *
     * @param Genre $genres
     * @return Song
     * @throws \BadMethodCallException if the number of arguments is not correct
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
