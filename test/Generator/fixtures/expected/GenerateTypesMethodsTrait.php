<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\GenerateTypes;

trait GenerateTypesMethodsTrait
{
    /**
     * Get integer
     *
     * @return integer
     * @throws \InvalidArgumentException
     */
    public function getInteger()
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
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
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
     * Set integer
     *
     * @param integer $integer
     * @return GenerateTypes
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \DomainException if the integer value is outside of the domain on this machine
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
     * Get float
     *
     * @return float
     * @throws \InvalidArgumentException
     */
    public function getFloat()
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
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->float;
    }

    /**
     * Set float
     *
     * @param float $float
     * @return GenerateTypes
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
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
     * Get string
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getString()
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
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->string;
    }

    /**
     * Set string
     *
     * @param string $string
     * @return GenerateTypes
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
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
                'Parameter string must be convertable to string.'
            );
        }

        $this->string = $string;
        return $this;
    }

    /**
     * Is boolean
     *
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function isBoolean()
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
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->boolean;
    }

    /**
     * Set boolean
     *
     * @param boolean $boolean
     * @return GenerateTypes
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
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
     * Is is_this_boolean
     *
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function isThisBoolean()
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
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->is_this_boolean;
    }

    /**
     * Set is_this_boolean
     *
     * @param boolean $is_this_boolean
     * @return GenerateTypes
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
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
     * Get datetime
     *
     * @return \DateTime
     * @throws \InvalidArgumentException
     */
    public function getDatetime()
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
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->datetime;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return GenerateTypes
     * @throws \BadMethodCallException if the number of arguments is not correct
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
     * Get array
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getArray()
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
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->array;
    }

    /**
     * Set array
     *
     * @param array $array
     * @return GenerateTypes
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
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
     * Get object
     *
     * @return object
     * @throws \InvalidArgumentException
     */
    public function getObject()
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
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->object;
    }

    /**
     * Set object
     *
     * @param object $object
     * @return GenerateTypes
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
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
