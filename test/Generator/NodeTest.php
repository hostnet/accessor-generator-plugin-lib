<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Collections\Collection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Node;

class NodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetOut()
    {
        $node     = new Node();
        $children = $node->getOut();
        $this->assertEmpty($children);
        $this->assertInstanceOf(Collection::class, $children);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetOutTooManyArguments()
    {
        $node = new Node();
        $node->getOut(1);
    }

    /**
     * @depends testGetOut
     */
    public function testAddOut()
    {
        $a = new Node();
        $b = new Node();
        $c = new Node();
        $d = new Node();

        // The initial list should be empty.
        $this->assertEmpty($a->getOut());

        // Add and receive a node (cycle).
        $a->addOut($a);
        $this->assertSame($a, $a->getOut()->first());
        $this->assertCount(1, $a->getOut());

        // Add the same child again, we expect no error
        // but also no duplicate entries.
        $a->addOut($a);
        $this->assertSame($a, $a->getOut()->first());
        $this->assertCount(1, $a->getOut());

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
        $this->assertEquals([$a, $b, $c, $d], $a->getOut()->toArray());
        $this->assertEquals([$a, $b, $c, $d], $b->getOut()->toArray());
        $this->assertEquals([$a, $b, $c, $d], $c->getOut()->toArray());
        $this->assertEquals([$a, $b, $c, $d], $d->getOut()->toArray());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testAddOutTooManyArguments()
    {
        $node = new Node();
        $node->addOut($node, 2);
    }

    /**
     * @depends testGetOut
     * @depends testAddOut
     */
    public function testRemoveOut()
    {
        $node  = new Node();
        $child = new Node();

        // The initial list should be empty.
        $this->assertEmpty($node->getOut());

        // Add and receive a child.
        $node->addOut($child);
        $this->assertSame($child, $node->getOut()->first());
        $this->assertCount(1, $node->getOut());

        // Remove child, check return value and check list.
        $this->assertSame($node->removeOut($child), $node);
        $this->assertCount(0, $node->getOut());

        // Remove not existing child, check return value. No
        // error is expected.
        $this->assertSame($node->removeOut($child), $node);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testRemoveOutTooManyArguments()
    {
        $node = new Node();
        $node->RemoveOut($node, 2);
    }

    /**
     * We are testing a private function here because
     * it is part of the api used by the programmer.
     */
    public function testAddInMultipleTimes()
    {
        $node = new Node();

        // Make private parts public.
        $method   = new \ReflectionMethod($node, 'addIn');
        $property = new \ReflectionProperty($node, 'in');
        $property->setAccessible(true);
        $method->setAccessible(true);

        // Add node twice.
        $this->assertSame($node, $method->invoke($node, $node));
        $this->assertSame($node, $method->invoke($node, $node));

        // Check that only one node has been added.
        $this->assertCount(1, $property->getValue($node)->toArray());
    }

    /**
     * We are testing a private function here because
     * it is part of the api used by the programmer.
     */
    public function testAddInNoArguments()
    {
        $node   = new Node();
        $method = new \ReflectionMethod($node, 'addIn');
        $this->assertEquals(1, $method->getNumberOfParameters());
    }


    /**
     * We are testing a private function here because
     * it is part of the api used by the programmer.
     *
     * @expectedException \BadMethodCallException
     */
    public function testAddInTooManyArguments()
    {
        $node   = new Node();
        $method = new \ReflectionMethod($node, 'addIn');
        $method->setAccessible(true);
        $method->invoke($node, $node, 2);
    }

    /**
     * We are testing a private function here because
     * it is part of the api used by the programmer.
     */
    public function testRemoveInNoArguments()
    {
        $node   = new Node();
        $method = new \ReflectionMethod($node, 'removeIn');
        $this->assertEquals(1, $method->getNumberOfParameters());
    }

    /**
     * We are testing a private function here because
     * it is part of the api used by the programmer.
     *
     * @expectedException \BadMethodCallException
     */
    public function testRemoveInTooManyArguments()
    {
        $node   = new Node();
        $method = new \ReflectionMethod($node, 'removeIn');
        $method->setAccessible(true);
        $method->invoke($node, $node, 2);
    }

    /**
     * We are testing a private function here because
     * it is part of the api used by the programmer.
     */
    public function testRemoveInNonExistent()
    {
        $node   = new Node();
        $method = new \ReflectionMethod($node, 'removeIn');
        $method->setAccessible(true);
        $this->assertSame($node, $method->invoke($node, $node));
    }
}
