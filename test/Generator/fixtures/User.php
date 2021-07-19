<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * 6.1. Many-To-One, Unidirectional
 *
 * A many-to-one association is the most
 * common association between objects.
 *
 * @ORM\Entity
 */
class User
{
    use Generated\UserMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Address")
     * @AG\Generate
     */
    private $address = null;
}
