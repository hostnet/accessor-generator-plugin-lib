<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Collections\Collection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Cart;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Customer;

/**
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class CartTest extends \PHPUnit_Framework_TestCase
{
    public function testSetCustomer()
    {
        $cart     = new Cart();
        $customer = new Customer();

        $cart->setCustomer($customer);
        $this->assertSame($customer, $cart->getCustomer());
    }

    /**
     * @expectedException \Doctrine\ORM\EntityNotFoundException
     */
    public function testGetCustomerEmpty()
    {
        $cart = new Cart();
        $cart->getCustomer();
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testGetCustomerTooManyArguments()
    {
        $cart = new Cart();
        $cart->getCustomer(1);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSetCustomerTooManyArguments()
    {
        $cart     = new Cart();
        $customer = new Customer();
        $cart->setCustomer($customer, 2);
    }
}
