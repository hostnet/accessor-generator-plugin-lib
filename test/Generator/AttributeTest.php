<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Collections\Collection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Attribute;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Product;

class AttributeTest extends \PHPUnit_Framework_TestCase
{
    public function testSetProduct()
    {
        $attribute = new Attribute();
        $product   = new Product();

        $product->addAttribute($attribute);

        // Make private parts public.
        $property = new \ReflectionProperty($attribute, 'product');
        $property->setAccessible(true);

        $this->assertSame($product, $property->getValue($attribute));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetProductTooManyArguments()
    {
        $attribute = new Attribute();
        $product   = new Product();

        // Make private parts public.
        $method = new \ReflectionMethod($attribute, 'setProduct');
        $method->setAccessible(true);

        $method->invoke($attribute, $product, 2);
    }

    /**
     * @expectedException \LogicException
     */
    public function testSetProductMultipleArguments()
    {
        $attribute = new Attribute();
        $product_a = new Product();
        $product_b = new Product();

        $product_a->addAttribute($attribute);
        $product_b->addAttribute($attribute);
    }

    /**
     * @expectedException \LogicException
     */
    public function testSetProductNotInSet()
    {
        $attribute = new Attribute();
        $product   = new Product();

        // Make private parts public.
        $method = new \ReflectionMethod($attribute, 'setProduct');
        $method->setAccessible(true);

        $method->invoke($attribute, $product);
    }
}
