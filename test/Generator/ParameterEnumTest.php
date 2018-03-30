<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Feature;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated\ParamNameEnum;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated\ParamsEnum;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Item;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Nullable;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\OneToOneNullable;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Parameterized;
use PHPUnit\Framework\TestCase;

class ParameterEnumTest extends TestCase
{
    /**
     * @var Parameterized
     */
    private $entity;

    public function setUp()
    {
        $this->entity = new Parameterized();
    }

    public function testGetter()
    {
        $obj = $this->entity->getParams();
        self::assertInstanceOf(ParamNameEnum::class, $obj);
        self::assertSame($obj, $this->entity->getParams());
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Parameter "A_SOME_ARRAY" does not exist or has never been initialized.
     */
    public function testGetterUndefinedParameter()
    {
        $this->entity->getParams()->getSomeArray();
    }

    public function methodsProvider()
    {
        return [
            ['SomeArray', [1, 2, 3], [4, 5, 6], 'Parameter "A_SOME_ARRAY" does not exist'],
            ['SomeString', 'Foo', 'Bar', 'Parameter "S_SOME_STRING" does not exist'],
            ['SomeInteger', 1, 2, 'Parameter "I_SOME_INTEGER" does not exist'],
            ['SomeFloat', 1.13, 3.14, 'Parameter "F_SOME_FLOAT" does not exist'],
            ['SomeBoolean', false, true, 'Parameter "B_SOME_BOOLEAN" does not exist'],
        ];
    }

    /**
     * @dataProvider methodsProvider
     *
     * @param string $name
     * @param mixed  $value1
     * @param mixed  $value2
     * @param string $exception_message_not_exists
     */
    public function testMethods($name, $value1, $value2, $exception_message_not_exists)
    {
        $get    = 'get' . $name;
        $set    = 'set' . $name;
        $has    = 'has' . $name;
        $clear  = 'clear' . $name;
        $remove = 'remove' . $name;
        $object = $this->entity->getParams();

        self::expectException(\LogicException::class);
        self::expectExceptionMessage($exception_message_not_exists);
        self::assertFalse($object->$has());
        $object->$set($value1);
        self::assertTrue($object->$has());
        self::assertEquals($value1, $object->$get());
        $object->$set($value2);
        self::assertEquals($value2, $object->$get());
        $object->$clear();
        self::assertFalse($object->$has());
        $object->$set($value1);
        self::assertTrue($object->$has());
        self::assertEquals($value1, $object->$get());
        $object->$remove();
        self::assertFalse($object->$has());
        $object->$set($value2);
        self::assertTrue($object->$has());
        self::assertEquals($value2, $object->$get());
        $object->$remove();
        $object->$get();
    }

    public function testArray()
    {
        self::assertFalse($this->entity->getParams()->hasSomeArray());

        $this->entity->getParams()->setSomeArray([1, 2, 3]);
        self::assertTrue($this->entity->getParams()->hasSomeArray());
        self::assertSame([1, 2, 3], $this->entity->getParams()->getSomeArray());

        $this->entity->getParams()->setSomeArray([4, 5, 6]);
        self::assertTrue($this->entity->getParams()->hasSomeArray());
        self::assertSame([4, 5, 6], $this->entity->getParams()->getSomeArray());

        $this->entity->getParams()->clearSomeArray();
        self::assertFalse($this->entity->getParams()->hasSomeArray());
        $this->entity->getParams()->setSomeArray([7, 8, 9]);
        self::assertSame([7, 8, 9], $this->entity->getParams()->getSomeArray());
    }
}
