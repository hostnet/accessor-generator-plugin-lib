<?php
// Generated at 2014-12-17 13:25:27 by hboomsma on se18-03-73-3f-9f-e0

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Item;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Shipping;

trait ItemMethodsTrait
{
    /**
     * Get shipping
     *
     * @return Shipping
     * @throws \InvalidArgumentException
     */
    public function getShipping()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getShipping() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->shipping === null) {
            throw new \Doctrine\ORM\EntityNotFoundException('Missing required property "shipping".');
        }

        return $this->shipping;
    }

    /**
     * Set shipping
     *
     * @param Shipping $shipping
     * @return Item
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setShipping(Shipping $shipping)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setShipping() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        $this->shipping = $shipping;
        return $this;
    }
}
