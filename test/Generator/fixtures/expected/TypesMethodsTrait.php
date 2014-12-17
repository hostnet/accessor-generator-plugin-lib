<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Types;

trait TypesMethodsTrait
{
    /**
     * Get id
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getId()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getId() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->id === null) {
            throw new \LogicException(sprintf(
                'Property id is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->id;
    }

    /**
     * Get smallint
     *
     * @return integer
     * @throws \InvalidArgumentException
     */
    public function getSmallint()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getSmallint() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->smallint === null) {
            throw new \LogicException(sprintf(
                'Property smallint is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        if ($this->smallint < -32768|| $this->smallint > 32767) {
            throw new \DomainException(
                sprintf(
                    'Parameter smallint(%s) is too big for the integer domain [%d,%d]',
                    $this->smallint,
                    -32768,
                    32767
                )
            );
        }

        return (int) $this->smallint;
    }

    /**
     * Set smallint
     *
     * @param integer $smallint
     * @return Types
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \DomainException if the intger value is outside of the domain on this machine
     */
    public function setSmallint($smallint)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setSmallint() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (!is_int($smallint)) {
            throw new \InvalidArgumentException(
                'Parameter smallint must be integer.'
            );
        }
        if ($smallint < -32768|| $smallint > 32767) {
            throw new \DomainException(
                sprintf(
                    'Parameter smallint(%s) is too big for the integer domain [%d,%d]',
                    $smallint,
                    -32768,
                    32767
                )
            );
        }

        $this->smallint = $smallint;
        return $this;
    }

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
     * @return Types
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \DomainException if the intger value is outside of the domain on this machine
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
     * Get bigint
     *
     * @return integer
     * @throws \InvalidArgumentException
     */
    public function getBigint()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getBigint() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->bigint === null) {
            throw new \LogicException(sprintf(
                'Property bigint is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        if (is_float($this->bigint)
            && ($this->bigint >= 9007199254740992|| $this->bigint <= -9007199254740992)
        ) {
            throw new \DomainException(
                sprintf(
                    'Parameter bigint(%17f) is a float that could have resulted from an integer ' .
                    'overflowing the integer domain [%d,%d]',
                    $this->bigint,
                    -9223372036854775808,
                    9223372036854775807
                )
            );
        }


        return (int) $this->bigint;
    }

    /**
     * Set bigint
     *
     * @param integer $bigint
     * @return Types
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \DomainException if the intger value is outside of the domain on this machine
     */
    public function setBigint($bigint)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setBigint() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (is_float($bigint)
            && ($bigint >= 9007199254740992|| $bigint <= -9007199254740992)
        ) {
            throw new \DomainException(
                sprintf(
                    'Parameter bigint(%17f) is a float that could have resulted from an integer ' .
                    'overflowing the integer domain [%d,%d]',
                    $bigint,
                    -9223372036854775808,
                    9223372036854775807
                )
            );
        }
        if (!is_int($bigint)) {
            throw new \InvalidArgumentException(
                'Parameter bigint must be integer.'
            );
        }


        $this->bigint = $bigint;
        return $this;
    }

    /**
     * Get decimal
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getDecimal()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getDecimal() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->decimal === null) {
            throw new \LogicException(sprintf(
                'Property decimal is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->decimal;
    }

    /**
     * Set decimal
     *
     * @param string $decimal
     * @param bool %round round the number fit in the precision and scale (round away from zero)
     * @return Types
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     */
    public function setDecimal($decimal, $round = false)
    {
        if (func_num_args() > 2) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDecimal() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (!is_int($decimal) && !is_string($decimal) && !is_float($decimal)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid type (%s) given. Only int, float and a numeric string are allowed.',
                    gettype($decimal)
                )
            );
        }

        $result           = [];
        $scientific_float = false;
        if (preg_match('/^([\-+]?[0-9]+\.?([0-9]*))[Ee]([\-+]?[0-9]+)$/', $decimal, $result)) {
            $scale = max(strlen($result[2]) - $result[3], 1);
            if (is_float($decimal)) {
                $scientific_float = true;
            }
            $decimal = bcmul($result[1], bcpow(10, $result[3], $scale), $scale);
        }

        if (!preg_match('/^(-?)([0-9]*)(?:\.([0-9]*))?$/', $decimal, $result)) {
            throw new \InvalidArgumentException(sprintf('String (%s) is not strictly numeric.', $decimal));
        }

        $minus  = isset($result[1]) ? $result[1] === '-': false;
        $before = isset($result[2]) ? ltrim($result[2], '0') : '';
        $after  = isset($result[3]) ? $result[3] : '';

        if (strlen($before) > 9) {
            throw new \DomainException(
                'More than 9 digit(s) ' .
                'before the decimal point given while only 9 is/are allowed'
            );
        }

        if ($round || is_float($decimal) || $scientific_float || strlen($after) <= 1) {
            if (substr($after, 1, 1) >= 5) {
                if ($minus) {
                    $decimal = bcsub($decimal, '0.1', 1);
                } else {
                    $decimal = bcadd($decimal, '0.1', 1);
                }
            } else {
                $decimal = bcadd($decimal, 0, 1);
            }
        } else {
            throw new \DomainException(
                'More than 1 digit(s) '.
                'after the decimal point given while only 1 is/are allowed'
            );
        }

        $this->decimal = $decimal;
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
     * @return Types
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
     * @return Types
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \LengthException if the length of the value is to long
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

        if (!is_string($string)) {
            throw new \InvalidArgumentException(
                'Parameter string must be string.'
            );
        }

        if (strlen($string) > 255) {
            throw new \LengthException('Parameter \'$string\' should not be longer than 255 characters.');
        }

        $this->string = $string;
        return $this;
    }

    /**
     * Get text
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getText()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getText() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->text === null) {
            throw new \LogicException(sprintf(
                'Property text is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->text;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Types
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     */
    public function setText($text)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setText() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (!is_string($text)) {
            throw new \InvalidArgumentException(
                'Parameter text must be string.'
            );
        }

        $this->text = $text;
        return $this;
    }

    /**
     * Get guid
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getGuid()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getGuid() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->guid === null) {
            throw new \LogicException(sprintf(
                'Property guid is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->guid;
    }

    /**
     * Set guid
     *
     * @param string $guid
     * @return Types
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     */
    public function setGuid($guid)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setGuid() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (!is_string($guid)) {
            throw new \InvalidArgumentException(
                'Parameter guid must be string.'
            );
        }

        $this->guid = $guid;
        return $this;
    }

    /**
     * Get blob
     *
     * @return resource
     * @throws \InvalidArgumentException
     */
    public function getBlob()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getBlob() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->blob === null) {
            throw new \LogicException(sprintf(
                'Property blob is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->blob;
    }

    /**
     * Set blob
     *
     * @param resource $blob
     * @return Types
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     */
    public function setBlob($blob)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setBlob() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (!is_resource($blob)) {
            throw new \InvalidArgumentException(
                'Parameter blob must be resource.'
            );
        }

        $this->blob = $blob;
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
                    'getBoolean() has no arguments but %d given.',
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
     * @return Types
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
     * Get date
     *
     * @return \DateTime
     * @throws \InvalidArgumentException
     */
    public function getDate()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getDate() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->date === null) {
            throw new \LogicException(sprintf(
                'Property date is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->date;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Types
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setDate(\DateTime $date)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDate() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        $this->date = $date;
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
     * @return Types
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
     * @return Types
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
     * Get json_array
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getJsonArray()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getJsonArray() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->json_array === null) {
            throw new \LogicException(sprintf(
                'Property json_array is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->json_array;
    }

    /**
     * Set json_array
     *
     * @param array $json_array
     * @return Types
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     */
    public function setJsonArray($json_array)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setJsonArray() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (!is_array($json_array)) {
            throw new \InvalidArgumentException(
                'Parameter json_array must be array.'
            );
        }

        $this->json_array = $json_array;
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
     * @return Types
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
