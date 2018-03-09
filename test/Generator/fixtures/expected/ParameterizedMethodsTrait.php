<?php
// Generated at 2018-03-09 14:46:05 by hiedema on se18-03-73-40-f6-af

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Parameterized;

trait ParameterizedMethodsTrait
{
    
    /**
     * Returns a parameter collection for \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName.
     *
     * @return \Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated\ParamNameEnum
     */
    public function getParams()
    {
        if (! $this->params) {
            $this->params = new \Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated\ParamNameEnum(
                $this->parameters,
                $this,
                \Hostnet\Component\AccessorGenerator\Generator\fixtures\Parameter::class
            );
        }

        return $this->params;
    }


    /**
     * Returns a parameter collection for \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2.
     *
     * @return \Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated\ParamName2Enum
     */
    public function getParams2()
    {
        if (! $this->params2) {
            $this->params2 = new \Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated\ParamName2Enum(
                $this->parameters,
                $this,
                \Hostnet\Component\AccessorGenerator\Generator\fixtures\Parameter::class
            );
        }

        return $this->params2;
    }


    /**
     * Returns a parameter collection for \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName.
     *
     * @return \Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated\ParamNameEnum
     */
    public function getMoreParams()
    {
        if (! $this->more_params) {
            $this->more_params = new \Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated\ParamNameEnum(
                $this->parameters,
                $this,
                \Hostnet\Component\AccessorGenerator\Generator\fixtures\Parameter::class
            );
        }

        return $this->more_params;
    }
}
