<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Cart;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Customer as Client;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Customer;

trait CartMethodsTrait
{
    /**
     * Gets customer
     *
     * @throws \BadMethodCallException
     *
     * @return Client|null
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
     * Sets customer
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     *
     * @param  Client $customer
     * @return $this|Cart
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
        // Create reflection property.
        $property = new \ReflectionProperty(Customer::class, 'cart');
        $property->setAccessible(true);

        // Unset old value and set the new value to keep the inverse side in sync.
        $this->customer && $property->setValue($this->customer, null);
        $customer && $property->setValue($customer, $this);

        // Update the accessible flag to disallow further again.
        $property->setAccessible(false);

        $this->customer = $customer;

        return $this;
    }
}
