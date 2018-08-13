<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Collection\ImmutableCollection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Node;

trait NodeMethodsTrait
{
    /**
     * Gets out
     *
     * @throws \BadMethodCallException
     *
     * @return \Hostnet\Component\AccessorGenerator\Generator\fixtures\Node[]|ImmutableCollection
     */
    public function getOut(): iterable
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getOut() has no arguments but %d given.',
                    \func_num_args()
                )
            );
        }

        if ($this->out === null) {
            $this->out = new \Doctrine\Common\Collections\ArrayCollection();
        }

        return new ImmutableCollection($this->out);
    }

    /**
     * Adds the given out to this collection.
     *
     * @throws \BadMethodCallException if the number of arguments is not correct.
     * @throws \Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException
     *
     * @param Node $out
     *
     * @return $this|Node
     */
    public function addOut(Node $out)
    {
        if (\func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addOut() has one argument but %d given.',
                    \func_num_args()
                )
            );
        }

        /* @var $this->out \Doctrine\Common\Collections\ArrayCollection */
        if ($this->out === null) {
            $this->out = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->out->contains($out)) {
            return $this;
        }

        $this->out->add($out);
        try {
            $property = new \ReflectionProperty(Node::class, 'in');
        } catch (\ReflectionException $e) {
            throw new \Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
        $property->setAccessible(true);
        if (method_exists(Node::class, 'addIn')) {
            $adder = new \ReflectionMethod(Node::class, 'addIn');
            $adder->setAccessible(true);
            $adder->invoke($out, $this);
            $adder->setAccessible(false);
        } else {
            $collection = $property->getValue($out);
            if (!$collection) {
                $collection = new \Doctrine\Common\Collections\ArrayCollection();
                $property->setValue($out, $collection);
            }
            $collection->add($this);
        }
        $property->setAccessible(false);

        return $this;
    }

    /**
     * Removes the given out from this collection.
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     *
     * @param Node $out
     *
     * @return $this|Node
     */
    public function removeOut(Node $out)
    {
        if (\func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'removeOut() has one argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if (! $this->out instanceof \Doctrine\Common\Collections\Collection
            || ! $this->out->contains($out)
        ) {
            return $this;
        }

        $this->out->removeElement($out);

        $property = new \ReflectionProperty(Node::class, 'in');
        $property->setAccessible(true);
        $collection = $property->getValue($out);
        if ($collection) {
            $collection->removeElement($this);
        }
        $property->setAccessible(false);

        return $this;
    }
}
