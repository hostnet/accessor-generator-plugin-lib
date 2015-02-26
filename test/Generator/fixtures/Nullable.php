<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * @ORM\Entity
 */
class Nullable
{
    use Generated\NullableMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @AG\Generate(get=false)
     */
    private $datetime_default = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @AG\Generate(get=false)
     */
    private $datetime_nullable;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @AG\Generate(get=false)
     */
    private $datetime_both = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @AG\Generate(get=false)
     */
    private $int_different = 2;

    /**
     * @ORM\ManyToOne(targetEntity="Feature")
     * @ORM\JoinColumn(nullable=true)
     * @AG\Generate(get=false)
     */
    private $feature;

    /**
     * @ORM\ManyToOne(targetEntity="Feature")
     * @AG\Generate(get=false)
     */
    private $an_other_feature;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @AG\Generate
     */
    private $string = null;

    /**
     * @ORM\OneToOne(targetEntity="OneToOneNullable", mappedBy="one_only")
     * @AG\Generate
     */
    private $only_one = null;

    /**
     * @ORM\OneToOne(targetEntity="Item")
     * @AG\Generate
     */
    private $unidirectional_one_to_one = null;
}
