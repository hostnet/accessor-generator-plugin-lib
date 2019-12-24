<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityNotFoundException;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Attribute;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Period;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /**
     * @var Product
     */
    private $product;

    protected function setUp(): void
    {
        $this->product = new Product();
    }

    public function testGetId(): void
    {
        $id = new \ReflectionProperty(Product::class, 'id');
        $id->setAccessible(true);
        $id->setValue($this->product, 10);

        self::assertEquals(10, $this->product->getId());
    }

    public function testGetIdDomain(): void
    {
        $id = new \ReflectionProperty(Product::class, 'id');
        $id->setAccessible(true);
        $id->setValue($this->product, PHP_INT_MAX . '0');

        $this->expectException(\DomainException::class);

        $this->product->getId();
    }

    public function testGetIdNew(): void
    {
        self::assertNull($this->product->getId());
    }

    public function testGetIdTooManyArguments(): void
    {
        $this->expectException(\BadMethodCallException::class);

        $this->product->getId(1);
    }

    public function testGetName(): void
    {
        $id = new \ReflectionProperty(Product::class, 'name');
        $id->setAccessible(true);
        $id->setValue($this->product, '10');

        self::assertEquals('10', $this->product->getName());
        self::assertTrue(is_string($this->product->getName()));
    }

    public function testGetNameNew(): void
    {
        $this->expectException(\LogicException::class);

        $this->product->getName();
    }

    public function testGetNameTooManyArguments(): void
    {
        $this->expectException(\BadMethodCallException::class);

        $this->product->getName(1);
    }

    public function testGetDescription(): void
    {
        $description = new \ReflectionProperty(Product::class, 'description');
        $description->setAccessible(true);
        $description->setValue($this->product, '10');

        self::assertEquals('10', $this->product->getDescription());
        self::assertTrue(is_string($this->product->getDescription()), 'of type string');
    }

    public function testGetDescriptionNew(): void
    {
        self::assertEquals('empty', $this->product->getDescription());
    }

    public function testGetDescriptionNewNull(): void
    {
        $property = new \ReflectionProperty($this->product, 'description');
        $property->setAccessible(true);
        $property->setValue($this->product, null);

        $this->expectException(\LogicException::class);

        $this->product->getDescription();
    }

    public function testGetDescriptionTooManyArguments(): void
    {
        $this->expectException(\BadMethodCallException::class);

        $this->product->getDescription(1);
    }

    public function testSetSystemName(): void
    {
        self::assertSame($this->product, $this->product->setSystemName(100));
        self::assertSame('100', $this->product->getSystemName());
        self::assertTrue(is_string($this->product->getSystemName()), 'of type string');
    }

    public function testSetSystemNameTooManyArguments(): void
    {
        $this->expectException(\BadMethodCallException::class);

        $this->product->setSystemName('', 2);
    }

    public function testSetSystemNameWrongType(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->product->setSystemName([]);
    }

    public function testSetSystemNameTooLong(): void
    {
        $this->expectException(\LengthException::class);

        $this->product->setSystemName(str_repeat('a', 51));
    }

    public function testGetSystemName(): void
    {
        self::assertEmpty($this->product->getSystemName());
        self::assertTrue(is_string($this->product->getSystemName()), 'of type string');
    }

    public function testGetSystemNameNewNull(): void
    {
        $property = new \ReflectionProperty($this->product, 'system_name');
        $property->setAccessible(true);
        $property->setValue($this->product, null);

        $this->expectException(\LogicException::class);

        $this->product->getSystemName();
    }

    public function testGetSystemNameTooManyArguments(): void
    {
        $this->expectException(\BadMethodCallException::class);

        $this->product->getSystemName(1);
    }

    public function testGetDuration(): void
    {
        $duration = new Period();
        $property = new \ReflectionProperty(Product::class, 'duration');
        $property->setAccessible(true);
        $property->setValue($this->product, $duration);

        self::assertSame($duration, $this->product->getDuration());
    }

    public function testGetDurationEmpty(): void
    {
        $this->expectException(EntityNotFoundException::class);

        $this->product->getDuration();
    }

    public function testGetDurationTooManyArguments(): void
    {
        $this->expectException(\BadMethodCallException::class);

        $this->product->getDuration(1);
    }

    public function testGetAttributes(): void
    {
        $attributes = $this->product->getAttributes();
        self::assertEmpty($attributes);
        self::assertInstanceOf(Collection::class, $attributes);
    }

    public function testGetAttributesTooManyArguments(): void
    {
        $this->expectException(\BadMethodCallException::class);

        $this->product->getAttributes(1);
    }

    /**
     * @depends testGetAttributes
     */
    public function testAddAttribute(): void
    {
        $attribute_a       = new Attribute();
        $attribute_a->name = 'A';
        $attribute_b       = new Attribute();
        $attribute_b->name = 'B';

        $this->product->addAttribute($attribute_a);
        $this->product->addAttribute($attribute_a);

        $attributes = $this->product->getAttributes();
        self::assertCount(1, $attributes);

        $this->product->addAttribute($attribute_b);
        self::assertCount(2, $attributes);

        self::assertSame($attribute_a, $attributes->first());
        self::assertSame($attribute_b, $attributes->last());

        self::assertSame($attribute_a, $attributes['A']);
        self::assertSame($attribute_b, $attributes['B']);
    }

    /**
     * @depends testGetAttributes
     * @throws \BadMethodCallException
     */
    public function testAddAttributeNonUniqueIndex(): void
    {
        $attribute_a       = new Attribute();
        $attribute_a->name = 'Same';
        $attribute_b       = new Attribute();
        $attribute_b->name = 'Same';

        $this->product->addAttribute($attribute_a);

        $this->expectException(\LogicException::class);

        $this->product->addAttribute($attribute_b);
    }

    public function testAddAttributesTooManyArguments(): void
    {
        $attribute = new Attribute();

        $this->expectException(\BadMethodCallException::class);

        $this->product->addAttribute($attribute, 2);
    }

    /**
     * @depends testAddAttribute
     * @depends testGetAttributes
     */
    public function testRemoveAttribute(): void
    {
        $attribute  = new Attribute();
        $attributes = $this->product->getAttributes();

        $this->product->addAttribute($attribute);
        self::assertCount(1, $attributes);

        $this->product->removeAttribute($attribute);
        $this->product->removeAttribute($attribute);
        self::assertCount(0, $attributes);
    }

    public function testRemoveAttributesTooManyArguments(): void
    {
        $attribute = new Attribute();

        $this->expectException(\BadMethodCallException::class);

        $this->product->removeAttribute($attribute, 2);
    }

    public function testAddAttributeToMultipleProducts(): void
    {
        $attribute = new Attribute();
        $product2  = new Product();

        $this->product->addAttribute($attribute);

        $this->expectException(\LogicException::class);

        $product2->addAttribute($attribute);
    }
}
