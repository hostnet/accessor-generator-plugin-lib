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

        self::assertEquals(2, count($parent->getChildren()));
        self::assertSame($node_a, $parent->getChildren()[0]);
        self::assertSame($node_b, $parent->getChildren()[1]);
    }

    public function testGetChildren()
    {
        $category = new Category();
        $children = $category->getChildren();
        self::assertEmpty($children);
        self::assertInstanceOf(Collection::class, $children);
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
        self::assertEmpty($category->getChildren());

        // Add and receive a child.
        $category->addChild($child);
        self::assertSame($child, $category->getChildren()->first());
        self::assertEquals(1, $category->getChildren()->count());

        // Add the same child again, we expect no error
        // but also no duplicate entries.
        $category->addChild($child);
        self::assertSame($child, $category->getChildren()->first());
        self::assertEquals(1, $category->getChildren()->count());
    }

    /**
     * @depends testGetChildren
     */
    public function testAddSameChildConstructor()
    {
        $category = new Category();
        $child    = new Category($category);

        // Add and receive a child.
        self::assertSame($category, $child->getParent());

        // Add the same child again, we expect no error
        // but also no duplicate entries.
        $category->addChild($child);
        self::assertSame($child, $category->getChildren()->first());
        self::assertEquals(1, $category->getChildren()->count());
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
        self::assertEmpty($category->getChildren());

        // Add and receive a child.
        $category->addChild($child);
        self::assertSame($child, $category->getChildren()->first());
        self::assertEquals(1, $category->getChildren()->count());
        self::assertSame($category, $child->getParent());

        // Remove child, check return value and check list.
        self::assertSame($category->removeChild($child), $category);
        self::assertEquals(0, $category->getChildren()->count());
        self::assertNull($child->getParent());

        // Remove not existing child, check return value. No
        // error is expected.
        self::assertSame($category->removeChild($child), $category);
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
     * @expectedException \BadMethodCallException
     */
    public function testSetParentTooManyArguments()
    {
        $c = new Category();
        $c->setParent($c, 1);
    }

    public function testSetParent()
    {
        $a = new Category();
        $b = new Category();
        $c = new Category();
        $d = new Category();
        $e = new Category();

        // Add b as child to a
        $a->addChild($b);
        $a->addChild($b);
        self::assertSame($b, $a->getChildren()->first());
        self::assertSame($a, $b->getParent());

        // Set a as parent to b
        $b->setParent($a);
        $b->setParent($a);
        self::assertSame($b, $a->getChildren()->first());
        self::assertSame($a, $b->getParent());

        // Unset parent of b
        $b->setParent(null);
        self::assertEquals(0, $a->getChildren()->count());
        self::assertNull($b->getParent());

        // Add b as child to c
        $c->addChild($b);
        $c->addChild($b);
        self::assertSame($b, $c->getChildren()->first());
        self::assertSame($c, $b->getParent());

        // Set a as parent to b
        $b->setParent($a);
        $b->setParent($a);
        $a->addChild($b);
        self::assertEquals(0, $c->getChildren()->count());
        self::assertSame($b, $a->getChildren()->first());
        self::assertSame($a, $b->getParent());

        // Set parent when get children has not been called
        $e->setParent($d);
        self::assertSame($e, $d->getChildren()->first());
    }

    public function testGetParentNotInitialized()
    {
        $c = new Category();
        self::assertNull($c->getParent());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetParentTooManyArguments()
    {
        $c = new Category();
        $c->getParent([]);
    }
}
