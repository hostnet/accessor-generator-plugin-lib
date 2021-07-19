<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * 6.5. One-To-Many, Bidirectional (Owning)
 *
 * Bidirectionallity is forced by the relational
 * database but we want a one-many uni-directional
 * association
 *
 * A one-to-many association has to be
 * bidirectional, unless you are using an
 * additional join-table. This is necessary,
 * because of the foreign key in a one-to-many
 * association being defined on the “many”
 * side. Doctrine needs a many-to-one
 * association that defines the mapping of
 * this foreign key.
 *
 * This bidirectional mapping requires the
 * mappedBy attribute on the OneToMany
 * association and the inversedBy attribute
 * on the ManyToOne association.
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
class Feature implements FeatureInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Software", inversedBy="features")
     * @ORM\JoinColumn(nullable=false)
     * @AG\Generate(get="none", set="none")
     */
    private $software;
}
