<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Types;

trait TypesMethodsTrait
{
    /**
     * Gets id
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return string
     */
    public function getId(): string
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
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->id;
    }

    /**
     * Gets smallint
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return int
     */
    public function getSmallint(): int
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
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
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
     * Sets smallint
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \DomainException if the integer value is outside of the domain on this machine
     *
     * @param  int $smallint
     * @return $this|Types
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
     * @return $this|Types
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
     * Gets bigint
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return int
     */
    public function getBigint(): int
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
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
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
     * Sets bigint
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \DomainException if the integer value is outside of the domain on this machine
     *
     * @param  int $bigint
     * @return $this|Types
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
     * Gets decimal
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return string
     */
    public function getDecimal(): string
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
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->decimal;
    }

    /**
     * Sets decimal
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param  string $decimal
     * @param  bool $round round the number fit in the precision and scale (round away from zero)
     * @return $this|Types
     */
    public function setDecimal($decimal, $round = false)
    {
        if (func_num_args() > 2) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDecimal() has one mandatory and one optional argument but %d given.',
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
     * @return $this|Types
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
     * @throws \LengthException if the length of the value is to long
     *
     * @param  string $string
     * @return $this|Types
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

        if (strlen($string) > 255) {
            throw new \LengthException('Parameter \'$string\' should not be longer than 255 characters.');
        }

        $this->string = $string;

        return $this;
    }

    /**
     * Gets text
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return string
     */
    public function getText(): string
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
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->text;
    }

    /**
     * Sets text
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param  string $text
     * @return $this|Types
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

        if ($text === null
            || is_scalar($text)
            || is_callable([$text, '__toString'])
        ) {
            $text = (string)$text;
        } else {
            throw new \InvalidArgumentException(
                'Parameter text must be convertible to string.'
            );
        }

        $this->text = $text;

        return $this;
    }

    /**
     * Gets guid
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return string
     */
    public function getGuid(): string
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
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->guid;
    }

    /**
     * Sets guid
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param  string $guid
     * @return $this|Types
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

        if ($guid === null
            || is_scalar($guid)
            || is_callable([$guid, '__toString'])
        ) {
            $guid = (string)$guid;
        } else {
            throw new \InvalidArgumentException(
                'Parameter guid must be convertible to string.'
            );
        }

        $this->guid = $guid;

        return $this;
    }

    /**
     * Gets blob
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return resource
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
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->blob;
    }

    /**
     * Sets blob
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param  resource $blob
     * @return $this|Types
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
     * @return $this|Types
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
     * @return $this|Types
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
     * Gets date
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return \DateTime
     */
    public function getDate(): \DateTime
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
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->date;
    }

    /**
     * Sets date
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     *
     * @param  \DateTime $date
     * @return $this|Types
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
     * @return $this|Types
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
     * @return $this|Types
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
     * Gets json_array
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return array
     */
    public function getJsonArray(): array
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
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->json_array;
    }

    /**
     * Sets json_array
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param  array $json_array
     * @return $this|Types
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
     * @return $this|Types
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
