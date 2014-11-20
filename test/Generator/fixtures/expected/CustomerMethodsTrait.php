<?php
// Generated at 2014-12-10 17:27:16 by hboomsma on se18-03-73-3f-9f-e0

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Cart;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Customer;

trait CustomerMethodsTrait
{
    /**
     * Get cart
     *
     * @return Cart
     * @throws \InvalidArgumentException
     */
    public function getCart()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getCart() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->cart === null) {
            throw new \Doctrine\ORM\EntityNotFoundException(
                'Property Cart references an other entity ' .
                'but is not found and also is not nullable for parameter cart.'
            );
        }

        return $this->cart;
    }

    /**
     * Set cart
     *
     * @param Cart $cart
     * @return Customer
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setCart(Cart $cart)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setCart() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        $this->cart = $cart;
        return $this;
    }
}
