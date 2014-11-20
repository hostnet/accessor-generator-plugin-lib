<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * 6.10. Many-To-Many, Self-referencing, Uni-directional
 *
 * You can even have a self-referencing
 * many-to-many association. A common scenario
 * is where a User has friends and the target
 * entity of that relationship is a User so
 * it is self referencing. In this example
 * it is unidirectional, because it is self-
 * referencing Node has a field named $in and
 * on named $out.
 *
 * This entity can be used to represent an
 * directed cyclic graph.
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
class Node
{
    use Generated\NodeMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="Node", inversedBy="in")
     * @ORM\JoinTable("Node")
     * @AG\Generate
     */
    private $out;

    /**
     * @ORM\ManyToMany(targetEntity="Node", mappedBy="out")
     */
    private $in;
}
