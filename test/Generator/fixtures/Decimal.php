<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * Different decimal columns
 * @ORM\Entity
 */
class Decimal
{
    use Generated\DecimalMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", scale=0, precision=10)
     * @AG\Generate(get="none")
     */
    private $decimal_0_10;

    /**
     * @ORM\Column(type="decimal", scale=1, precision=10)
     * @AG\Generate(get="none")
     */
    private $decimal_1_10;

    /**
     * @ORM\Column(type="decimal", scale=5, precision=10)
     * @AG\Generate(get="none")
     */
    private $decimal_5_10;

    /**
     * @ORM\Column(type="decimal", scale=10, precision=10)
     * @AG\Generate(get="none")
     */
    private $decimal_10_10;

    /**
     * @ORM\Column(type="decimal", scale=18, precision=20)
     * @AG\Generate(get="none")
     */
    private $decimal_18_20;

    /**
     * @ORM\Column(type="decimal", scale=19, precision=20)
     * @AG\Generate(get="none")
     */
    private $decimal_19_20 = '1.2345678901234567890';

    /**
     * @ORM\Column(type="decimal", scale=30, precision=65)
     * @AG\Generate(get="none")
     */
    private $decimal_30_65;
}
