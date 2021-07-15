<?php
/**
 * @copyright 2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

use Doctrine\Common\Util\Inflector;
use Doctrine\Inflector\InflectorFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\AccessorGenerator\AnnotationProcessor\EnumItemInformation
 */
class EnumItemInformationTest extends TestCase
{
    /**
     * This is of type: array.
     */
    public const A_TEST_CONSTANT = 'I_TEST_CONSTANT';

    /**
     * This is of type: int.
     */
    public const I_TEST_CONSTANT = 'I_TEST_CONSTANT';

    /**
     * This is of type: string.
     */
    public const S_TEST_CONSTANT = 'S_TEST_CONSTANT';

    /**
     * This is of type: float.
     */
    public const F_TEST_CONSTANT = 'F_TEST_CONSTANT';

    /**
     * This is of type: bool.
     */
    public const B_TEST_CONSTANT = 'B_TEST_CONSTANT';

    /**
     * This is a broken constant.
     */
    public const BROKEN_CONSTANT = 'BROKEN_CONSTANT';

    public const S_CONSTANT_WITHOUT_DOCBLOCK = 'S_CONSTANT_WITHOUT_DOCBLOCK';

    private $inflector;

    public function setup(): void
    {
        $this->inflector = InflectorFactory::create()->build();
    }

    public function constantProvider(): array
    {
        return [
            ['* This is of type: int.', self::A_TEST_CONSTANT, 'int'],
            ['* This is of type: string.', self::S_TEST_CONSTANT, 'string'],
            ['* This is of type: int.', self::I_TEST_CONSTANT, 'int'],
            ['* This is of type: float.', self::F_TEST_CONSTANT, 'float'],
            ['* This is of type: bool.', self::B_TEST_CONSTANT, 'bool'],
            ['', self::S_CONSTANT_WITHOUT_DOCBLOCK, 'string'],
        ];
    }

    /**
     * @dataProvider constantProvider
     */
    public function testConstants(string $expected_doc_block_prefix, string $name, string $type): void
    {
        $reflector = new \ReflectionClass(self::class);
        $constant  = $reflector->getReflectionConstant($name);
        $info      = new EnumItemInformation($constant);

        self::assertEquals($type, $info->getTypeHint());
        self::assertEquals($expected_doc_block_prefix, $info->getDocBlock());
        self::assertEquals($name, $info->getConstName());
        self::assertEquals(substr($name, 2), $info->getName());
        self::assertEquals($this->inflector->classify(strtolower(substr($name, 2))), $info->getMethodName());
        self::assertEquals(self::class, $info->getEnumClass());
    }

    public function testBrokenConstant(): void
    {
        $reflector = new \ReflectionClass(self::class);
        $constant  = $reflector->getReflectionConstant('BROKEN_CONSTANT');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'The name of the constant "BROKEN_CONSTANT" is not prefixed with a valid type string'
        );

        new EnumItemInformation($constant);
    }
}
