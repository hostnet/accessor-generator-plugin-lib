<?php
// Generated at 2014-11-06 12:57:33 by hboomsma on se18-03-73-3f-9f-e0

namespace Hostnet\Product\Entity\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

trait ProductMethodsTrait
{
    /**
     * Product Id not good etc
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
     * Get duration
     *
     * @returns \Hostnet\Product\Entity\Period
     * @throws \InvalidArgumentException
     */
    public function getDuration()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getDuration() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        return $this->duration;
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
     * Used in invoices and email
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
     * Get system_name
     *
     * @returns string
     * @throws \InvalidArgumentException
     */
    public function getSystemName()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getSystemName() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        return $this->system_name;
    }

    /**
     * Set system_name
     *
     * @param string $system_name
     * @return $this
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \LengthException if the length of the value is to long
     */
    public function setSystemName($system_name)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setSystemName() has one argument but %d given.',
                    func_num_args()
                )
            );
        }


        if (strlen($system_name > 50)) {
            throw new \LengthException('Parameter \'$system_name\' should not be longer than 50 characters.');
        }

        return $this;
    }

    /**
     * Get attributes
     *
     * @returns \Doctrine\Common\Collections\Collection | \Hostnet\Product\Entity\Attribute[]
     * @throws \InvalidArgumentException
     */
    public function getAttributes()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getAttributes() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        return $this->attributes;
    }

    /**
     * Add attribute
     *
     * @param \Hostnet\Product\Entity\Attribute $attributes
     * @return $this
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function addAttribute(\Hostnet\Product\Entity\Attribute $attribute)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addAttributes() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        $this->attributes->add($attribute);
        return $this;
    }

    /**
     * Add attribute
     *
     * @param \Hostnet\Product\Entity\Attribute $attributes
     * @return $this
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function removeAttribute(\Hostnet\Product\Entity\Attribute $attribute)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'removeAttributes() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        $this->attributes->removeElement($attribute);
        return $this;
    }
}
