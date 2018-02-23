<?php
// Generated at 2018-02-22 13:12:39 by hiedema on se18-03-73-40-f6-af

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Parameterized;

trait ParameterizedMethodsTrait
{
    private $params_instance;

    /**
     * Returns a parameter collection for \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName.
     *
     * @return \Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated\ParamNameEnum
     */
    public function getParams()
    {
        if (! $this->params_instance) {
            $this->params_instance = new \Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated\ParamNameEnum(
                $this->parameters,
                $this,
                \Hostnet\Component\AccessorGenerator\Generator\fixtures\Parameter::class
            );
        }

        return $this->params_instance;
    }
}
