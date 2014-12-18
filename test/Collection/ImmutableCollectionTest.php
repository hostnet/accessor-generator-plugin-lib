<?php
namespace Hostnet\Component\AccessorGenerator\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ImmutableCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * @var ImmutableCollection
     */
    private $immutable_collection;

    public function setUp()
    {
        $this->collection           = new ArrayCollection([1, 'a' => 1, 2, 'c' => 3, 5, 8, 13]);
        $this->immutable_collection = new ImmutableCollection($this->collection);
    }

    public function testCompatabillity()
    {
        $col = $this->collection;
        $imm = $this->immutable_collection;

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

    public function testExists()
    {
        $exists = function ($key, $value) {
            return is_string($key);
        };

        // Check that the function is wrapped correctly.
        $col = $this->collection->exists($exists);
        $imm = $this->immutable_collection->exists($exists);
        $this->assertEquals($col, $imm);

        // Check that the closure is actualy working.
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

        // Check that the closure is actualy working.
        $this->assertEquals(0, $imm->count());
    }

    public function testForAll()
    {
        $for_all = function ($key, $value) {
            return is_numeric($value);
        };

        // Check that the function is wrapped correctly.
        $col = $this->collection->forAll($for_all);
        $imm = $this->immutable_collection->forAll($for_all);
        $this->assertEquals($col, $imm);

        // Check that the closure is actualy working.
        $this->assertTrue($imm);
    }

    public function testMap()
    {
        $map = function ($value) {
            return $value << 1;
        };
        $before = $this->immutable_collection->toArray();

        // Check that the function is wrapped correctly.
        $this->assertEquals($this->collection->map($map), $this->immutable_collection->map($map));

        // Test that we got a copy and did not change the original collection.
        $this->assertEquals($before, $this->immutable_collection->toArray());
    }

    public function testPartition()
    {
        $partition = function ($key, $value) {
            return $value % 2;
        };
        $before = $this->immutable_collection->toArray();

        // Check that the function is wrapped correctly.
        $col = $this->collection->partition($partition);
        $imm = $this->immutable_collection->partition($partition);
        $this->assertEquals($col, $imm);

        // Test that we got a copy and did not change the original collection.
        $this->assertEquals($before, $this->immutable_collection->toArray());
    }
}
