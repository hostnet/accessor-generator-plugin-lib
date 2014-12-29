<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Annotations;
use Symfony\Component\Console as Stupid;

trait AnnotationsMethodsTrait
{
    /**
     * Get stupid
     *
     * @return \DateTime
     * @throws \InvalidArgumentException
     */
    public function getStupid()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getStupid() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->stupid === null) {
            throw new \LogicException(sprintf(
                'Property stupid is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->stupid;
    }

    /**
     * Set stupid
     *
     * @param \DateTime $stupid
     * @return Annotations
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setStupid(\DateTime $stupid)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setStupid() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        $this->stupid = $stupid;
        return $this;
    }
}
