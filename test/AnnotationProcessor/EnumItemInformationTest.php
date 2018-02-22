<?php
namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

use Doctrine\Common\Util\Inflector;

class EnumItemInformationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * This is array.
     */
    public const A_TEST_CONSTANT = 'I_TEST_CONSTANT';

    /**
     * This is int.
     */
    public const I_TEST_CONSTANT = 'I_TEST_CONSTANT';

    /**
     * This is string.
     */
    public const S_TEST_CONSTANT = 'S_TEST_CONSTANT';

    /**
     * This is float.
     */
    public const F_TEST_CONSTANT = 'F_TEST_CONSTANT';

    /**
     * This is bool.
     */
    public const B_TEST_CONSTANT = 'B_TEST_CONSTANT';

    /**
     * This is a broken constant.
     */
    public const BROKEN_CONSTANT = 'BROKEN_CONSTANT';

    public function constantProvider()
    {
        return [
            [self::A_TEST_CONSTANT, 'int'],
            [self::S_TEST_CONSTANT, 'string'],
            [self::I_TEST_CONSTANT, 'int'],
            [self::F_TEST_CONSTANT, 'float'],
            [self::B_TEST_CONSTANT, 'bool']
        ];
    }

    /**
     * @dataProvider constantProvider
     *
     * @param string $name
     * @param string $type
     */
    public function testConstants($name, $type)
    {
        $reflector = new \ReflectionClass(EnumItemInformationTest::class);
        $constant  = $reflector->getReflectionConstant($name);
        $info      = new EnumItemInformation($constant);

        self::assertEquals($type, $info->getTypeHint());
        self::assertEquals('* This is ' . $type . '.', $info->getDocBlock());
        self::assertEquals($name, $info->getConstName());
        self::assertEquals(substr($name, 2), $info->getName());
        self::assertEquals(Inflector::classify(strtolower(substr($name, 2))), $info->getMethodName());
        self::assertEquals(EnumItemInformationTest::class, $info->getEnumClass());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The name of the constant "BROKEN_CONSTANT" is not prefixed with a valid type string
     */
    public function testBrokenConstant()
    {
        $reflector = new \ReflectionClass(EnumItemInformationTest::class);
        $constant  = $reflector->getReflectionConstant('BROKEN_CONSTANT');
        new EnumItemInformation($constant);
    }
}
