<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Item;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Shipping;

trait ItemMethodsTrait
{
    /**
     * Gets shipping
     *
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return Shipping
     */
    public function getShipping(): Shipping
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getShipping() has no arguments but %d given.',
                    \func_num_args()
                )
            );
        }

        if ($this->shipping === null) {
            throw new \Doctrine\ORM\EntityNotFoundException('Missing required property "shipping".');
        }

        return $this->shipping;
    }

    /**
     * Sets shipping
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     *
     * @param Shipping $shipping
     *
     * @return $this|Item
     */
    public function setShipping(Shipping $shipping)
    {
        if (\func_num_args() !== 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setShipping() has one argument but %d given.',
                    \func_num_args()
                )
            );
        }

        $this->shipping = $shipping;

        return $this;
    }
}
