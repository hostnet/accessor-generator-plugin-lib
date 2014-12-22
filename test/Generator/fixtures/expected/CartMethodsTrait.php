<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Cart;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Customer as Client;

trait CartMethodsTrait
{
    /**
     * Get customer
     *
     * @return Client|null
     * @throws \InvalidArgumentException
     */
    public function getCustomer()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getCustomer() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        return $this->customer;
    }

    /**
     * Set customer
     *
     * @param Client $customer
     * @return Cart
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setCustomer(Client $customer)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setCustomer() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        $this->customer = $customer;
        return $this;
    }
}
