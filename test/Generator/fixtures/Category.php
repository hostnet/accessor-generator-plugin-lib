<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * 6.7. One-To-Many, Self-referencing
 *
 * You can also setup a one-to-many association
 * that is self-referencing. In this example we
 * setup a hierarchy of Category objects by
 * creating a self referencing relationship.
 * This effectively models a hierarchy of
 * categories and from the database perspective
 * is known as an adjacency list approach.
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
class Category
{
    use Generated\CategoryMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @AG\Generate
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @AG\Generate(set=false, get=false)
     */
    private $parent;
}
