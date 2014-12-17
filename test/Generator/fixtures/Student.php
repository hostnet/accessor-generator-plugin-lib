<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * 6.4. One-To-One, Self-referencing
 *
 * You can define a self-referencing
 * one-to-one relationships like below.
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
class Student implements StudentInterface
{
    use Generated\StudentMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Student")
     * @AG\Generate(type="StudentInterface")
     */
    private $student;
}
