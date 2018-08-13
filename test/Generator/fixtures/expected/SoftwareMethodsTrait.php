<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Collection\ImmutableCollection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Feature;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\FeatureInterface;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Software;

trait SoftwareMethodsTrait
{
    /**
     * Gets features
     *
     * @throws \BadMethodCallException
     *
     * @return \Hostnet\Component\AccessorGenerator\Generator\fixtures\FeatureInterface[]|ImmutableCollection
     */
    public function getFeatures(): iterable
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getFeatures() has no arguments but %d given.',
                    \func_num_args()
                )
            );
        }

        if ($this->features === null) {
            $this->features = new \Doctrine\Common\Collections\ArrayCollection();
        }

        return new ImmutableCollection($this->features);
    }

    /**
     * Adds the given feature to this collection.
     *
     * @throws \BadMethodCallException if the number of arguments is not correct.
     * @throws \LogicException         if a member was added that already exists within the collection.
     * @throws \Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException
     *
     * @param FeatureInterface $feature
     *
     * @return $this|Software
     */
    public function addFeature(FeatureInterface $feature)
    {
        if (\func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addFeatures() has one argument but %d given.',
                    \func_num_args()
                )
            );
        }

        /* @var $this->features \Doctrine\Common\Collections\ArrayCollection */
        if ($this->features === null) {
            $this->features = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->features->contains($feature)) {
            return $this;
        }

        $this->features->add($feature);
        try {
            if ($feature instanceof Feature) {
                $property = new \ReflectionProperty(Feature::class, 'software');
            } else {
                $property = new \ReflectionProperty($feature, 'software');
            }
        } catch (\ReflectionException $e) {
            throw new \Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
        $property->setAccessible(true);
        $value = $property->getValue($feature);
        if ($value && $value !== $this) {
            throw new \LogicException('Feature can not be added to more than one Software.');
        }
        $property->setValue($feature, $this);
        $property->setAccessible(false);

        return $this;
    }

    /**
     * Removes the given feature from this collection.
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     *
     * @param FeatureInterface $feature
     *
     * @return $this|Software
     */
    public function removeFeature(FeatureInterface $feature)
    {
        if (\func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'removeFeatures() has one argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if (! $this->features instanceof \Doctrine\Common\Collections\Collection
            || ! $this->features->contains($feature)
        ) {
            return $this;
        }

        $this->features->removeElement($feature);

        $property = new \ReflectionProperty(Feature::class, 'software');
        $property->setAccessible(true);
        $property->setValue($feature, null);
        $property->setAccessible(false);

        return $this;
    }
}
