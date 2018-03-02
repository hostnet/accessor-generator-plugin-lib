<?php

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\Common\Collections\Collection;

interface CleanCarInterface
{
    public function getCleanCars(): Collection;
}
