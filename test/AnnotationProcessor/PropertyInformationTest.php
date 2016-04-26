<?php
namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

use Hostnet\Component\AccessorGenerator\Annotation\Generate;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionClass;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionProperty;

/**
 * @covers Hostnet\Component\AccessorGenerator\AnnotationProcessor\PropertyInformation
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class PropertyInformationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PropertyInformation
     */
    private $info;

    /**
     * @var PropertyInformation
     */
    private $minimal_info;


    public function setUp()
    {
        $class = $this->getMockBuilder(ReflectionClass::class)->disableOriginalConstructor()->getMock();
        $class->expects($this->any())->method('getNamespace')->willReturn('');
        $class->expects($this->any())->method('getUseStatements')->willReturn([]);
        $class->expects($this->any())->method('getName')->willReturn('Test');

        $property = new ReflectionProperty(
            'test',
            ReflectionProperty::IS_PRIVATE,
            null,
            '/**
              * Hidde
              * @Hostnet\Component\AccessorGenerator\Annotation\Generate(get=false)
              */',
            $class
        );

        $this->info         = new PropertyInformation($property);
        $this->minimal_info = new PropertyInformation(new ReflectionProperty('test'));
    }

    public function testProcessAnnotations()
    {
        $processor = $this->getMock(AnnotationProcessorInterface::class);
        $processor->expects($this->atLeastOnce())->method('processAnnotation');

        $this->info->registerAnnotationProcessor($processor);
        $this->info->processAnnotations();
    }

    public function testGetDocumentation()
    {
        self::assertEquals('Hidde', $this->info->getDocumentation());
    }

    public function testGetMethods()
    {
        self::assertEquals('test', $this->info->getName());
        self::assertNull($this->info->getDefault());
        self::assertNull($this->info->getType());
        self::assertEquals('', $this->info->getFullyQualifiedType());
        self::assertEquals(0, $this->info->getScale());
        self::assertEquals(0, $this->info->getPrecision());
        self::assertEquals(0, $this->info->getLength());
        self::assertEquals(32, $this->info->getIntegerSize());
        self::assertEquals('Test', $this->info->getClass());
        self::assertEquals('', $this->minimal_info->getClass());
        self::assertEquals('', $this->info->getNamespace());

        self::assertFalse($this->info->isCollection());
        self::assertFalse($this->info->isFixedPointNumber());
        self::assertNull($this->info->isNullable());
        self::assertNull($this->info->isUnique());
        self::assertTrue($this->info->willGenerateStrict());
    }

    public function testBasicSetMethods()
    {
        self::assertSame($this->info, $this->info->setCollection('garbage'));
        self::assertTrue($this->info->isCollection());

        self::assertSame($this->info, $this->info->setFixedPointNumber('garbage'));
        self::assertTrue($this->info->isFixedPointNumber());

        self::assertSame($this->info, $this->info->setNullable('garbage'));
        self::assertTrue($this->info->isNullable());

        self::assertSame($this->info, $this->info->setUnique('garbage'));
        self::assertTrue($this->info->isUnique());

        self::assertFalse($this->info->willGenerateAdd());
        self::assertSame($this->info, $this->info->limitMaximumAddVisibility(Generate::VISIBILITY_PROTECTED));
        self::assertTrue($this->info->willGenerateAdd());
        self::assertSame($this->info, $this->info->limitMaximumAddVisibility(Generate::VISIBILITY_NONE));
        self::assertFalse($this->info->willGenerateAdd());

        self::assertFalse($this->info->willGenerateRemove());
        self::assertSame($this->info, $this->info->limitMaximumRemoveVisibility(Generate::VISIBILITY_PROTECTED));
        self::assertTrue($this->info->willGenerateRemove());
        self::assertSame($this->info, $this->info->limitMaximumRemoveVisibility(Generate::VISIBILITY_NONE));
        self::assertFalse($this->info->willGenerateRemove());

        self::assertFalse($this->info->willGenerateSet());
        self::assertSame($this->info, $this->info->limitMaximumSetVisibility(Generate::VISIBILITY_PUBLIC));
        self::assertTrue($this->info->willGenerateSet());
        self::assertSame($this->info, $this->info->limitMaximumSetVisibility(Generate::VISIBILITY_NONE));
        self::assertFalse($this->info->willGenerateSet());

        self::assertFalse($this->info->willGenerateGet());
        self::assertSame($this->info, $this->info->limitMaximumGetVisibility(Generate::VISIBILITY_PRIVATE));
        self::assertTrue($this->info->willGenerateGet());
        self::assertSame($this->info, $this->info->limitMaximumGetVisibility(Generate::VISIBILITY_NONE));
        self::assertFalse($this->info->willGenerateGet());

        self::assertSame($this->info, $this->info->setGenerateStrict(false));
        self::assertFalse($this->info->willGenerateStrict());

        self::assertSame($this->info, $this->info->setGenerateStrict('garbage'));
        self::assertTrue($this->info->willGenerateStrict());
    }

    public function setReferencedPropertyProvider()
    {
        return [
            [1,                      \InvalidArgumentException::class],
            [0b100,                  \InvalidArgumentException::class],
            [010,                    \InvalidArgumentException::class],
            [0x8,                    \InvalidArgumentException::class],
            [32,                     \InvalidArgumentException::class],
            [[],                     \InvalidArgumentException::class],
            [null,                   \InvalidArgumentException::class],
            [false,                  \InvalidArgumentException::class],
            [1.5,                    \InvalidArgumentException::class],
            ['16',                   \DomainException::class         ],
            ['\Object',              \DomainException::class         ],
            ['',                     null                            ],
            ['Object',               null                            ],
            ['property',             null                            ],
        ];
    }

    /**
     * @dataProvider setReferencedPropertyProvider
     * @param string $referenced_property
     * @param string $exception
     */
    public function testSetReferencedPropery($referenced_property, $exception)
    {
        $this->expectException($exception);
        self::assertSame($this->info, $this->info->setReferencedProperty($referenced_property));
        self::assertEquals($referenced_property, $this->info->getReferencedProperty());
    }


    public function setIntegerSizeProvider()
    {
        return [
            [-1,                     \RangeException::class          ],
            [0,                      \RangeException::class          ],
            [1,                      null                            ],
            [0b100,                  null                            ],
            [010,                    null                            ],
            [0x8,                    null                            ],
            [32,                     null                            ],
            [PHP_INT_SIZE << 3,      null                            ],
            [(PHP_INT_SIZE << 3)+ 1, \RangeException::class          ],
            [[],                     \InvalidArgumentException::class],
            ['',                     \InvalidArgumentException::class],
            [null,                   \InvalidArgumentException::class],
            [false,                  \InvalidArgumentException::class],
            ['16',                   \InvalidArgumentException::class],
            [1.5,                    \InvalidArgumentException::class],
        ];
    }

    /**
     * @dataProvider setIntegerSizeProvider
     * @param string $integer_size
     * @param string $exception
     */
    public function testSetIntegerSize($integer_size, $exception)
    {
        $this->expectException($exception);
        self::assertSame($this->info, $this->info->setIntegerSize($integer_size));
        self::assertEquals($integer_size, $this->info->getIntegerSize());
    }

    public function setScaleProvider()
    {
        //http://dev.mysql.com/doc/refman/5.0/en/precision-math-decimal-characteristics.html
        $max = 30;
        return [
            [-1,       \RangeException::class          ],
            [0,        null                            ],
            [0b1,      null                            ],
            [0x1,      null                            ],
            [01,       null                            ],
            [$max,     null                            ],
            [$max + 1, \RangeException::class          ],
            [[],       \InvalidArgumentException::class],
            ['10',     \InvalidArgumentException::class],
            ['10',     \InvalidArgumentException::class],
            [1.0,      \InvalidArgumentException::class],
        ];
    }

    /**
     * @dataProvider setScaleProvider
     * @param string $scale
     * @param string $exception
     */
    public function testSetScale($scale, $exception)
    {
        $this->expectException($exception);
        self::assertSame($this->info, $this->info->setScale($scale));
        self::assertEquals($scale, $this->info->getScale());
    }

    public function setPrecisionProvider()
    {
        //http://dev.mysql.com/doc/refman/5.0/en/precision-math-decimal-characteristics.html
        $max = 65;
        return [
            [-1,       \RangeException::class          ],
            [0,        null                            ],
            [0b1,      null                            ],
            [0x1,      null                            ],
            [01,       null                            ],
            [$max,     null                            ],
            [$max + 1, \RangeException::class          ],
            [[],       \InvalidArgumentException::class],
            ['10',     \InvalidArgumentException::class],
            ['10',     \InvalidArgumentException::class],
            [1.0,      \InvalidArgumentException::class],
        ];
    }

    /**
     * @dataProvider setPrecisionProvider
     * @param string $precision
     * @param string $exception
     */
    public function testSetPrecision($precision, $exception)
    {
        $this->expectException($exception);
        self::assertSame($this->info, $this->info->setPrecision($precision));
        self::assertEquals($precision, $this->info->getPrecision());
    }

    public function setLengthProvider()
    {
        return [
            [-1,           \RangeException::class          ],
            [0,            null                            ],
            [PHP_INT_SIZE, null                            ],
            [null,         \InvalidArgumentException::class],
            [1.0,          \InvalidArgumentException::class],
            ['10',         \InvalidArgumentException::class],
            [0xFF,         null                            ],
        ];
    }

    /**
     * @dataProvider setLengthProvider
     * @param string $length
     * @param string $exception
     */
    public function testSetLength($length, $exception)
    {
        $this->expectException($exception);
        self::assertSame($this->info, $this->info->setLength($length));
        self::assertEquals($length, $this->info->getLength());
    }

    public function setTypeProvider()
    {
        return array_merge([['integer', null]], $this->setTypeHintProvider());
    }

    public function setTypeHintProvider()
    {
        return [
            ['\\Test',  null                            ],
            ['Enum',    null                            ],
            ['enum',    \DomainException::class         ],
            ['10',      \DomainException::class         ],
            ['1A',      \DomainException::class         ],
            ['',        \DomainException::class         ],
            [['test'],  \InvalidArgumentException::class],
            [10,        \InvalidArgumentException::class],
        ];
    }

    /**
     * @dataProvider setTypeProvider
     * @param string $type
     * @param string $exception
     */
    public function testSetType($type, $exception)
    {
        $this->expectException($exception);
        self::assertSame($this->info, $this->info->setType($type));
        if ($this->info->isComplexType()) {
            self::assertEquals($type, $this->info->getTypeHint());
        }
        self::assertEquals($type, $this->info->getType());
        self::assertSame($this->info, $this->info->setType('string'));
        self::assertEquals('string', $this->info->getType());
        self::assertNotEquals('string', $this->info->getTypeHint());
    }

    /**
     * @dataProvider setTypeHintProvider
     * @param string $type
     * @param string $exception
     */
    public function testSetTypeHint($type, $exception)
    {
        $this->expectException($exception);
        self::assertSame($this->info, $this->info->setTypeHint($type));
        self::assertEquals($type, $this->info->getTypeHint());
    }

    public function setFullyQualifiedTypeProvider()
    {
        return [
            ['integer', \DomainException::class         ],
            ['\\Test',  null                            ],
            ['Enum',    \DomainException::class         ],
            ['enum',    \DomainException::class         ],
            ['10',      \DomainException::class         ],
            ['1A',      \DomainException::class         ],
            ['',        null                            ],
            [['test'],  \InvalidArgumentException::class],
            [10,        \InvalidArgumentException::class],
        ];
    }

    /**
     * @dataProvider setFullyQualifiedTypeProvider
     * @param string $type
     * @param string $exception
     */
    public function testSetFullyQualifiedType($type, $exception)
    {
        $this->expectException($exception);
        self::assertSame($this->info, $this->info->setFullyQualifiedType($type));
        self::assertEquals($type, $this->info->getFullyQualifiedType());
    }
}
