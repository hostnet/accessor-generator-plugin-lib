<?php
namespace Hostnet\Component\AccessorGenerator\Annotation;

use Doctrine\Common\Annotations\Annotation\Enum;

/**
 * @Annotation(target={"ANNOTATION"})
 */
class Enumerator
{
    /**
     * @var string
     */
    public $value;

    /**
     * @var string
     */
    public $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEnumeratorClass()
    {
        return $this->value;
    }
}
