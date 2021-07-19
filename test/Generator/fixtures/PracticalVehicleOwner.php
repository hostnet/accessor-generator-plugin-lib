<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

class PracticalVehicleOwner
{
    use Generated\PracticalVehicleOwnerMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AbstractVehicle", mappedBy="owner")
     * @AG\Generate(
     *     get="none",
     *     remove="none",
     *     type="\Hostnet\Component\AccessorGenerator\Generator\fixtures\VehicleInterface"
     * )
     */
    public $vehicles;
}
