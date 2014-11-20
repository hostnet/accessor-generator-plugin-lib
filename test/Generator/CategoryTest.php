<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Collections\Collection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Category;

class CategoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCategory()
    {
        $parent = new Category();
        $node_a = new Category();
        $node_b = new Category();

        $parent->addChild($node_a)->addChild($node_b);
        $parent->removeChild($parent);

        $this->assertEquals(2, count($parent->getChildren()));
        $this->assertSame($node_a, $parent->getChildren()[0]);
        $this->assertSame($node_b, $parent->getChildren()[1]);
    }

    public function testGetChildren()
    {
        $category = new Category();
        $children = $category->getChildren();
        $this->assertEmpty($children);
        $this->assertInstanceOf(Collection::class, $children);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetChildrenTooManyArguments()
    {
        $category = new Category();
        $category->getChildren(1);
    }

    /**
     * @depends testGetChildren
     */
    public function testAddChild()
    {
        $category = new Category();
        $child    = new Category();

        // The initial list should be empty.
        $this->assertEmpty($category->getChildren());

        // Add and receive a child.
        $category->addChild($child);
        $this->assertSame($child, $category->getChildren()->first());
        $this->assertEquals(1, $category->getChildren()->count());

        // Add the same child again, we expect no error
        // but also no duplicate entries.
        $category->addChild($child);
        $this->assertSame($child, $category->getChildren()->first());
        $this->assertEquals(1, $category->getChildren()->count());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testAddChildTooManyArguments()
    {
        $category = new Category();
        $category->addChild($category, 2);
    }

    /**
     * @depends testGetChildren
     * @depends testAddChild
     */
    public function testRemoveChild()
    {
        $category = new Category();
        $child    = new Category();

        // The initial list should be empty.
        $this->assertEmpty($category->getChildren());

        // Add and receive a child.
        $category->addChild($child);
        $this->assertSame($child, $category->getChildren()->first());
        $this->assertEquals(1, $category->getChildren()->count());

        // Remove child, check return value and check list.
        $this->assertSame($category->removeChild($child), $category);
        $this->assertEquals(0, $category->getChildren()->count());

        // Remove not existing child, check return value. No
        // error is expected.
        $this->assertSame($category->removeChild($child), $category);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testRemoveChildTooManyArguments()
    {
        $category = new Category();
        $category->RemoveChild($category, 2);
    }

    /**
     * @expectedException \LogicException
     */
    public function testAddMultipleTimes()
    {
        $a = new Category();
        $b = new Category();
        $p = new Category();

        $a->addChild($p);
        $b->addChild($p);
    }

    /**
     * We are testing a private function here because
     * it is part of the api used by the programmer
     * when (s)he uses the generated code. We want
     * to be sure the association is kept in sync on
     * both sided.
     * @expectedException \LogicException
     */
    public function testSetParentNotSynced()
    {
        $category = new Category();
        $method   = new \ReflectionMethod($category, 'setParent');
        $method->setAccessible(true);
        $method->invoke($category, new Category());
    }

    /**
     * We are testing a private function here because
     * it is part of the api used by the programmer.
     *
     * @expectedException \BadMethodCallException
     */
    public function testSetParentTooManyArguments()
    {
        $category = new Category();
        $method   = new \ReflectionMethod($category, 'setParent');
        $method->setAccessible(true);
        $method->invoke($category, $category, 2);
    }

    /**
     * We are testing a private function here because
     * it is part of the api used by the programmer.
     *
     * @expectedException \BadMethodCallException
     */
    public function testSetParentNoArguments()
    {
        $category = new Category();
        $method   = new \ReflectionMethod($category, 'setParent');
        $method->setAccessible(true);
        $method->invoke($category);
    }
}
