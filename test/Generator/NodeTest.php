<?php
declare(strict_types=1);
/**
 * @copyright 2014-2018 Hostnet B.V.
 */

namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Collections\Collection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Node;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    public function testGetOut(): void
    {
        $node     = new Node();
        $children = $node->getOut();
        self::assertEmpty($children);
        self::assertInstanceOf(Collection::class, $children);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetOutTooManyArguments(): void
    {
        $node = new Node();
        $node->getOut(1);
    }

    /**
     * @depends testGetOut
     */
    public function testAddOut(): void
    {
        $a = new Node();
        $b = new Node();
        $c = new Node();
        $d = new Node();

        // The initial list should be empty.
        self::assertEmpty($a->getOut());

        // Add and receive a node (cycle).
        $a->addOut($a);
        self::assertSame($a, $a->getOut()->first());
        self::assertCount(1, $a->getOut());

        // Add the same child again, we expect no error
        // but also no duplicate entries.
        $a->addOut($a);
        self::assertSame($a, $a->getOut()->first());
        self::assertCount(1, $a->getOut());

        // Create a fully connected network of four nodes
        // inlcuding self references per node.
        $a->addOut($a);
        $a->addOut($b);
        $a->addOut($c);
        $a->addOut($d);
        $b->addOut($a);
        $b->addOut($b);
        $b->addOut($c);
        $b->addOut($d);
        $c->addOut($a);
        $c->addOut($b);
        $c->addOut($c);
        $c->addOut($d);
        $d->addOut($a);
        $d->addOut($b);
        $d->addOut($c);
        $d->addOut($d);
        self::assertEquals([$a, $b, $c, $d], $a->getOut()->toArray());
        self::assertEquals([$a, $b, $c, $d], $b->getOut()->toArray());
        self::assertEquals([$a, $b, $c, $d], $c->getOut()->toArray());
        self::assertEquals([$a, $b, $c, $d], $d->getOut()->toArray());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testAddOutTooManyArguments(): void
    {
        $node = new Node();
        $node->addOut($node, 2);
    }

    /**
     * @depends testGetOut
     * @depends testAddOut
     */
    public function testRemoveOut(): void
    {
        $node  = new Node();
        $child = new Node();

        // The initial list should be empty.
        self::assertEmpty($node->getOut());

        // Add and receive a child.
        $node->addOut($child);
        self::assertSame($child, $node->getOut()->first());
        self::assertCount(1, $node->getOut());

        // Remove child, check return value and check list.
        self::assertSame($node->removeOut($child), $node);
        self::assertCount(0, $node->getOut());

        // Remove not existing child, check return value. No
        // error is expected.
        self::assertSame($node->removeOut($child), $node);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testRemoveOutTooManyArguments(): void
    {
        $node = new Node();
        $node->RemoveOut($node, 2);
    }
}
