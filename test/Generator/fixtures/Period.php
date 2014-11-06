<?php
namespace Hostnet\Product\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * @ORM\Entity
 * @ORM\Table(name="periode")
 */
class Period
{
    use \Hostnet\Product\Entity\Generated\PeriodMethodsTrait;

    /**
     * A very nice and long
     * multi line description...
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     * @AG\Generate
     * @Hboomsma\Test
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="naam", type="string")
     * @AG\Generate
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="facturatietekst", type="string")
     * @AG\Generate
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(name="maanden", type="integer", length=4)
     * @AG\Generate
     * @var int
     */
    private $deprecated_evil_months;

    /**
     * @ORM\Column(name="eenmalig", type="boolean")
     * @AG\Generate
     *
     * @var boolean | If it's a one-time period
     */
    private $one_time;

    /**
     * @return \DateInterval
     */
    public function getInterval()
    {
        return new \DateInterval('P' . $this->deprecated_evil_months . 'M');
    }

    /**
     * @param \DateInterval $interval The time period described by this Period
     */
    public function setInterval(\DateInterval $interval)
    {
        if ($interval->y
            || $interval->d
            || $interval->h
            || $interval->i
            || $interval->s
            || $interval->invert
        ) {
            throw new \DomainException(
                'Only months are supported, got: ' .
                $interval->format('%yY %mM %dD %hH %iI %sS')
            );
        }
        $this->deprecated_evil_months = $interval->m;
        return  $this;
    }
}
