<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Node;

trait NodeMethodsTrait
{
    /**
     * Get out
     *
     * @return \Doctrine\Common\Collections\Collection | Node[]
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

        return $this->out;
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
        $method = new \ReflectionMethod($out, 'addIn');
        $method->setAccessible(true);
        $method->invoke($out, $this);
        $method->setAccessible(false);
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

        $method = new \ReflectionMethod($out, 'removeIn');
        $method->setAccessible(true);
        $method->invoke($out, $this);
        $method->setAccessible(false);
        return $this;
    }

    /**
     * Add in
     *
     * @param Node $in
     * @return Node
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    private function addIn(Node $in)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addIn() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->in === null) {
            $this->in = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->in->contains($in)) {
            return $this;
        }

        $this->in->add($in);
        $method = new \ReflectionMethod($in, 'addOut');
        $method->setAccessible(true);
        $method->invoke($in, $this);
        $method->setAccessible(false);
        return $this;
    }

    /**
     * Remove in
     *
     * @param Node $in
     * @return Node
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    private function removeIn(Node $in)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'removeIn() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (! $this->in instanceof \Doctrine\Common\Collections\Collection
            || ! $this->in->contains($in)
        ) {
            return $this;
        }

        $this->in->removeElement($in);

        $method = new \ReflectionMethod($in, 'removeOut');
        $method->setAccessible(true);
        $method->invoke($in, $this);
        $method->setAccessible(false);
        return $this;
    }
}
