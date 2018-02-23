<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\UseFunction;
use function Hostnet\Component\AccessorGenerator\Generator\fixtures\destroy as kaboom;
use function sprintf;

trait UseFunctionMethodsTrait
{
    /**
     * Gets count
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return string
     */
    public function getCount()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getCount() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->count === null) {
            throw new \LogicException(sprintf(
                'Property count is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->count;
    }

    /**
     * Sets count
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param  string $count
     * @return $this|UseFunction
     */
    public function setCount($count)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setCount() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($count === null
            || is_scalar($count)
            || is_callable([$count, '__toString'])
        ) {
            $count = (string)$count;
        } else {
            throw new \InvalidArgumentException(
                'Parameter count must be convertible to string.'
            );
        }

        $this->count = $count;

        return $this;
    }
}
