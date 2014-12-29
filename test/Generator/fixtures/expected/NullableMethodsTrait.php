<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Feature;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Nullable;

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
     * Still require an explicit argument to set the column. If you want to get
     * rid of this message, please specify a default value or specify
     * @JoinColumn(nullable=false).
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
     * Set int_different
     *
     * @param integer $int_different
     * @return Nullable
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \DomainException if the intger value is outside of the domain on this machine
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
     * Still require an explicit argument to set the column. If you want to get
     * rid of this message, please specify a default value or specify
     * @JoinColumn(nullable=false).
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
     * Still require an explicit argument to set the column. If you want to get
     * rid of this message, please specify a default value or specify
     * @JoinColumn(nullable=false).
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
}
