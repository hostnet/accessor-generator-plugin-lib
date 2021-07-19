<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * 6.3. One-To-One, Bidirectional (Owning)
 *
 * Here is a one-to-one relationship between
 * a Customer and a Cart. The Cart has a
 * reference back to the Customer so it is
 * bidirectional.
 *
 * A bidirectional relationship has both an
 * owning side and an inverse side. Doctrine
 * will only check the owning side of an
 * association for changes.
 *
 * The inverse side has to use the mappedBy
 * attribute of the OneToOne, OneToMany, or
 * ManyToMany mapping declaration. The mappedBy
 * attribute contains the name of the associa-
 * tion-field on the owning side.
 *
 * @ORM\Entity
 */
class Customer
{
    use Generated\CustomerMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\OneToOne(targetEntity="Cart", inversedBy="customer")
     * @AG\Generate
     */
    private $cart;
}
