<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class OneToOneNullable
{
    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Nullable", inversedBy="only_one")
     */
    private $one_only;
}
