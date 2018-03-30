<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Item;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Shipping;
use PHPUnit\Framework\TestCase;

/**
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class ItemTest extends TestCase
{
    public function testSetShipping()
    {
        $item     = new Item();
        $shipping = new Shipping();

        $item->setShipping($shipping);
        self::assertSame($shipping, $item->getShipping());
    }

    /**
     * @expectedException \Doctrine\ORM\EntityNotFoundException
     */
    public function testGetShippingEmpty()
    {
        $item = new Item();
        $item->getShipping();
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testGetShippingTooManyArguments()
    {
        $item = new Item();
        $item->getShipping(1);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSetShippingTooManyArguments()
    {
        $item     = new Item();
        $shipping = new Shipping();
        $item->setShipping($shipping, 2);
    }
}
