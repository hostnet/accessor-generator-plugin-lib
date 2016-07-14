<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

class NiceFeature implements FeatureInterface
{
    private $software;

    public function getSoftware()
    {
        return $this->software;
    }
}
