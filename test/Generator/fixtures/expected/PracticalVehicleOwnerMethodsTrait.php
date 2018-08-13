<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\AbstractVehicle;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\PracticalVehicleOwner;

trait PracticalVehicleOwnerMethodsTrait
{
    /**
     * Adds the given vehicle to this collection.
     *
     * @throws \BadMethodCallException if the number of arguments is not correct.
     * @throws \LogicException         if a member was added that already exists within the collection.
     * @throws \Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException
     *
     * @param \Hostnet\Component\AccessorGenerator\Generator\fixtures\VehicleInterface $vehicle
     *
     * @return $this|PracticalVehicleOwner
     */
    public function addVehicle(\Hostnet\Component\AccessorGenerator\Generator\fixtures\VehicleInterface $vehicle)
    {
        if (\func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addVehicles() has one argument but %d given.',
                    \func_num_args()
                )
            );
        }

        /* @var $this->vehicles \Doctrine\Common\Collections\ArrayCollection */
        if ($this->vehicles === null) {
            $this->vehicles = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->vehicles->contains($vehicle)) {
            return $this;
        }

        $this->vehicles->add($vehicle);
        try {
            if ($vehicle instanceof AbstractVehicle) {
                $property = new \ReflectionProperty(AbstractVehicle::class, 'owner');
            } else {
                $property = new \ReflectionProperty($vehicle, 'owner');
            }
        } catch (\ReflectionException $e) {
            throw new \Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
        $property->setAccessible(true);
        $value = $property->getValue($vehicle);
        if ($value && $value !== $this) {
            throw new \LogicException('Vehicle can not be added to more than one PracticalVehicleOwner.');
        }
        $property->setValue($vehicle, $this);
        $property->setAccessible(false);

        return $this;
    }
}
