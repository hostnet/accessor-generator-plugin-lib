<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\ORM\EntityNotFoundException;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Cart;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    public function testSetCart(): void
    {
        $cart     = new Cart();
        $customer = new Customer();

        $customer->setCart($cart);
        self::assertSame($cart, $customer->getCart());
    }

    public function testGetCartEmpty(): void
    {
        $customer = new Customer();

        $this->expectException(EntityNotFoundException::class);

        $customer->getCart();
    }

    public function testGetCartTooManyArguments(): void
    {
        $customer = new Customer();

        $this->expectException(\BadMethodCallException::class);

        $customer->getCart(1);
    }

    public function testSetCartTooManyArguments(): void
    {
        $cart     = new Cart();
        $customer = new Customer();

        $this->expectException(\BadMethodCallException::class);

        $customer->setCart($cart, 2);
    }
}
