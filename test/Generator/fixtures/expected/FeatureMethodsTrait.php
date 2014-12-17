<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Feature;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Software;

trait FeatureMethodsTrait
{
    /**
     * Set software
     *
     * @param Software $software
     * @return Feature
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \LogicException if the association constraints are violated
     * @access friends with Software
     */
    private function setSoftware(Software $software = null)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setSoftware() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($software && ! $software->getFeatures()->contains($this)) {
            throw new \LogicException('Please use Software::addFeature().');
        } elseif ($software && $this->software) {
            throw new \LogicException('Feature objects can not be added to more than one Software.');
        }

        $this->software = $software;
        return $this;
    }
}
