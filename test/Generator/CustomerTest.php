<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Cart;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Customer;

/**
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class CustomerTest extends \PHPUnit_Framework_TestCase
{
    public function testSetCart()
    {
        $cart     = new Cart();
        $customer = new Customer();

        $customer->setCart($cart);
        self::assertSame($cart, $customer->getCart());
    }

    /**
     * @expectedException \Doctrine\ORM\EntityNotFoundException
     */
    public function testGetCartEmpty()
    {
        $customer = new Customer();
        $customer->getCart();
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testGetCartTooManyArguments()
    {
        $customer = new Customer();
        $customer->getCart(1);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSetCartTooManyArguments()
    {
        $cart     = new Cart();
        $customer = new Customer();
        $customer->setCart($cart, 2);
    }
}
