<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Collection\ImmutableCollection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Attribute;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Product;

trait ProductMethodsTrait
{
    /**
     * Product Id not good etc
     *
     * @throws \BadMethodCallException
     *
     * @return integer|null
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
            return null;
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
     * Gets duration
     *
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return \Hostnet\Component\AccessorGenerator\Generator\fixtures\Period
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
            throw new \Doctrine\ORM\EntityNotFoundException('Missing required property "duration".');
        }

        return $this->duration;
    }

    /**
     * Gets name
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return string
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
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->name;
    }

    /**
     * Used in invoices and email
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return string
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
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->description;
    }

    /**
     * Gets system_name
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return string
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
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->system_name;
    }

    /**
     * Sets system_name
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \LengthException if the length of the value is to long
     *
     * @param  string $system_name
     * @return $this|Product
     */
    public function setSystemName($system_name = '')
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setSystemName() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($system_name === null
            || is_scalar($system_name)
            || is_callable([$system_name, '__toString'])
        ) {
            $system_name = (string)$system_name;
        } else {
            throw new \InvalidArgumentException(
                'Parameter system_name must be convertible to string.'
            );
        }

        if (strlen($system_name) > 50) {
            throw new \LengthException('Parameter \'$system_name\' should not be longer than 50 characters.');
        }

        $this->system_name = $system_name;

        return $this;
    }

    /**
     * Gets attributes
     *
     * @throws \BadMethodCallException
     *
     * @return \Hostnet\Component\AccessorGenerator\Generator\fixtures\Attribute[]|ImmutableCollection
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

        return new ImmutableCollection($this->attributes);
    }

    /**
     * Adds the given attribute to this collection.
     *
     * @throws \BadMethodCallException if the number of arguments is not correct.
     * @throws \LogicException         if a member was added that already exists within the collection.
     * @throws \Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException
     *
     * @param  Attribute $attribute
     * @return $this|Product
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

        /* @var $this->attributes \Doctrine\Common\Collections\ArrayCollection */
        if ($this->attributes === null) {
            $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->attributes->contains($attribute)) {
            return $this;
        }

        $reflection_index = new \ReflectionProperty(Attribute::class, 'name');
        $reflection_index->setAccessible(true);
        $index = $reflection_index->getValue($attribute);
        $reflection_index->setAccessible(false);
        if ($this->attributes->containsKey($index)) {
            throw new \LogicException(sprintf('index name with value "%s" is already taken, meaning the index is not unique!', $index));
        }
        $this->attributes->set($index, $attribute);
        try {
            $property = new \ReflectionProperty($attribute, 'product');
        } catch (\ReflectionException $e) {
            throw new \Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
        $property->setAccessible(true);
        $value = $property->getValue($attribute);
        if ($value && $value !== $this) {
            throw new \LogicException('Attribute can not be added to more than one Product.');
        }
        $property->setValue($attribute, $this);
        $property->setAccessible(false);

        return $this;
    }

    /**
     * Removes the given attribute from this collection.
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     *
     * @param  Attribute $attribute
     * @return $this|Product
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

        $property = new \ReflectionProperty(Attribute::class, 'product');
        $property->setAccessible(true);
        $property->setValue($attribute, null);
        $property->setAccessible(false);

        return $this;
    }
}
