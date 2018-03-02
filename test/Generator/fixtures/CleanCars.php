<?php

declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Collection\ImmutableCollection;

class CleanCars implements CleanCarInterface
{
    use Generated\CleanCarsMethodsTrait;

    /**
     * @ORM\OneToMany(targetEntity="Car")
     * @AG\Generate()
     */
    private $cars;

    public function getCleanCars(): Collection
    {
        return new ImmutableCollection($this->getCars());
    }
}
