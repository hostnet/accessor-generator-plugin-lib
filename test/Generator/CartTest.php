<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

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

    public function testGetCustomerTooManyArguments(): void
    {
        $cart = new Cart();

        $this->expectException(\BadMethodCallException::class);

        $cart->getCustomer(1);
    }

    public function testSetCustomerTooManyArguments(): void
    {
        $cart     = new Cart();
        $customer = new Customer();

        $this->expectException(\BadMethodCallException::class);

        $cart->setCustomer($customer, 2);
    }
}
