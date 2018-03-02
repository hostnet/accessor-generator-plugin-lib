<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Collection\ImmutableCollection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Car;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\CleanCars;

trait CleanCarsMethodsTrait
{
    /**
     * Gets cars
     *
     * @throws \BadMethodCallException
     *
     * @return \Hostnet\Component\AccessorGenerator\Generator\fixtures\Car[]|ImmutableCollection
     */
    public function getCars()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getCars() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->cars === null) {
            $this->cars = new \Doctrine\Common\Collections\ArrayCollection();
        }

        return new ImmutableCollection($this->cars);
    }

    /**
     * Adds the given car to this collection.
     *
     * @throws \BadMethodCallException if the number of arguments is not correct.
     * @throws \LogicException         if a member was added that already exists within the collection.
     *
     * @param  Car $car
     * @return $this|CleanCars
     */
    public function addCar(Car $car)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addCars() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        /* @var $this->cars \Doctrine\Common\Collections\ArrayCollection */
        if ($this->cars === null) {
            $this->cars = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->cars->contains($car)) {
            return $this;
        }

        $this->cars->add($car);

        return $this;
    }

    /**
     * Removes the given car from this collection.
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     *
     * @param  Car $car
     * @return $this|CleanCars
     */
    public function removeCar(Car $car)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'removeCars() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (! $this->cars instanceof \Doctrine\Common\Collections\Collection
            || ! $this->cars->contains($car)
        ) {
            return $this;
        }

        $this->cars->removeElement($car);


        return $this;
    }
}
