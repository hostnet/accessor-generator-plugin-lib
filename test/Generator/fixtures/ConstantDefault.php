<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * @ORM\Entity
 */
class ConstantDefault
{
    use Generated\ConstantDefaultMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @AG\Generate
     **/
    private $weather = Weather::SUN;
}
