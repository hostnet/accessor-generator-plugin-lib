<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\ORM\EntityNotFoundException;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Item;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Shipping;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testSetShipping(): void
    {
        $item     = new Item();
        $shipping = new Shipping();

        $item->setShipping($shipping);
        self::assertSame($shipping, $item->getShipping());
    }

    public function testGetShippingEmpty(): void
    {
        $item = new Item();

        $this->expectException(EntityNotFoundException::class);

        $item->getShipping();
    }

    public function testGetShippingTooManyArguments(): void
    {
        $item = new Item();

        $this->expectException(\BadMethodCallException::class);

        $item->getShipping(1);
    }

    public function testSetShippingTooManyArguments(): void
    {
        $item     = new Item();
        $shipping = new Shipping();

        $this->expectException(\BadMethodCallException::class);

        $item->setShipping($shipping, 2);
    }
}
