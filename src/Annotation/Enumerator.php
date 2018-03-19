<?php
/**
 * @copyright 2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Annotation;

/**
 * @Annotation(target={"ANNOTATION", "PROPERTY"})
 */
class Enumerator
{
    /**
     * References the Enum class for the parameter collection.
     *
     * @var string
     */
    public $value;

    /**
     * References the name of the property that holds the parameter collection.
     *
     * @var string
     */
    public $name;

    /**
     * References the property to assign the enum accessor to.
     *
     * @var string
     */
    public $property;

    /**
     * Specifies the parameter entity that is used to instantiate new parameter instances.
     * This information is only required if the Enumerator annotation is used outside the Generator annotation.
     *
     * @var string
     */
    public $type;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEnumeratorClass()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->property;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
