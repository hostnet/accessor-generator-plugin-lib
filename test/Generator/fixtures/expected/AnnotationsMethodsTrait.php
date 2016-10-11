<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Annotations;
use Symfony\Component\Console as Stupid;

trait AnnotationsMethodsTrait
{
    /**
     * Gets stupid
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return \DateTime
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
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->stupid;
    }

    /**
     * Sets stupid
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     *
     * @param  \DateTime $stupid
     * @return $this|Annotations
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
