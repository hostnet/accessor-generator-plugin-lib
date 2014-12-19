<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures as This;

/**
  * @ORM\Entity
  * @ORM\Table(name="product_attribuut")
  */
class Attribute
{
    use Generated\AttributeMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="This\Product", inversedBy="attributes")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
}
