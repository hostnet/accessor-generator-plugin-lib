<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

/**
 * Generated accessor for enum class \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2.
 */
class ParamName2Enum
{
    private $owning_entity;
    private $collection;
    private $parameter_entity_class;

    /**
     * @param Collection $collection
     * @param Entity|*   $owning_entity
     * @param string     $parameter_entity_class
     */
    public function __construct(Collection $collection, $owning_entity, string $parameter_entity_class)
    {
        $this->collection             = $collection;
        $this->owning_entity          = $owning_entity;
        $this->parameter_entity_class = $parameter_entity_class;
    }

    /**
     * This is an array.
     *
     * @return array
     */
    public function getSomeArray(): array
    {
        if (! $this->hasSomeArray()) {
            throw new \LogicException(sprintf(
                'Parameter "%s" does not exist or has never been initialized. %s',
                'A_SOME_ARRAY',
                'Use the method hasSomeArray to make sure that this parameter exists has as a valid value.'
            ));
        }

        if (null === ($result = json_decode($this->getSomeArrayEntityInstance()->getValue(), true))) {
            throw new \RuntimeException(
                'The value of parameter "A_SOME_ARRAY" could not be converted to a native array type.'
            );
        }

        return (array) $result;
    }

    /**
     * Sets the value for the parameter SOME_ARRAY.
     *
     * @param  array $value
     * @return ParamName2Enum
     */
    public function setSomeArray(array $value): ParamName2Enum
    {
        $items = $this
            ->collection
            ->matching((new Criteria())
                ->where(Criteria::expr()->eq(
                    'name',
                    \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::A_SOME_ARRAY
                ))
            );

        if ($items->isEmpty()) {
            $item = new $this->parameter_entity_class(
                $this->owning_entity,
                \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::A_SOME_ARRAY,
                json_encode($value)
            );
            $this->collection->add($item);
        } else {
            $items->first()->setValue(json_encode($value));
        }

        return $this;
    }

    /**
     * Returns true if the value of parameter SOME_ARRAY exists and is not NULL.
     *
     * @return bool
     */
    public function hasSomeArray(): bool
    {
        $items = $this
            ->collection
            ->matching((new Criteria())
                ->where(Criteria::expr()->eq(
                    'name',
                    \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::A_SOME_ARRAY
                ))
            );

        return $items->isEmpty()
            ? false
            : $items->first()->getValue() !== null;
    }

    /**
     * Removes the parameter SOME_ARRAY from the collection.
     *
     * @throws \LogicException if the parameter does not exist.
     * @return ParamName2Enum
     */
    public function removeSomeArray(): ParamName2Enum
    {
        if (! $this->hasSomeArray()) {
            throw new \LogicException(sprintf(
                'Parameter "%s" does not exist or has never been initialized. %s',
                'A_SOME_ARRAY',
                'Use the method hasSomeArray to make sure that this parameter exists has as a valid value.'
            ));
        }

        $this->collection->removeElement($this->getSomeArrayEntityInstance());

        return $this;
    }

    /**
     * Nullifies the data for the parameter SOME_ARRAY.
     *
     * @throws \LogicException if the parameter does not exist or was never initialized.
     * @return ParamName2Enum
     */
    public function clearSomeArray(): ParamName2Enum
    {
        if (! $this->hasSomeArray()) {
            throw new \LogicException(sprintf(
                'Parameter "%s" does not exist or has never been initialized. %s',
                'A_SOME_ARRAY',
                'Use the method hasSomeArray to make sure that this parameter exists has as a valid value.'
            ));
        }

        $this->getSomeArrayEntityInstance()->setValue(null);

        return $this;
    }

    /**
     * This is a string.
     *
     * @return string
     */
    public function getSomeString(): string
    {
        if (! $this->hasSomeString()) {
            throw new \LogicException(sprintf(
                'Parameter "%s" does not exist or has never been initialized. %s',
                'S_SOME_STRING',
                'Use the method hasSomeString to make sure that this parameter exists has as a valid value.'
            ));
        }

        return (string) $this->getSomeStringEntityInstance()->getValue();
    }

    /**
     * Sets the value for the parameter SOME_STRING.
     *
     * @param  string $value
     * @return ParamName2Enum
     */
    public function setSomeString(string $value): ParamName2Enum
    {
        $items = $this
            ->collection
            ->matching((new Criteria())
                ->where(Criteria::expr()->eq(
                    'name',
                    \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::S_SOME_STRING
                ))
            );

        if ($items->isEmpty()) {
            $item = new $this->parameter_entity_class(
                $this->owning_entity,
                \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::S_SOME_STRING,
                $value
            );
            $this->collection->add($item);
        } else {
            $items->first()->setValue((string) $value);
        }

        return $this;
    }

    /**
     * Returns true if the value of parameter SOME_STRING exists and is not NULL.
     *
     * @return bool
     */
    public function hasSomeString(): bool
    {
        $items = $this
            ->collection
            ->matching((new Criteria())
                ->where(Criteria::expr()->eq(
                    'name',
                    \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::S_SOME_STRING
                ))
            );

        return $items->isEmpty()
            ? false
            : $items->first()->getValue() !== null;
    }

    /**
     * Removes the parameter SOME_STRING from the collection.
     *
     * @throws \LogicException if the parameter does not exist.
     * @return ParamName2Enum
     */
    public function removeSomeString(): ParamName2Enum
    {
        if (! $this->hasSomeString()) {
            throw new \LogicException(sprintf(
                'Parameter "%s" does not exist or has never been initialized. %s',
                'S_SOME_STRING',
                'Use the method hasSomeString to make sure that this parameter exists has as a valid value.'
            ));
        }

        $this->collection->removeElement($this->getSomeStringEntityInstance());

        return $this;
    }

    /**
     * Nullifies the data for the parameter SOME_STRING.
     *
     * @throws \LogicException if the parameter does not exist or was never initialized.
     * @return ParamName2Enum
     */
    public function clearSomeString(): ParamName2Enum
    {
        if (! $this->hasSomeString()) {
            throw new \LogicException(sprintf(
                'Parameter "%s" does not exist or has never been initialized. %s',
                'S_SOME_STRING',
                'Use the method hasSomeString to make sure that this parameter exists has as a valid value.'
            ));
        }

        $this->getSomeStringEntityInstance()->setValue(null);

        return $this;
    }

    /**
     * This is an integer.
     *
     * @return int
     */
    public function getSomeInteger(): int
    {
        if (! $this->hasSomeInteger()) {
            throw new \LogicException(sprintf(
                'Parameter "%s" does not exist or has never been initialized. %s',
                'I_SOME_INTEGER',
                'Use the method hasSomeInteger to make sure that this parameter exists has as a valid value.'
            ));
        }

        return (int) $this->getSomeIntegerEntityInstance()->getValue();
    }

    /**
     * Sets the value for the parameter SOME_INTEGER.
     *
     * @param  int $value
     * @return ParamName2Enum
     */
    public function setSomeInteger(int $value): ParamName2Enum
    {
        $items = $this
            ->collection
            ->matching((new Criteria())
                ->where(Criteria::expr()->eq(
                    'name',
                    \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::I_SOME_INTEGER
                ))
            );

        if ($items->isEmpty()) {
            $item = new $this->parameter_entity_class(
                $this->owning_entity,
                \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::I_SOME_INTEGER,
                $value
            );
            $this->collection->add($item);
        } else {
            $items->first()->setValue((string) $value);
        }

        return $this;
    }

    /**
     * Returns true if the value of parameter SOME_INTEGER exists and is not NULL.
     *
     * @return bool
     */
    public function hasSomeInteger(): bool
    {
        $items = $this
            ->collection
            ->matching((new Criteria())
                ->where(Criteria::expr()->eq(
                    'name',
                    \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::I_SOME_INTEGER
                ))
            );

        return $items->isEmpty()
            ? false
            : $items->first()->getValue() !== null;
    }

    /**
     * Removes the parameter SOME_INTEGER from the collection.
     *
     * @throws \LogicException if the parameter does not exist.
     * @return ParamName2Enum
     */
    public function removeSomeInteger(): ParamName2Enum
    {
        if (! $this->hasSomeInteger()) {
            throw new \LogicException(sprintf(
                'Parameter "%s" does not exist or has never been initialized. %s',
                'I_SOME_INTEGER',
                'Use the method hasSomeInteger to make sure that this parameter exists has as a valid value.'
            ));
        }

        $this->collection->removeElement($this->getSomeIntegerEntityInstance());

        return $this;
    }

    /**
     * Nullifies the data for the parameter SOME_INTEGER.
     *
     * @throws \LogicException if the parameter does not exist or was never initialized.
     * @return ParamName2Enum
     */
    public function clearSomeInteger(): ParamName2Enum
    {
        if (! $this->hasSomeInteger()) {
            throw new \LogicException(sprintf(
                'Parameter "%s" does not exist or has never been initialized. %s',
                'I_SOME_INTEGER',
                'Use the method hasSomeInteger to make sure that this parameter exists has as a valid value.'
            ));
        }

        $this->getSomeIntegerEntityInstance()->setValue(null);

        return $this;
    }

    /**
     * This is a float.
     *
     * @return float
     */
    public function getSomeFloat(): float
    {
        if (! $this->hasSomeFloat()) {
            throw new \LogicException(sprintf(
                'Parameter "%s" does not exist or has never been initialized. %s',
                'F_SOME_FLOAT',
                'Use the method hasSomeFloat to make sure that this parameter exists has as a valid value.'
            ));
        }

        return (float) $this->getSomeFloatEntityInstance()->getValue();
    }

    /**
     * Sets the value for the parameter SOME_FLOAT.
     *
     * @param  float $value
     * @return ParamName2Enum
     */
    public function setSomeFloat(float $value): ParamName2Enum
    {
        $items = $this
            ->collection
            ->matching((new Criteria())
                ->where(Criteria::expr()->eq(
                    'name',
                    \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::F_SOME_FLOAT
                ))
            );

        if ($items->isEmpty()) {
            $item = new $this->parameter_entity_class(
                $this->owning_entity,
                \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::F_SOME_FLOAT,
                $value
            );
            $this->collection->add($item);
        } else {
            $items->first()->setValue((string) $value);
        }

        return $this;
    }

    /**
     * Returns true if the value of parameter SOME_FLOAT exists and is not NULL.
     *
     * @return bool
     */
    public function hasSomeFloat(): bool
    {
        $items = $this
            ->collection
            ->matching((new Criteria())
                ->where(Criteria::expr()->eq(
                    'name',
                    \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::F_SOME_FLOAT
                ))
            );

        return $items->isEmpty()
            ? false
            : $items->first()->getValue() !== null;
    }

    /**
     * Removes the parameter SOME_FLOAT from the collection.
     *
     * @throws \LogicException if the parameter does not exist.
     * @return ParamName2Enum
     */
    public function removeSomeFloat(): ParamName2Enum
    {
        if (! $this->hasSomeFloat()) {
            throw new \LogicException(sprintf(
                'Parameter "%s" does not exist or has never been initialized. %s',
                'F_SOME_FLOAT',
                'Use the method hasSomeFloat to make sure that this parameter exists has as a valid value.'
            ));
        }

        $this->collection->removeElement($this->getSomeFloatEntityInstance());

        return $this;
    }

    /**
     * Nullifies the data for the parameter SOME_FLOAT.
     *
     * @throws \LogicException if the parameter does not exist or was never initialized.
     * @return ParamName2Enum
     */
    public function clearSomeFloat(): ParamName2Enum
    {
        if (! $this->hasSomeFloat()) {
            throw new \LogicException(sprintf(
                'Parameter "%s" does not exist or has never been initialized. %s',
                'F_SOME_FLOAT',
                'Use the method hasSomeFloat to make sure that this parameter exists has as a valid value.'
            ));
        }

        $this->getSomeFloatEntityInstance()->setValue(null);

        return $this;
    }

    /**
     * This is a boolean.
     *
     * @return bool
     */
    public function getSomeBoolean(): bool
    {
        if (! $this->hasSomeBoolean()) {
            throw new \LogicException(sprintf(
                'Parameter "%s" does not exist or has never been initialized. %s',
                'B_SOME_BOOLEAN',
                'Use the method hasSomeBoolean to make sure that this parameter exists has as a valid value.'
            ));
        }

        return (bool) $this->getSomeBooleanEntityInstance()->getValue();
    }

    /**
     * Sets the value for the parameter SOME_BOOLEAN.
     *
     * @param  bool $value
     * @return ParamName2Enum
     */
    public function setSomeBoolean(bool $value): ParamName2Enum
    {
        $items = $this
            ->collection
            ->matching((new Criteria())
                ->where(Criteria::expr()->eq(
                    'name',
                    \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::B_SOME_BOOLEAN
                ))
            );

        if ($items->isEmpty()) {
            $item = new $this->parameter_entity_class(
                $this->owning_entity,
                \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::B_SOME_BOOLEAN,
                $value
            );
            $this->collection->add($item);
        } else {
            $items->first()->setValue((string) $value);
        }

        return $this;
    }

    /**
     * Returns true if the value of parameter SOME_BOOLEAN exists and is not NULL.
     *
     * @return bool
     */
    public function hasSomeBoolean(): bool
    {
        $items = $this
            ->collection
            ->matching((new Criteria())
                ->where(Criteria::expr()->eq(
                    'name',
                    \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::B_SOME_BOOLEAN
                ))
            );

        return $items->isEmpty()
            ? false
            : $items->first()->getValue() !== null;
    }

    /**
     * Removes the parameter SOME_BOOLEAN from the collection.
     *
     * @throws \LogicException if the parameter does not exist.
     * @return ParamName2Enum
     */
    public function removeSomeBoolean(): ParamName2Enum
    {
        if (! $this->hasSomeBoolean()) {
            throw new \LogicException(sprintf(
                'Parameter "%s" does not exist or has never been initialized. %s',
                'B_SOME_BOOLEAN',
                'Use the method hasSomeBoolean to make sure that this parameter exists has as a valid value.'
            ));
        }

        $this->collection->removeElement($this->getSomeBooleanEntityInstance());

        return $this;
    }

    /**
     * Nullifies the data for the parameter SOME_BOOLEAN.
     *
     * @throws \LogicException if the parameter does not exist or was never initialized.
     * @return ParamName2Enum
     */
    public function clearSomeBoolean(): ParamName2Enum
    {
        if (! $this->hasSomeBoolean()) {
            throw new \LogicException(sprintf(
                'Parameter "%s" does not exist or has never been initialized. %s',
                'B_SOME_BOOLEAN',
                'Use the method hasSomeBoolean to make sure that this parameter exists has as a valid value.'
            ));
        }

        $this->getSomeBooleanEntityInstance()->setValue(null);

        return $this;
    }

    /**
     * Returns the parameter element for easy access.
     *
     * @return object
     */
    private function getSomeArrayEntityInstance()
    {
        $items = $this
            ->collection
            ->matching((new Criteria())
                ->where(Criteria::expr()->eq(
                    'name',
                    \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::A_SOME_ARRAY
                ))
            );

        return $items->first();
    }

    /**
     * Returns the parameter element for easy access.
     *
     * @return object
     */
    private function getSomeStringEntityInstance()
    {
        $items = $this
            ->collection
            ->matching((new Criteria())
                ->where(Criteria::expr()->eq(
                    'name',
                    \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::S_SOME_STRING
                ))
            );

        return $items->first();
    }

    /**
     * Returns the parameter element for easy access.
     *
     * @return object
     */
    private function getSomeIntegerEntityInstance()
    {
        $items = $this
            ->collection
            ->matching((new Criteria())
                ->where(Criteria::expr()->eq(
                    'name',
                    \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::I_SOME_INTEGER
                ))
            );

        return $items->first();
    }

    /**
     * Returns the parameter element for easy access.
     *
     * @return object
     */
    private function getSomeFloatEntityInstance()
    {
        $items = $this
            ->collection
            ->matching((new Criteria())
                ->where(Criteria::expr()->eq(
                    'name',
                    \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::F_SOME_FLOAT
                ))
            );

        return $items->first();
    }

    /**
     * Returns the parameter element for easy access.
     *
     * @return object
     */
    private function getSomeBooleanEntityInstance()
    {
        $items = $this
            ->collection
            ->matching((new Criteria())
                ->where(Criteria::expr()->eq(
                    'name',
                    \Hostnet\Component\AccessorGenerator\Generator\fixtures\ParamName2::B_SOME_BOOLEAN
                ))
            );

        return $items->first();
    }
}
