<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Feature;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Item;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Nullable;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\OneToOneNullable;

class NullableTest extends \PHPUnit_Framework_TestCase
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
    public function testSetDatetimeDefaultTooManyArguments()
    {
        $this->nullable->setDatetimeDefault(null, 1);
    }

    public function testSetDatetimeDefault()
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
    public function testSetDatetimeNullableTooManyArguments()
    {
        $this->nullable->setDatetimeNullable(null, 1);
    }

    public function testSetDatetimeNullable()
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
    public function testSetDatetimeBothTooManyArguments()
    {
        $this->nullable->setDatetimeBoth(new \DateTime(), 1);
    }

    public function testSetDatetimeBoth()
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
    public function testSetFeatureTooManyArguments()
    {
        $this->nullable->setFeature(new Feature(), 1);
    }

    public function testSetFeature()
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
    public function testSetAnOtherFeatureTooManyArguments()
    {
        $this->nullable->setAnotherFeature(new Feature(), 1);
    }

    public function testSetAnOtherFeature()
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
    public function testSetIntDifferentTooManyArguments()
    {
        $this->nullable->setIntDifferent(1, 2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetIntDifferentInvalidArgument()
    {
        $this->nullable->setIntDifferent([]);
    }

    /**
     * @expectedException \DomainException
     */
    public function testSetIntDifferentInvalidDomain()
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped('Only valid on a 64bit system');
        } else {
            $this->nullable->setIntDifferent(PHP_INT_MAX);
        }
    }

    public function testSetIntDifferent()
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
    public function testSetIntTooManyArguments()
    {
        $this->nullable->setInt(1, 2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetIntInvalidArgument()
    {
        $this->nullable->setInt([]);
    }

    /**
     * @expectedException \DomainException
     */
    public function testSetIntInvalidDomain()
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped('Only valid on a 64bit system');
        } else {
            $this->nullable->setInt(PHP_INT_MAX);
        }
    }

    public function testSetInt()
    {
        self::assertSame($this->nullable, $this->nullable->setInt(5));
        self::assertEquals(5, $this->nullable->getInt());

        self::assertSame($this->nullable, $this->nullable->setInt(null));
        self::assertEquals(null, $this->nullable->getInt());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetIntTooManyArguments()
    {
        $this->nullable->getInt([]);
    }

    /**
     * @expectedException \DomainException
     */
    public function testGetIntInvalidDomain()
    {
        $property = new \ReflectionProperty($this->nullable, 'int');
        $property->setAccessible(true);
        $property->setValue($this->nullable, PHP_INT_MAX * 2);
        $this->nullable->getInt();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetStringTooManyArguments()
    {
        $this->nullable->getString(1);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetStringTooManyArguments()
    {
        $this->nullable->setString(1, 2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetStringWrongType()
    {
        $this->nullable->setString([]);
    }

    public function testSetString()
    {
        self::assertSame($this->nullable, $this->nullable->setString(null));
        self::assertNull($this->nullable->getString());
        self::assertSame($this->nullable, $this->nullable->setString(''));
        self::assertSame('', $this->nullable->getString());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetOnlyOneTooManyArguments()
    {
        $this->nullable->getOnlyOne(1);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetOnlyOneTooManyArguments()
    {
        $this->nullable->setOnlyOne(new OneToOneNullable(), 2);
    }

    public function testSetOnlyOne()
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
    public function testGetUnidirectionalOneToOneTooManyArguments()
    {
        $this->nullable->getUnidirectionalOneToOne(1);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetUnidirectionalOneToOneTooManyArguments()
    {
        $this->nullable->setUnidirectionalOneToOne(new Item(), 2);
    }

    public function testUnidirectionalOneToOne()
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
