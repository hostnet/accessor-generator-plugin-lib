<?php
// Generated at 2014-12-10 17:27:16 by hboomsma on se18-03-73-3f-9f-e0

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Attribute;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Product;

trait ProductMethodsTrait
{
    /**
     * Product Id not good etc
     *
     * @return integer
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

        if ($this->id < -2147483648|| $this->id > 2147483647) {
            throw new \DomainException(
                sprintf(
                    'Parameter id(%s) is too big for the integer domain [%d,%d]',
                    $this->id,
                    -2147483648,
                    2147483647
                )
            );
        }

        return (int) $this->id;
    }

    /**
     * Get duration
     *
     * @return Hostnet\Component\AccessorGenerator\Generator\fixtures\Period
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

        if ($this->duration === null) {
            throw new \Doctrine\ORM\EntityNotFoundException(
                'Property Hostnet\Component\AccessorGenerator\Generator\fixtures\Period references an other entity ' .
                'but is not found and also is not nullable for parameter duration.'
            );
        }

        return $this->duration;
    }

    /**
     * Get name
     *
     * @return string
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
        if ($this->name === null) {
            throw new \LogicException(sprintf(
                'Property name is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->name;
    }

    /**
     * Used in invoices and email
     *
     * @return string
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
        if ($this->description === null) {
            throw new \LogicException(sprintf(
                'Property description is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->description;
    }

    /**
     * Get system_name
     *
     * @return string
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
        if ($this->system_name === null) {
            throw new \LogicException(sprintf(
                'Property system_name is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->system_name;
    }

    /**
     * Set system_name
     *
     * @param string $system_name
     * @return Product
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \LengthException if the length of the value is to long
     */
    public function setSystemName($system_name = '')
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setSystemName() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (!is_string($system_name)) {
            throw new \InvalidArgumentException(
                'Parameter system_name must be string.'
            );
        }

        if (strlen($system_name) > 50) {
            throw new \LengthException('Parameter \'$system_name\' should not be longer than 50 characters.');
        }

        $this->system_name = $system_name;
        return $this;
    }

    /**
     * Get attributes
     *
     * @return \Doctrine\Common\Collections\Collection | Attribute[]
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

        if ($this->attributes === null) {
            $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
        }

        return $this->attributes;
    }

    /**
     * Add attribute
     *
     * @param Attribute $attributes
     * @return Product
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function addAttribute(Attribute $attribute)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addAttributes() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->attributes === null) {
            $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->attributes->contains($attribute)) {
            return $this;
        }

        $this->attributes->add($attribute);
        $method = new \ReflectionMethod($attribute, 'setProduct');
        $method->setAccessible(true);
        $method->invoke($attribute, $this);
        $method->setAccessible(false);
        return $this;
    }

    /**
     * Remove attribute
     *
     * @param Attribute $attributes
     * @return Product
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function removeAttribute(Attribute $attribute)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'removeAttributes() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (! $this->attributes instanceof \Doctrine\Common\Collections\Collection
            || ! $this->attributes->contains($attribute)
        ) {
            return $this;
        }

        $this->attributes->removeElement($attribute);

        $method = new \ReflectionMethod($attribute, 'setProduct');
        $method->setAccessible(true);
        $method->invoke($attribute, null);
        $method->setAccessible(false);
        return $this;
    }
}
