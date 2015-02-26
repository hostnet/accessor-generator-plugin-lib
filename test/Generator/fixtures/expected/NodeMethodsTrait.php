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
     * Get out
     *
     * @return \Hostnet\Component\AccessorGenerator\Generator\fixtures\Node[]
     * @return \Hostnet\Component\AccessorGenerator\Collection\ConstCollectionInterface
     * @throws \InvalidArgumentException
     */
    public function getOut()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getOut() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->out === null) {
            $this->out = new \Doctrine\Common\Collections\ArrayCollection();
        }

        return new ImmutableCollection($this->out);
    }

    /**
     * Add out
     *
     * @param Node $out
     * @return Node
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function addOut(Node $out)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addOut() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->out === null) {
            $this->out = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->out->contains($out)) {
            return $this;
        }

        $this->out->add($out);
        $property = new \ReflectionProperty(Node::class, 'in');
        $property->setAccessible(true);
        $collection = $property->getValue($out);
        if (!$collection) {
            $collection = new \Doctrine\Common\Collections\ArrayCollection();
            $property->setValue($out, $collection);
        }
        $collection->add($this);
        $property->setAccessible(false);
        return $this;
    }

    /**
     * Remove out
     *
     * @param Node $out
     * @return Node
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function removeOut(Node $out)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'removeOut() has one argument but %d given.',
                    func_num_args()
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
