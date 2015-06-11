<?php
namespace Hostnet\Component\AccessorGenerator\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;

class ImmutableCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Collection|Selectable
     */
    private $collection;

    /**
     * @var ImmutableCollection
     */
    private $immutable_collection;

    /**
     * @var ImmutableCollection
     */
    private $clone_immutable_collection;

    public function setUp()
    {
        $this->collection                 = new ArrayCollection([1, 'a' => 1, 2, 'c' => 3, 5, 8, 13]);
        $this->immutable_collection       = new ImmutableCollection($this->collection);
        $this->clone_immutable_collection = clone $this->immutable_collection;
    }

    public function testCompatibility()
    {
        $col = $this->collection;
        $imm = $this->immutable_collection;
        $cln = $this->clone_immutable_collection;

        $this->assertEquals($col[0], $imm[0]);
        $this->assertEquals(isset($col[0]), isset($imm[0]));

        foreach ($imm as $key => $value) {
            $this->assertSame($value, $col[$key]);
        }

        $this->assertSame($col->contains(1), $imm->contains(1));
        $this->assertSame($col->contains(0), $imm->contains(0));
        $this->assertSame($col->containsKey('a'), $imm->containsKey('a'));
        $this->assertSame($col->containsKey('a'), $imm->containsKey('a'));
        $this->assertSame($col->count(), $imm->count());
        $this->assertSame($col->current(), $imm->current());
        $this->assertSame($col->key(), $imm->key());
        $this->assertSame($col->first(), $imm->first());
        $this->assertSame($col->last(), $imm->last());
        $this->assertSame($col->getValues(), $imm->getValues());
        $this->assertSame($col->get(3), $imm->get(3));
        $this->assertSame($col->getKeys(), $imm->getKeys());
        $this->assertSame($col->toArray(), $imm->toArray());
        $this->assertSame($col->indexOf(3), $imm->indexOf(3));
        $this->assertSame($col->isEmpty(), $imm->isEmpty());
        $this->assertSame($col->slice(2, 2), $imm->slice(2, 2));
        $this->assertSame($col->next(), $imm->current());
        $this->assertSame($imm->next(), $col->current());
        $this->assertSame($col->add('a'), $cln->add('a'));
        $this->assertSame($col->remove(0), $cln->remove(0));
        $this->assertSame($col->removeElement('a'), $cln->removeElement('a'));
    }

    /**
     * @expectedException \LogicException
     */
    public function testAdd()
    {
        $this->immutable_collection->add(0);
    }

    /**
     * @expectedException \LogicException
     */
    public function testClear()
    {
        $this->immutable_collection->clear();
    }

    /**
     * @expectedException \LogicException
     */
    public function testRemove()
    {
        $this->immutable_collection->remove(0);
    }

    /**
     * @expectedException \LogicException
     */
    public function testRemoveElement()
    {
        $this->immutable_collection->removeElement(0);
    }

    /**
     * @expectedException \LogicException
     */
    public function testSet()
    {
        $this->immutable_collection->set(0, 0);
    }

    /**
     * @expectedException \LogicException
     */
    public function testOffsetSet()
    {
        $this->immutable_collection[0] = 0;
    }

    /**
     * @expectedException \LogicException
     */
    public function testOffsetUnset()
    {
        unset($this->immutable_collection[0]);
    }

    public function testCloneAdd()
    {
        $this->clone_immutable_collection->add('an item');
        $this->assertContains('an item', $this->clone_immutable_collection);
    }

    public function testCloneClear()
    {
        $this->clone_immutable_collection->clear();
        $this->assertEmpty($this->clone_immutable_collection);
    }

    public function testCloneRemove()
    {
        $this->clone_immutable_collection->remove(0);
        $this->assertArrayNotHasKey(0, $this->clone_immutable_collection);
        $this->assertContains(1, $this->clone_immutable_collection);
    }

    public function testCloneRemoveElement()
    {
        // Remove fist element with value 1.
        $this->clone_immutable_collection->removeElement(1);
        // Make sure the second element with value 1 still exists.
        $this->assertContains(1, $this->clone_immutable_collection);

        // Remove second element with value 1.
        $this->clone_immutable_collection->removeElement(1);
        // Make sure all elements with value 1 are gone.
        $this->assertNotContains(1, $this->clone_immutable_collection);
    }

    public function testCloneSet()
    {
        $this->clone_immutable_collection->set(0, 0);
        $this->assertEquals(0, $this->clone_immutable_collection[0]);
    }

    public function testCloneOffsetSet()
    {
        $this->clone_immutable_collection[0] = 0;
        $this->assertEquals(0, $this->clone_immutable_collection[0]);
    }

    public function testCloneOffsetUnset()
    {
        unset($this->clone_immutable_collection[0]);
        $this->assertArrayNotHasKey(0, $this->clone_immutable_collection);
    }

    public function testExists()
    {
        $exists = function ($key, $value) {
            return is_string($key) && is_numeric($value);
        };

        // Check that the function is wrapped correctly.
        $col = $this->collection->exists($exists);
        $imm = $this->immutable_collection->exists($exists);
        $this->assertEquals($col, $imm);

        // Check that the closure is actually working.
        $this->assertTrue($imm);
    }

    public function testFilter()
    {
        $filter = function ($value) {
            return is_string($value);
        };

        // Check that the function is wrapped correctly.
        $col = $this->collection->filter($filter);
        $imm = $this->immutable_collection->filter($filter);
        $this->assertEquals($col, $imm);

        // Check that the closure is actually working.
        $this->assertEquals(0, $imm->count());
    }

    public function testForAll()
    {
        $for_all = function ($key, $value) {
            return is_numeric($value) && is_numeric($key);
        };

        // Check that the function is wrapped correctly.
        $col = $this->collection->forAll($for_all);
        $imm = $this->immutable_collection->forAll($for_all);
        $this->assertEquals($col, $imm);

        // Check that the closure is actually working.
        $this->assertFalse($imm);
    }

    public function testMap()
    {
        $before = $this->immutable_collection->toArray();
        $map    = function ($value) {
            return $value << 1;
        };

        // Check that the function is wrapped correctly.
        $this->assertEquals($this->collection->map($map), $this->immutable_collection->map($map));

        // Test that we got a copy and did not change the original collection.
        $this->assertEquals($before, $this->immutable_collection->toArray());
    }

    public function testPartition()
    {
        $before    = $this->immutable_collection->toArray();
        $partition = function ($key, $value) {
            return $value % 2 && is_string($key);
        };

        // Check that the function is wrapped correctly.
        $col = $this->collection->partition($partition);
        $imm = $this->immutable_collection->partition($partition);
        $this->assertEquals($col, $imm);

        // Test that we got a copy and did not change the original collection.
        $this->assertEquals($before, $this->immutable_collection->toArray());
    }

    public function testMatching()
    {
        $criteria = (new Criteria());
        $before   = $this->immutable_collection->toArray();

        // Check that the function is wrapped correctly.
        $col = $this->collection->matching($criteria);
        $imm = $this->immutable_collection->matching($criteria);
        $this->assertEquals($col, $imm);

        // Test that we got a copy and did not change the original collection.
        $this->assertEquals($before, $this->immutable_collection->toArray());
    }
}
