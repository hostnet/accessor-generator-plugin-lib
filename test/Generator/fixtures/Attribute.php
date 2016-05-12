<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
  * @ORM\Entity
  * @ORM\Table(name="product_attribuut")
  */
class Attribute
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column
     */
    public $name = '';
    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="attributes")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
}
