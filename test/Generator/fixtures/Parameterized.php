<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * @ORM\Entity
 */
class Parameterized
{
    use Generated\ParameterizedMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column
     */
    private $id;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Hostnet\Component\AccessorGenerator\Generator\fixtures\Parameter",
     *     mappedBy="component",
     *     cascade={"persist"}
     * )
     *
     * @AG\Generate(enumerators={
     *     @AG\Enumerator("\Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName", property="params")
     * })
     */
    private $parameters;

    /**
     * @var ParamName
     */
    private $params;

    public function __construct()
    {
        $this->parameters = new ArrayCollection();
    }
}
