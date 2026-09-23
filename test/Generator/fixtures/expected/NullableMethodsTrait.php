<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Attribute as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Feature;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Item;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Nullable;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\OneToOneNullable;

trait NullableMethodsTrait
{
    /**
     * Gets zeroed_datetime
     *
     * @throws \BadMethodCallException
     *
     * @return \DateTime|null
     */
    public function getZeroedDatetime(): ?\DateTime
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getZeroedDatetime() has no arguments but %d given.',
                    \func_num_args()
                )
            );
        }

        if ($this->zeroed_datetime === null) {
            return null;
        }

        return $this->zeroed_datetime;
    }

    /**
     * Sets zeroed_datetime
     *
     * Generated a default null value because the doctrine column is nullable.
     * Still require an explicit argument to set the column. If you do not like
     * this message, specify a default value or use JoinColumn(nullable=false).
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setZeroedDatetime(?\DateTime $zeroed_datetime = null): static
    {
        if (\func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setZeroedDatetime() has one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        $this->zeroed_datetime = $zeroed_datetime;

        return $this;
    }

    /**
     * Gets zeroed_date
     *
     * @throws \BadMethodCallException
     *
     * @return \DateTime|null
     */
    public function getZeroedDate(): ?\DateTime
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getZeroedDate() has no arguments but %d given.',
                    \func_num_args()
                )
            );
        }

        if ($this->zeroed_date === null) {
            return null;
        }

        return $this->zeroed_date;
    }

    /**
     * Sets zeroed_date
     *
     * Generated a default null value because the doctrine column is nullable.
     * Still require an explicit argument to set the column. If you do not like
     * this message, specify a default value or use JoinColumn(nullable=false).
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setZeroedDate(?\DateTime $zeroed_date = null): static
    {
        if (\func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setZeroedDate() has one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        $this->zeroed_date = $zeroed_date;

        return $this;
    }

    /**
     * Sets datetime_default
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setDatetimeDefault(?\DateTime $datetime_default = null): static
    {
        if (\func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDatetimeDefault() has one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        $this->datetime_default = $datetime_default;

        return $this;
    }

    /**
     * Sets datetime_nullable
     *
     * Generated a default null value because the doctrine column is nullable.
     * Still require an explicit argument to set the column. If you do not like
     * this message, specify a default value or use JoinColumn(nullable=false).
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setDatetimeNullable(?\DateTime $datetime_nullable = null): static
    {
        if (\func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDatetimeNullable() has one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        $this->datetime_nullable = $datetime_nullable;

        return $this;
    }

    /**
     * Sets datetime_both
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setDatetimeBoth(?\DateTime $datetime_both = null): static
    {
        if (\func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDatetimeBoth() has one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        $this->datetime_both = $datetime_both;

        return $this;
    }

    /**
     * Gets int
     *
     * @throws \BadMethodCallException
     *
     * @return int|null
     */
    public function getInt(): ?int
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getInt() has no arguments but %d given.',
                    \func_num_args()
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
     * Sets int
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \DomainException if the integer value is outside of the domain on this machine
     *
     * @param int $int
     */
    public function setInt($int = null): static
    {
        if (\func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setInt() has one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if ($int === null) {
            $this->int = $int;
            return $this;
        }

        if (!\is_int($int)) {
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
     * Sets int_different
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \DomainException if the integer value is outside of the domain on this machine
     *
     * @param int $int_different
     */
    public function setIntDifferent($int_different = 2): static
    {
        if (\func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setIntDifferent() has one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if ($int_different === null) {
            $this->int_different = $int_different;
            return $this;
        }

        if (!\is_int($int_different)) {
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
     * Sets feature
     *
     * Generated a default null value because the doctrine column is nullable.
     * Still require an explicit argument to set the column. If you do not like
     * this message, specify a default value or use JoinColumn(nullable=false).
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setFeature(?Feature $feature = null): static
    {
        if (\func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setFeature() has one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        $this->feature = $feature;

        return $this;
    }

    /**
     * Sets an_other_feature
     *
     * Generated a default null value because the doctrine column is nullable.
     * Still require an explicit argument to set the column. If you do not like
     * this message, specify a default value or use JoinColumn(nullable=false).
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setAnOtherFeature(?Feature $an_other_feature = null): static
    {
        if (\func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setAnOtherFeature() has one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        $this->an_other_feature = $an_other_feature;

        return $this;
    }

    /**
     * Gets string
     *
     * @throws \BadMethodCallException
     *
     * @return string|null
     */
    public function getString(): ?string
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getString() has no arguments but %d given.',
                    \func_num_args()
                )
            );
        }

        if ($this->string === null) {
            return null;
        }

        return $this->string;
    }

    /**
     * Sets string
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param string $string
     */
    public function setString($string = null): static
    {
        if (\func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setString() has one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if ($string === null) {
            $this->string = $string;
            return $this;
        }

        if ($string === null
            || \is_scalar($string)
            || \is_callable([$string, '__toString'])
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
     * Gets only_one
     *
     * @throws \BadMethodCallException
     *
     * @return OneToOneNullable|null
     */
    public function getOnlyOne(): ?OneToOneNullable
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getOnlyOne() has no arguments but %d given.',
                    \func_num_args()
                )
            );
        }

        return $this->only_one;
    }

    /**
     * Sets only_one
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setOnlyOne(?OneToOneNullable $only_one = null): static
    {
        if (\func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setOnlyOne() has one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }
        // Create reflection property.
        $property = new \ReflectionProperty(OneToOneNullable::class, 'one_only');
        $property->setAccessible(true);

        // Unset old value and set the new value to keep the inverse side in sync.
        $this->only_one && $property->setValue($this->only_one, null);
        $only_one && $property->setValue($only_one, $this);

        // Update the accessible flag to disallow further again.
        $property->setAccessible(false);

        $this->only_one = $only_one;

        return $this;
    }

    /**
     * Gets unidirectional_one_to_one
     *
     * @throws \BadMethodCallException
     *
     * @return Item|null
     */
    public function getUnidirectionalOneToOne(): ?Item
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getUnidirectionalOneToOne() has no arguments but %d given.',
                    \func_num_args()
                )
            );
        }

        return $this->unidirectional_one_to_one;
    }

    /**
     * Sets unidirectional_one_to_one
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setUnidirectionalOneToOne(?Item $unidirectional_one_to_one = null): static
    {
        if (\func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setUnidirectionalOneToOne() has one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        $this->unidirectional_one_to_one = $unidirectional_one_to_one;

        return $this;
    }
}
