<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * 6.8. Many-To-Many, Unidirectional
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
 * @ORM\Entity
 */
class Song
{
    use Generated\SongMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="Genre")
     * @ORM\JoinTable(name="songs_genres")
     * @AG\Generate
     */
    private $genres;
}
