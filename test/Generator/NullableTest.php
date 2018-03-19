<?php
declare(strict_types=1);
/**
 * @copyright 2014-2018 Hostnet B.V.
 */

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Feature;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Item;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Nullable;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\OneToOneNullable;
use PHPUnit\Framework\TestCase;

class NullableTest extends TestCase
{

    /**
     * @var Nullable
     */
    private $nullable;

    public function setUp()
    {
        $this->nullable = new Nullable();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetDatetimeDefaultTooManyArguments(): void
    {
        $this->nullable->setDatetimeDefault(null, 1);
    }

    public function testSetDatetimeDefault(): void
    {
        self::assertSame($this->nullable, $this->nullable->setDatetimeDefault(new \DateTime()));
        self::assertSame($this->nullable, $this->nullable->setDatetimeDefault(null));

        $property = new \ReflectionProperty($this->nullable, 'datetime_default');
        $property->setAccessible(true);
        self::assertSame(null, $property->getValue($this->nullable));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetDatetimeNullableTooManyArguments(): void
    {
        $this->nullable->setDatetimeNullable(null, 1);
    }

    public function testSetDatetimeNullable(): void
    {
        self::assertSame($this->nullable, $this->nullable->setDatetimeNullable(new \DateTime()));
        self::assertSame($this->nullable, $this->nullable->setDatetimeNullable(null));

        $property = new \ReflectionProperty($this->nullable, 'datetime_nullable');
        $property->setAccessible(true);
        self::assertSame(null, $property->getValue($this->nullable));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetDatetimeBothTooManyArguments(): void
    {
        $this->nullable->setDatetimeBoth(new \DateTime(), 1);
    }

    public function testSetDatetimeBoth(): void
    {
        self::assertSame($this->nullable, $this->nullable->setDatetimeBoth(new \DateTime()));
        self::assertSame($this->nullable, $this->nullable->setDatetimeBoth(null));

        $property = new \ReflectionProperty($this->nullable, 'datetime_both');
        $property->setAccessible(true);
        self::assertSame(null, $property->getValue($this->nullable));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetFeatureTooManyArguments(): void
    {
        $this->nullable->setFeature(new Feature(), 1);
    }

    public function testSetFeature(): void
    {
        self::assertSame($this->nullable, $this->nullable->setFeature(new Feature()));
        self::assertSame($this->nullable, $this->nullable->setFeature(null));

        $property = new \ReflectionProperty($this->nullable, 'feature');
        $property->setAccessible(true);
        self::assertSame(null, $property->getValue($this->nullable));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetAnOtherFeatureTooManyArguments(): void
    {
        $this->nullable->setAnotherFeature(new Feature(), 1);
    }

    public function testSetAnOtherFeature(): void
    {
        self::assertSame($this->nullable, $this->nullable->setAnotherFeature(new Feature()));
        self::assertSame($this->nullable, $this->nullable->setAnotherFeature(null));

        $property = new \ReflectionProperty($this->nullable, 'an_other_feature');
        $property->setAccessible(true);
        self::assertNull($property->getValue($this->nullable));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetIntDifferentTooManyArguments(): void
    {
        $this->nullable->setIntDifferent(1, 2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetIntDifferentInvalidArgument(): void
    {
        $this->nullable->setIntDifferent([]);
    }

    /**
     * @expectedException \DomainException
     */
    public function testSetIntDifferentInvalidDomain(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped('Only valid on a 64bit system');
        } else {
            $this->nullable->setIntDifferent(PHP_INT_MAX);
        }
    }

    public function testSetIntDifferent(): void
    {
        $property = new \ReflectionProperty($this->nullable, 'int_different');
        $property->setAccessible(true);

        // Check default value.
        self::assertSame(2, $property->getValue($this->nullable));

        // Set null
        self::assertSame($this->nullable, $this->nullable->setIntDifferent(null));
        self::assertSame(null, $property->getValue($this->nullable));

        // Set default.
        self::assertSame($this->nullable, $this->nullable->setIntDifferent());
        self::assertSame(2, $property->getValue($this->nullable));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetIntTooManyArguments(): void
    {
        $this->nullable->setInt(1, 2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetIntInvalidArgument(): void
    {
        $this->nullable->setInt([]);
    }

    /**
     * @expectedException \DomainException
     */
    public function testSetIntInvalidDomain(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped('Only valid on a 64bit system');
        } else {
            $this->nullable->setInt(PHP_INT_MAX);
        }
    }

    public function testSetInt(): void
    {
        self::assertSame($this->nullable, $this->nullable->setInt(5));
        self::assertEquals(5, $this->nullable->getInt());

        self::assertSame($this->nullable, $this->nullable->setInt(null));
        self::assertEquals(null, $this->nullable->getInt());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetIntTooManyArguments(): void
    {
        $this->nullable->getInt([]);
    }

    /**
     * @expectedException \DomainException
     */
    public function testGetIntInvalidDomain(): void
    {
        $property = new \ReflectionProperty($this->nullable, 'int');
        $property->setAccessible(true);
        $property->setValue($this->nullable, PHP_INT_MAX * 2);
        $this->nullable->getInt();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetStringTooManyArguments(): void
    {
        $this->nullable->getString(1);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetStringTooManyArguments(): void
    {
        $this->nullable->setString(1, 2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetStringWrongType(): void
    {
        $this->nullable->setString([]);
    }

    public function testSetString(): void
    {
        self::assertSame($this->nullable, $this->nullable->setString(null));
        self::assertNull($this->nullable->getString());
        self::assertSame($this->nullable, $this->nullable->setString(''));
        self::assertSame('', $this->nullable->getString());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetOnlyOneTooManyArguments(): void
    {
        $this->nullable->getOnlyOne(1);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetOnlyOneTooManyArguments(): void
    {
        $this->nullable->setOnlyOne(new OneToOneNullable(), 2);
    }

    public function testSetOnlyOne(): void
    {
        $one_to_one_a = new OneToOneNullable();
        $one_to_one_b = new OneToOneNullable();
        $property     = new \ReflectionProperty(OneToOneNullable::class, 'one_only');
        $property->setAccessible(true);

        // Every thing should be null at start.
        self::assertNull($this->nullable->getOnlyOne());
        self::assertNull($property->getValue($one_to_one_a));
        self::assertNull($property->getValue($one_to_one_b));

        // Set one_to_one a, check b is still null and that the roundtrip works.
        self::assertSame($this->nullable, $this->nullable->setOnlyOne($one_to_one_a));
        self::assertSame($one_to_one_a, $this->nullable->getOnlyOne());
        self::assertSame($this->nullable, $property->getValue($one_to_one_a));
        self::assertNull($property->getValue($one_to_one_b));

        // Set one_to_one b, check a is null again and that the roundtrip works.
        self::assertSame($this->nullable, $this->nullable->setOnlyOne($one_to_one_b));
        self::assertSame($one_to_one_b, $this->nullable->getOnlyOne());
        self::assertNull($property->getValue($one_to_one_a));
        self::assertSame($this->nullable, $property->getValue($one_to_one_b));

        // Unset everty thing and verify everything is set to null again.
        self::assertSame($this->nullable, $this->nullable->setOnlyOne(null));
        self::assertNull($this->nullable->getOnlyOne());
        self::assertNull($property->getValue($one_to_one_a));
        self::assertNull($property->getValue($one_to_one_b));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetUnidirectionalOneToOneTooManyArguments(): void
    {
        $this->nullable->getUnidirectionalOneToOne(1);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetUnidirectionalOneToOneTooManyArguments(): void
    {
        $this->nullable->setUnidirectionalOneToOne(new Item(), 2);
    }

    public function testUnidirectionalOneToOne(): void
    {
        $item = new Item();

        // Default situation returns null.
        self::assertNull($this->nullable->getUnidirectionalOneToOne());

        // Set a value and retrieve it again.
        self::assertSame($this->nullable, $this->nullable->setUnidirectionalOneToOne($item));
        self::assertSame($item, $this->nullable->getUnidirectionalOneToOne());

        // Unset a value and verify it is gone.
        self::assertSame($this->nullable, $this->nullable->setUnidirectionalOneToOne(null));
        self::assertNull($this->nullable->getUnidirectionalOneToOne());
    }
}
