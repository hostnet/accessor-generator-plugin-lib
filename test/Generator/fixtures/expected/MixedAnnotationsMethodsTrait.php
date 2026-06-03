<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Attribute as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\MixedAnnotations;

trait MixedAnnotationsMethodsTrait
{
    /**
     * Gets name
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return string
     */
    public function getName(): string
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getName() has no arguments but %d given.',
                    \func_num_args()
                )
            );
        }
        if ($this->name === null) {
            throw new \LogicException(sprintf(
                'Property name is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->name;
    }

    /**
     * Sets name
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \LengthException if the length of the value is to long
     *
     * @param string $name
     *
     * @return $this|MixedAnnotations
     */
    public function setName($name)
    {
        if (\func_num_args() !== 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setName() has one argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if ($name === null
            || \is_scalar($name)
            || \is_callable([$name, '__toString'])
        ) {
            $name = (string)$name;
        } else {
            throw new \InvalidArgumentException(
                'Parameter name must be convertible to string.'
            );
        }

        if (\strlen($name) > 100) {
            throw new \LengthException('Parameter \'$name\' should not be longer than 100 characters.');
        }

        $this->name = $name;

        return $this;
    }

    /**
     * Gets count
     *
     * @throws \BadMethodCallException
     *
     * @return int|null
     */
    public function getCount(): ?int
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
            return null;
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

    /**
     * Gets created_at
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getCreatedAt() has no arguments but %d given.',
                    \func_num_args()
                )
            );
        }
        if ($this->created_at === null) {
            throw new \LogicException(sprintf(
                'Property created_at is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->created_at;
    }
}
