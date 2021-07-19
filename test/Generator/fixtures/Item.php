<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * 6.2. One-To-One, Unidirectional
 *
 * Here is an example of a one-to-one association with a Item
 * entity that references one Shipping entity. The Shipping
 * does not reference back to the Product so that the reference
 * is said to be unidirectional, in one direction only.
 *
 * @ORM\Entity
 */
class Item
{
    use Generated\ItemMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @AG\Generate
     * @ORM\JoinColumn(nullable=false)
     * @ORM\OneToOne(targetEntity="Shipping")
     **/
    private $shipping;
}
