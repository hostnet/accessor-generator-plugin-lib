<?php
namespace Hostnet\Component\AccessorGenerator\Reflection;

use Doctrine\Common\Collections\ArrayCollection;
use Hostnet\Component\AccessorGenerator\Collection\ImmutableCollection;
use Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassNotFoundException;

/**
 * Collection of all AccessorGenerator\Reflection classes that can
 * be used to lookup information about those classes without the
 * need for using the PHP auto loading mechanism or actually
 * loaded the classes into memory.
 *
 * This class is used to lookup properties on the referenced side of
 * associations. For example in the case you have an indexed collection
 * and a bi-directional association, we need the referenced side
 * information to be able to update it correctly.
 */
class Metadata
{
    /**
     * @var ArrayCollection
     */
    private $class_index ;

    public function __construct()
    {
        $this->class_index = new ArrayCollection();
    }

    public function addReflectionClass(ReflectionClass $reflection_class)
    {
        $this->class_index->set($reflection_class->getFullyQualifiedClassName(), $reflection_class);
    }

    /**
     * @param $name
     * @return ReflectionClass
     * @throws ClassNotFoundException
     */
    public function getReflectionClassByName($name)
    {
        if (!$this->class_index->containsKey($name)) {
            throw new ClassNotFoundException($name);
        }

        return $this->class_index->get($name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasReflectionClassByName($name)
    {
        return $this->class_index->containsKey($name);
    }

    /**
     * @return ImmutableCollection|ReflectionClass[]
     */
    public function getReflectionClasses()
    {
        return new ImmutableCollection($this->class_index);
    }
}
