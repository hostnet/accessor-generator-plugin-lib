<?php
// Generated at 2014-11-06 12:57:33 by hboomsma on se18-03-73-3f-9f-e0

namespace Hostnet\Product\Entity\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

trait PeriodMethodsTrait
{
    /**
     * A very nice and long
     * multi line description...
     *
     * @returns integer
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

        if (!is_int($this->id)
            && (int)$this->id !== 1 * $this->id
        ) {
            throw new \DomainException(
                '\'id\' is too big for the interger domain ['
                . PHP_INT_MIN
                . ','
                . PHP_INT_MAX
                . ']'
            );
        }

        return $this->id;
    }

    /**
     * Get name
     *
     * @returns string
     * @throws \InvalidArgumentException
     */
    public function getName()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getName() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setName($name)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setName() has one argument but %d given.',
                    func_num_args()
                )
            );
        }


        return $this;
    }

    /**
     * Get description
     *
     * @returns string
     * @throws \InvalidArgumentException
     */
    public function getDescription()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getDescription() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return $this
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setDescription($description)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDescription() has one argument but %d given.',
                    func_num_args()
                )
            );
        }


        return $this;
    }

    /**
     * Get deprecated_evil_months
     *
     * @returns integer
     * @throws \InvalidArgumentException
     */
    public function getDeprecatedEvilMonths()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getDeprecatedEvilMonths() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if (!is_int($this->deprecated_evil_months)
            && (int)$this->deprecated_evil_months !== 1 * $this->deprecated_evil_months
        ) {
            throw new \DomainException(
                '\'deprecated_evil_months\' is too big for the interger domain ['
                . PHP_INT_MIN
                . ','
                . PHP_INT_MAX
                . ']'
            );
        }

        return $this->deprecated_evil_months;
    }

    /**
     * Set deprecated_evil_months
     *
     * @param integer $deprecated_evil_months
     * @return $this
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \DomainException if the intger value is outside of the domain on this machine
     * @throws \LengthException if the length of the value is to long
     */
    public function setDeprecatedEvilMonths($deprecated_evil_months)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDeprecatedEvilMonths() has one argument but %d given.',
                    func_num_args()
                )
            );
        }


        if (strlen($deprecated_evil_months > 4)) {
            throw new \LengthException('Parameter \'$deprecated_evil_months\' should not be longer than 4 characters.');
        }

        return $this;
    }

    /**
     * Is one_time
     *
     * @returns boolean
     * @throws \InvalidArgumentException
     */
    public function isOneTime()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getOneTime() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        return $this->one_time;
    }

    /**
     * Set one_time
     *
     * @param boolean $one_time
     * @return $this
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setOneTime($one_time)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setOneTime() has one argument but %d given.',
                    func_num_args()
                )
            );
        }


        return $this;
    }
}
