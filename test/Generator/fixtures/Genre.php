<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\Column
     */
    private $id;
}
