<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
#[ORM\Entity]
class Address
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;
}
