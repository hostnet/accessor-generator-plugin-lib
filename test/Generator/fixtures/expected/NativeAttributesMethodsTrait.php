<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Attribute as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\NativeAttributes;

trait NativeAttributesMethodsTrait
{
    /**
     * Gets label
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return string
     */
    public function getLabel(): string
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getLabel() has no arguments but %d given.',
                    \func_num_args()
                )
            );
        }
        if ($this->label === null) {
            throw new \LogicException(sprintf(
                'Property label is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->label;
    }

    /**
     * Sets label
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \LengthException if the length of the value is to long
     *
     * @param string $label
     *
     * @return $this|NativeAttributes
     */
    public function setLabel($label)
    {
        if (\func_num_args() !== 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setLabel() has one argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if ($label === null
            || \is_scalar($label)
            || \is_callable([$label, '__toString'])
        ) {
            $label = (string)$label;
        } else {
            throw new \InvalidArgumentException(
                'Parameter label must be convertible to string.'
            );
        }

        if (\strlen($label) > 255) {
            throw new \LengthException('Parameter \'$label\' should not be longer than 255 characters.');
        }

        $this->label = $label;

        return $this;
    }

    /**
     * Gets count
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return int
     */
    public function getCount(): int
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getCount() has no arguments but %d given.',
                    \func_num_args()
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

        if ($this->count < -2147483648|| $this->count > 2147483647) {
            throw new \DomainException(
                sprintf(
                    'Parameter count(%s) is too big for the integer domain [%d,%d]',
                    $this->count,
                    -2147483648,
                    2147483647
                )
            );
        }

        return (int) $this->count;
    }
}
