<?php
declare(strict_types=1);
/**
 * @copyright 2014-2018 Hostnet B.V.
 */

namespace Hostnet\Component\AccessorGenerator\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use PHPUnit\Framework\TestCase;

class ImmutableCollectionTest extends TestCase
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

    public function testCompatibility(): void
    {
        $col = $this->collection;
        $imm = $this->immutable_collection;
        $cln = $this->clone_immutable_collection;

        self::assertEquals($col[0], $imm[0]);
        self::assertEquals(isset($col[0]), isset($imm[0]));

        foreach ($imm as $key => $value) {
            self::assertSame($value, $col[$key]);
        }

        self::assertSame($col->contains(1), $imm->contains(1));
        self::assertSame($col->contains(0), $imm->contains(0));
        self::assertSame($col->containsKey('a'), $imm->containsKey('a'));
        self::assertSame($col->containsKey('a'), $imm->containsKey('a'));
        self::assertSame($col->count(), $imm->count());
        self::assertSame($col->current(), $imm->current());
        self::assertSame($col->key(), $imm->key());
        self::assertSame($col->first(), $imm->first());
        self::assertSame($col->last(), $imm->last());
        self::assertSame($col->getValues(), $imm->getValues());
        self::assertSame($col->get(3), $imm->get(3));
        self::assertSame($col->getKeys(), $imm->getKeys());
        self::assertSame($col->toArray(), $imm->toArray());
        self::assertSame($col->indexOf(3), $imm->indexOf(3));
        self::assertSame($col->isEmpty(), $imm->isEmpty());
        self::assertSame($col->slice(2, 2), $imm->slice(2, 2));
        self::assertSame($col->next(), $imm->current());
        self::assertSame($imm->next(), $col->current());
        self::assertSame($col->add('a'), $cln->add('a'));
        self::assertSame($col->remove(0), $cln->remove(0));
        self::assertSame($col->removeElement('a'), $cln->removeElement('a'));
    }

    /**
     * @expectedException \LogicException
     */
    public function testAdd(): void
    {
        $this->immutable_collection->add(0);
    }

    /**
     * @expectedException \LogicException
     */
    public function testClear(): void
    {
        $this->immutable_collection->clear();
    }

    /**
     * @expectedException \LogicException
     */
    public function testRemove(): void
    {
        $this->immutable_collection->remove(0);
    }

    /**
     * @expectedException \LogicException
     */
    public function testRemoveElement(): void
    {
        $this->immutable_collection->removeElement(0);
    }

    /**
     * @expectedException \LogicException
     */
    public function testSet(): void
    {
        $this->immutable_collection->set(0, 0);
    }

    /**
     * @expectedException \LogicException
     */
    public function testOffsetSet(): void
    {
        $this->immutable_collection[0] = 0;
    }

    /**
     * @expectedException \LogicException
     */
    public function testOffsetUnset(): void
    {
        unset($this->immutable_collection[0]);
    }

    public function testCloneAdd(): void
    {
        $this->clone_immutable_collection->add('an item');
        self::assertContains('an item', $this->clone_immutable_collection);
    }

    public function testCloneClear(): void
    {
        $this->clone_immutable_collection->clear();
        self::assertEmpty($this->clone_immutable_collection);
    }

    public function testCloneRemove(): void
    {
        $this->clone_immutable_collection->remove(0);
        self::assertArrayNotHasKey(0, $this->clone_immutable_collection);
        self::assertContains(1, $this->clone_immutable_collection);
    }

    public function testCloneRemoveElement(): void
    {
        // Remove fist element with value 1.
        $this->clone_immutable_collection->removeElement(1);
        // Make sure the second element with value 1 still exists.
        self::assertContains(1, $this->clone_immutable_collection);

        // Remove second element with value 1.
        $this->clone_immutable_collection->removeElement(1);
        // Make sure all elements with value 1 are gone.
        self::assertNotContains(1, $this->clone_immutable_collection);
    }

    public function testCloneSet(): void
    {
        $this->clone_immutable_collection->set(0, 0);
        self::assertEquals(0, $this->clone_immutable_collection[0]);
    }

    public function testCloneOffsetSet(): void
    {
        $this->clone_immutable_collection[0] = 0;
        self::assertEquals(0, $this->clone_immutable_collection[0]);
    }

    public function testCloneOffsetUnset(): void
    {
        unset($this->clone_immutable_collection[0]);
        self::assertArrayNotHasKey(0, $this->clone_immutable_collection);
    }

    public function testExists(): void
    {
        $exists = function ($key, $value) {
            return is_string($key) && is_numeric($value);
        };

        // Check that the function is wrapped correctly.
        $col = $this->collection->exists($exists);
        $imm = $this->immutable_collection->exists($exists);
        self::assertEquals($col, $imm);

        // Check that the closure is actually working.
        self::assertTrue($imm);
    }

    public function testFilter(): void
    {
        $filter = function ($value) {
            return is_string($value);
        };

        // Check that the function is wrapped correctly.
        $col = $this->collection->filter($filter);
        $imm = $this->immutable_collection->filter($filter);
        self::assertEquals($col, $imm);

        // Check that the closure is actually working.
        self::assertEquals(0, $imm->count());
    }

    public function testForAll(): void
    {
        $for_all = function ($key, $value) {
            return is_numeric($value) && is_numeric($key);
        };

        // Check that the function is wrapped correctly.
        $col = $this->collection->forAll($for_all);
        $imm = $this->immutable_collection->forAll($for_all);
        self::assertEquals($col, $imm);

        // Check that the closure is actually working.
        self::assertFalse($imm);
    }

    public function testMap(): void
    {
        $before = $this->immutable_collection->toArray();
        $map    = function ($value) {
            return $value << 1;
        };

        // Check that the function is wrapped correctly.
        self::assertEquals($this->collection->map($map), $this->immutable_collection->map($map));

        // Test that we got a copy and did not change the original collection.
        self::assertEquals($before, $this->immutable_collection->toArray());
    }

    public function testPartition(): void
    {
        $before    = $this->immutable_collection->toArray();
        $partition = function ($key, $value) {
            return $value % 2 && is_string($key);
        };

        // Check that the function is wrapped correctly.
        $col = $this->collection->partition($partition);
        $imm = $this->immutable_collection->partition($partition);
        self::assertEquals($col, $imm);

        // Test that we got a copy and did not change the original collection.
        self::assertEquals($before, $this->immutable_collection->toArray());
    }

    public function testMatching(): void
    {
        $criteria = (new Criteria());
        $before   = $this->immutable_collection->toArray();

        // Check that the function is wrapped correctly.
        $col = $this->collection->matching($criteria);
        $imm = $this->immutable_collection->matching($criteria);
        self::assertEquals($col, $imm);

        // Test that we got a copy and did not change the original collection.
        self::assertEquals($before, $this->immutable_collection->toArray());
    }
}
