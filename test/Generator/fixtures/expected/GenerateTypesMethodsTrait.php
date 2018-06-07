<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\GenerateTypes;

trait GenerateTypesMethodsTrait
{
    /**
     * Gets integer
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return int
     */
    public function getInteger(): int
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getInteger() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->integer === null) {
            throw new \LogicException(sprintf(
                'Property integer is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        if ($this->integer < -2147483648|| $this->integer > 2147483647) {
            throw new \DomainException(
                sprintf(
                    'Parameter integer(%s) is too big for the integer domain [%d,%d]',
                    $this->integer,
                    -2147483648,
                    2147483647
                )
            );
        }

        return (int) $this->integer;
    }

    /**
     * Sets integer
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \DomainException if the integer value is outside of the domain on this machine
     *
     * @param  int $integer
     * @return $this|GenerateTypes
     */
    public function setInteger($integer)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setInteger() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (!is_int($integer)) {
            throw new \InvalidArgumentException(
                'Parameter integer must be integer.'
            );
        }
        if ($integer < -2147483648|| $integer > 2147483647) {
            throw new \DomainException(
                sprintf(
                    'Parameter integer(%s) is too big for the integer domain [%d,%d]',
                    $integer,
                    -2147483648,
                    2147483647
                )
            );
        }

        $this->integer = $integer;

        return $this;
    }

    /**
     * Gets float
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return float
     */
    public function getFloat(): float
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getFloat() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->float === null) {
            throw new \LogicException(sprintf(
                'Property float is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->float;
    }

    /**
     * Sets float
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param  float $float
     * @return $this|GenerateTypes
     */
    public function setFloat($float)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setFloat() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (!is_float($float)) {
            throw new \InvalidArgumentException(
                'Parameter float must be float.'
            );
        }

        $this->float = $float;

        return $this;
    }

    /**
     * Gets string
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return string
     */
    public function getString(): string
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getString() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->string === null) {
            throw new \LogicException(sprintf(
                'Property string is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->string;
    }

    /**
     * Sets string
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param  string $string
     * @return $this|GenerateTypes
     */
    public function setString($string)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setString() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($string === null
            || is_scalar($string)
            || is_callable([$string, '__toString'])
        ) {
            $string = (string)$string;
        } else {
            throw new \InvalidArgumentException(
                'Parameter string must be convertible to string.'
            );
        }

        $this->string = $string;

        return $this;
    }

    /**
     * Returns true if boolean
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return bool
     */
    public function isBoolean(): bool
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'isBoolean() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->boolean === null) {
            throw new \LogicException(sprintf(
                'Property boolean is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->boolean;
    }

    /**
     * Sets boolean
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param  bool $boolean
     * @return $this|GenerateTypes
     */
    public function setBoolean($boolean)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setBoolean() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (!is_bool($boolean)) {
            throw new \InvalidArgumentException(
                'Parameter boolean must be boolean.'
            );
        }

        $this->boolean = $boolean;

        return $this;
    }

    /**
     * Returns true if is_this_boolean
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return bool
     */
    public function isThisBoolean(): bool
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'isThisBoolean() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->is_this_boolean === null) {
            throw new \LogicException(sprintf(
                'Property is_this_boolean is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->is_this_boolean;
    }

    /**
     * Sets is_this_boolean
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param  bool $is_this_boolean
     * @return $this|GenerateTypes
     */
    public function setIsThisBoolean($is_this_boolean)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setIsThisBoolean() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (!is_bool($is_this_boolean)) {
            throw new \InvalidArgumentException(
                'Parameter is_this_boolean must be boolean.'
            );
        }

        $this->is_this_boolean = $is_this_boolean;

        return $this;
    }

    /**
     * Gets datetime
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return \DateTime
     */
    public function getDatetime(): \DateTime
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getDatetime() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->datetime === null) {
            throw new \LogicException(sprintf(
                'Property datetime is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->datetime;
    }

    /**
     * Sets datetime
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     *
     * @param  \DateTime $datetime
     * @return $this|GenerateTypes
     */
    public function setDatetime(\DateTime $datetime)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDatetime() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Gets array
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return array
     */
    public function getArray(): array
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getArray() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->array === null) {
            throw new \LogicException(sprintf(
                'Property array is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->array;
    }

    /**
     * Sets array
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param  array $array
     * @return $this|GenerateTypes
     */
    public function setArray($array)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setArray() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (!is_array($array)) {
            throw new \InvalidArgumentException(
                'Parameter array must be array.'
            );
        }

        $this->array = $array;

        return $this;
    }

    /**
     * Gets object
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return object
     */
    public function getObject(): object
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getObject() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->object === null) {
            throw new \LogicException(sprintf(
                'Property object is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->object;
    }

    /**
     * Sets object
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param  object $object
     * @return $this|GenerateTypes
     */
    public function setObject($object)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setObject() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (!is_object($object)) {
            throw new \InvalidArgumentException(
                'Parameter object must be object.'
            );
        }

        $this->object = $object;

        return $this;
    }
}
