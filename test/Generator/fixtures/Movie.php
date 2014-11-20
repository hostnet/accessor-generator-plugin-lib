<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * 6.9. Many-To-Many, Bidirectional (Inversed)
 *
   Here is a similar many-to-many relationship
   as above except this one is bidirectional.
 *
 * Real many-to-many associations are less
 * common. The following example shows a uni-
 * directional association between Genre and
 * Song entities.
 *
 * Why are many-to-many associations less
 * common? Because frequently you want to
 * associate additional attributes with an
 * association, in which case you introduce
 * an association class. Consequently, the
 * direct many-to-many association disappears
 * and is replaced by one-to-many/many-to-one
 * associations between the 3 participating
 * classes.
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
class Movie
{
    use Generated\MovieMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="Actor", mappedBy="movies")
     * @ORM\JoinTable(name="actors_movies")
     * @AG\Generate
     */
    private $actors;
}
