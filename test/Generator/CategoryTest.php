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
     * @depends testGetChildren
     */
    public function testAddSameChildConstructor()
    {
        $category = new Category();
        $child    = new Category($category);

        // Add and receive a child.
        $this->assertSame($category, $child->getParent());

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
        $this->assertSame($category, $child->getParent());

        // Remove child, check return value and check list.
        $this->assertSame($category->removeChild($child), $category);
        $this->assertEquals(0, $category->getChildren()->count());
        $this->assertNull($child->getParent());

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
        $this->assertSame($b, $a->getChildren()->first());
        $this->assertSame($a, $b->getParent());

        // Set a as parent to b
        $b->setParent($a);
        $b->setParent($a);
        $this->assertSame($b, $a->getChildren()->first());
        $this->assertSame($a, $b->getParent());

        // Unset parent of b
        $b->setParent(null);
        $this->assertEquals(0, $a->getChildren()->count());
        $this->assertNull($b->getParent());

        // Add b as child to c
        $c->addChild($b);
        $c->addChild($b);
        $this->assertSame($b, $c->getChildren()->first());
        $this->assertSame($c, $b->getParent());

        // Set a as parent to b
        $b->setParent($a);
        $b->setParent($a);
        $a->addChild($b);
        $this->assertEquals(0, $c->getChildren()->count());
        $this->assertSame($b, $a->getChildren()->first());
        $this->assertSame($a, $b->getParent());

        // Set parent when get children has not been called
        $e->setParent($d);
        $this->assertSame($e, $d->getChildren()->first());
    }

    public function testGetParentNotInitialized()
    {
        $c = new Category();
        $this->assertNull($c->getParent());
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
