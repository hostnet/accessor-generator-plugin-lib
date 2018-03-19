<?php
declare(strict_types=1);
/**
 * @copyright 2014-2018 Hostnet B.V.
 */

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Cart;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Customer;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testSetCustomer(): void
    {
        $cart     = new Cart();
        $customer = new Customer();

        $cart->setCustomer($customer);
        self::assertSame($customer, $cart->getCustomer());
    }

    public function testGetCustomerEmpty(): void
    {
        $cart = new Cart();
        self::assertNull($cart->getCustomer());
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testGetCustomerTooManyArguments(): void
    {
        $cart = new Cart();
        $cart->getCustomer(1);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSetCustomerTooManyArguments(): void
    {
        $cart     = new Cart();
        $customer = new Customer();
        $cart->setCustomer($customer, 2);
    }
}
