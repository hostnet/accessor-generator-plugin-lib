<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class Shipping
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;
}
