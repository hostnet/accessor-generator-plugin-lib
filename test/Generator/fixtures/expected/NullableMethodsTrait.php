<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Feature;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Item;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Nullable;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\OneToOneNullable;

trait NullableMethodsTrait
{
    /**
     * Set datetime_default
     *
     * @param \DateTime $datetime_default
     * @return Nullable
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setDatetimeDefault(\DateTime $datetime_default = null)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDatetimeDefault() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }

        $this->datetime_default = $datetime_default;
        return $this;
    }

    /**
     * Set datetime_nullable
     *
     * Generated a default null value because the doctrine column is nullable.
     * Still require an explicit argument to set the column. If you do not like
     * this message, specify a default value or use JoinColumn(nullable=false).
     *
     * @param \DateTime $datetime_nullable
     * @return Nullable
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setDatetimeNullable(\DateTime $datetime_nullable = null)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDatetimeNullable() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }

        $this->datetime_nullable = $datetime_nullable;
        return $this;
    }

    /**
     * Set datetime_both
     *
     * @param \DateTime $datetime_both
     * @return Nullable
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setDatetimeBoth(\DateTime $datetime_both = null)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDatetimeBoth() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }

        $this->datetime_both = $datetime_both;
        return $this;
    }

    /**
     * Get int
     *
     * @return integer
     * @throws \InvalidArgumentException
     */
    public function getInt()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getInt() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->int === null) {
            return null;
        }

        if ($this->int < -2147483648|| $this->int > 2147483647) {
            throw new \DomainException(
                sprintf(
                    'Parameter int(%s) is too big for the integer domain [%d,%d]',
                    $this->int,
                    -2147483648,
                    2147483647
                )
            );
        }

        return (int) $this->int;
    }

    /**
     * Set int
     *
     * @param integer $int
     * @return Nullable
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \DomainException if the integer value is outside of the domain on this machine
     */
    public function setInt($int = null)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setInt() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($int === null) {
            $this->int = $int;
            return $this;
        }

        if (!is_int($int)) {
            throw new \InvalidArgumentException(
                'Parameter int must be integer.'
            );
        }
        if ($int < -2147483648|| $int > 2147483647) {
            throw new \DomainException(
                sprintf(
                    'Parameter int(%s) is too big for the integer domain [%d,%d]',
                    $int,
                    -2147483648,
                    2147483647
                )
            );
        }

        $this->int = $int;
        return $this;
    }

    /**
     * Set int_different
     *
     * @param integer $int_different
     * @return Nullable
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \DomainException if the integer value is outside of the domain on this machine
     */
    public function setIntDifferent($int_different = 2)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setIntDifferent() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($int_different === null) {
            $this->int_different = $int_different;
            return $this;
        }

        if (!is_int($int_different)) {
            throw new \InvalidArgumentException(
                'Parameter int_different must be integer.'
            );
        }
        if ($int_different < -2147483648|| $int_different > 2147483647) {
            throw new \DomainException(
                sprintf(
                    'Parameter int_different(%s) is too big for the integer domain [%d,%d]',
                    $int_different,
                    -2147483648,
                    2147483647
                )
            );
        }

        $this->int_different = $int_different;
        return $this;
    }

    /**
     * Set feature
     *
     * Generated a default null value because the doctrine column is nullable.
     * Still require an explicit argument to set the column. If you do not like
     * this message, specify a default value or use JoinColumn(nullable=false).
     *
     * @param Feature $feature
     * @return Nullable
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setFeature(Feature $feature = null)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setFeature() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }

        $this->feature = $feature;
        return $this;
    }

    /**
     * Set an_other_feature
     *
     * Generated a default null value because the doctrine column is nullable.
     * Still require an explicit argument to set the column. If you do not like
     * this message, specify a default value or use JoinColumn(nullable=false).
     *
     * @param Feature $an_other_feature
     * @return Nullable
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setAnOtherFeature(Feature $an_other_feature = null)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setAnOtherFeature() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }

        $this->an_other_feature = $an_other_feature;
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
            return null;
        }

        return $this->string;
    }

    /**
     * Set string
     *
     * @param string $string
     * @return Nullable
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     */
    public function setString($string = null)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setString() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($string === null) {
            $this->string = $string;
            return $this;
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
     * Get only_one
     *
     * @return OneToOneNullable
     * @throws \InvalidArgumentException
     */
    public function getOnlyOne()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getOnlyOne() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        return $this->only_one;
    }

    /**
     * Set only_one
     *
     * @param OneToOneNullable $only_one
     * @return Nullable
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setOnlyOne(OneToOneNullable $only_one = null)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setOnlyOne() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }
        // Create reflection property.
        $property = new \ReflectionProperty(OneToOneNullable::class, 'one_only');
        $property->setAccessible(true);

        // Unset old value and set the new value
        // keeping the inverse side up-to-date.
        $this->only_one && $property->setValue($this->only_one, null);
        $only_one && $property->setValue($only_one, $this);

        // Disallow acces again.
        $property->setAccessible(false);

        $this->only_one = $only_one;
        return $this;
    }

    /**
     * Get unidirectional_one_to_one
     *
     * @return Item
     * @throws \InvalidArgumentException
     */
    public function getUnidirectionalOneToOne()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getUnidirectionalOneToOne() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        return $this->unidirectional_one_to_one;
    }

    /**
     * Set unidirectional_one_to_one
     *
     * @param Item $unidirectional_one_to_one
     * @return Nullable
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setUnidirectionalOneToOne(Item $unidirectional_one_to_one = null)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setUnidirectionalOneToOne() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }

        $this->unidirectional_one_to_one = $unidirectional_one_to_one;
        return $this;
    }
}
