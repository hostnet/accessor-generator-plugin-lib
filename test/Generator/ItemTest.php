<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Item;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Shipping;
use PHPUnit\Framework\TestCase;

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
