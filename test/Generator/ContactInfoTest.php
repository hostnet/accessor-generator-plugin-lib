<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\ContactInfo;
use PHPUnit\Framework\TestCase;

/**
 * {@inheritDoc}
 */
class ContactInfoTest extends TestCase
{
    public function testSetWrong(): void
    {
        $alice = new ContactInfo();
        foreach ([
                     'setAddressLine',
                     'setName',
                     'setDeleted',
                     'setSpendsLotsOfMoney',
                 ] as $modifier) {
            $caught = false;
            try {
                $alice->$modifier([]);
            } catch (\InvalidArgumentException $e) {
                $caught = true;
            }
            self::assertTrue($caught);
        }
    }

    public function testSet(): void
    {
        $alice = new ContactInfo();
        self::assertSame($alice, $alice->setAddressLine(''));
        self::assertSame($alice, $alice->setName(''));
        self::assertSame($alice, $alice->setDeleted(false));
        self::assertSame($alice, $alice->setSpendsLotsOfMoney(false));
        self::assertSame($alice, $alice->setFriendedBy($alice));
        self::assertSame($alice, $alice->setFriendedBy($alice));
        self::assertSame($alice, $alice->setReferrer($alice));
        self::assertSame($alice, $alice->setReferrer($alice));

        foreach (ContactInfo::GETTERS as $modifier) {
            self::assertNotNull($alice->$modifier());
        }
    }

    public function testRemove(): void
    {
        $alice = new ContactInfo();
        $bob   = new ContactInfo();

        foreach (ContactInfo::ADDERS as $modifier) {
            self::assertSame($alice, $alice->$modifier($bob));
        }

        foreach (ContactInfo::REMOVERS as $modifier) {
            self::assertSame($alice, $alice->$modifier($bob));
            self::assertSame($alice, $alice->$modifier($alice));
        }
    }

    public function testAddTwice(): void
    {
        $alice = new ContactInfo();
        $bob   = new ContactInfo();

        foreach (ContactInfo::ADDERS as $modifier) {
            $caught = false;
            try {
                $alice->$modifier($alice);
                $alice->$modifier($alice);
                $bob->$modifier($alice);
            } catch (\LogicException $e) {
                $caught = true;
            }
            self::assertTrue($caught);
        }
    }

    public function testGarbage(): void
    {
        $info = new ContactInfo();

        foreach (array_merge(
            ContactInfo::GETTERS,
            ContactInfo::ADDERS,
            ContactInfo::SETTERS,
            ContactInfo::REMOVERS
        ) as $modifier) {
            $caught = false;
            try {
                $info->$modifier($info, 2);
            } catch (\BadMethodCallException $e) {
                $caught = true;
            }
            self::assertTrue($caught);
        }
    }

    public function testGetAll(): void
    {
        $info = new ContactInfo();
        $all  = $info->getAll();
        self::assertTrue(is_array($all));
    }
}
