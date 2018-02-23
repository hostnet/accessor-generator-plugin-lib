<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Enum\EnumeratorCompatibleEntityInterface;

/**
 * @ORM\Entity()
 */
class Parameter implements EnumeratorCompatibleEntityInterface
{
    use Generated\ParameterMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @AG\Generate(set="none")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Hostnet\Component\AccessorGenerator\Generator\fixtures\Parameterized", inversedBy="parameters")
     * @ORM\JoinColumn(name="parameter_id", referencedColumnName="id")
     */
    private $parameterized;

    /**
     * @ORM\Column(type="string")
     * @AG\Generate(set="none")
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @AG\Generate()
     */
    private $value;

    /**
     * @param Parameterized $parameterized
     * @param string        $name
     * @param string|NULL   $value
     */
    public function __construct($parameterized, string $name, ?string $value)
    {
        $this->parameterized = $parameterized;
        $this->name          = $name;
        $this->value         = $value;
    }
}
