<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\EntityNotFoundException;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Feature;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Nullable;

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
        $this->assertSame($this->nullable, $this->nullable->setDatetimeDefault(new \DateTime()));
        $this->assertSame($this->nullable, $this->nullable->setDatetimeDefault(null));

        $property = new \ReflectionProperty($this->nullable, 'datetime_default');
        $property->setAccessible(true);
        $this->assertSame(null, $property->getValue($this->nullable));
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
        $this->assertSame($this->nullable, $this->nullable->setDatetimeNullable(new \DateTime()));
        $this->assertSame($this->nullable, $this->nullable->setDatetimeNullable(null));

        $property = new \ReflectionProperty($this->nullable, 'datetime_nullable');
        $property->setAccessible(true);
        $this->assertSame(null, $property->getValue($this->nullable));
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
        $this->assertSame($this->nullable, $this->nullable->setDatetimeBoth(new \DateTime()));
        $this->assertSame($this->nullable, $this->nullable->setDatetimeBoth(null));

        $property = new \ReflectionProperty($this->nullable, 'datetime_both');
        $property->setAccessible(true);
        $this->assertSame(null, $property->getValue($this->nullable));
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
        $this->assertSame($this->nullable, $this->nullable->setFeature(new Feature()));
        $this->assertSame($this->nullable, $this->nullable->setFeature(null));

        $property = new \ReflectionProperty($this->nullable, 'feature');
        $property->setAccessible(true);
        $this->assertSame(null, $property->getValue($this->nullable));
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
        $this->assertSame($this->nullable, $this->nullable->setAnotherFeature(new Feature()));
        $this->assertSame($this->nullable, $this->nullable->setAnotherFeature(null));

        $property = new \ReflectionProperty($this->nullable, 'an_other_feature');
        $property->setAccessible(true);
        $this->assertSame(null, $property->getValue($this->nullable));
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
        $this->assertSame(2, $property->getValue($this->nullable));

        // Set null
        $this->assertSame($this->nullable, $this->nullable->setIntDifferent(null));
        $this->assertSame(null, $property->getValue($this->nullable));

        // Set default.
        $this->assertSame($this->nullable, $this->nullable->setIntDifferent());
        $this->assertSame(2, $property->getValue($this->nullable));
    }
}
