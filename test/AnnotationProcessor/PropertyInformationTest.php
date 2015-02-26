<?php
namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

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
        $this->assertEquals('Hidde', $this->info->getDocumentation());
    }

    public function testGetMethods()
    {
        $this->assertEquals('test', $this->info->getName());
        $this->assertEquals(null, $this->info->getDefault());
        $this->assertEquals(null, $this->info->getType());
        $this->assertEquals('', $this->info->getFullyQualifiedType());
        $this->assertEquals(0, $this->info->getScale());
        $this->assertEquals(0, $this->info->getPrecision());
        $this->assertEquals(0, $this->info->getLength());
        $this->assertEquals(32, $this->info->getIntegerSize());
        $this->assertEquals('Test', $this->info->getClass());
        $this->assertEquals('', $this->minimal_info->getClass());
        $this->assertEquals('', $this->info->getNameSpace());

        $this->assertEquals(false, $this->info->isCollection());
        $this->assertEquals(false, $this->info->isFixedPointNumber());
        $this->assertEquals(false, $this->info->isNullable());
        $this->assertEquals(false, $this->info->isUnique());

        $this->assertEquals(false, $this->info->willGenerateAdd());
        $this->assertEquals(false, $this->info->willGenerateRemove());
        $this->assertEquals(false, $this->info->willGenerateSet());
        $this->assertEquals(false, $this->info->willGenerateGet());
        $this->assertEquals(true, $this->info->willGenerateStrict());
    }

    public function testBasicSetMethods()
    {
        $this->assertSame($this->info, $this->info->setCollection('garbage'));
        $this->assertEquals(true, $this->info->isCollection());

        $this->assertSame($this->info, $this->info->setFixedPointNumber('garbage'));
        $this->assertEquals(true, $this->info->isFixedPointNumber());

        $this->assertSame($this->info, $this->info->setNullable('garbage'));
        $this->assertEquals(true, $this->info->isNullable());

        $this->assertSame($this->info, $this->info->setUnique('garbage'));
        $this->assertEquals(true, $this->info->isUnique());

        $this->assertSame($this->info, $this->info->setGenerateAdd('garbage'));
        $this->assertEquals(true, $this->info->willGenerateAdd());

        $this->assertSame($this->info, $this->info->setGenerateRemove('garbage'));
        $this->assertEquals(true, $this->info->willGenerateRemove());

        $this->assertSame($this->info, $this->info->setGenerateGet('garbage'));
        $this->assertEquals(true, $this->info->willGenerateGet());

        $this->assertSame($this->info, $this->info->setGenerateSet('garbage'));
        $this->assertEquals(true, $this->info->willGenerateSet());

        $this->assertSame($this->info, $this->info->setGenerateStrict(false));
        $this->assertEquals(false, $this->info->willGenerateStrict());

        $this->assertSame($this->info, $this->info->setGenerateStrict('garbage'));
        $this->assertEquals(true, $this->info->willGenerateStrict());
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
        $this->setExpectedException($exception);
        $this->assertSame($this->info, $this->info->setReferencedProperty($referenced_property));
        $this->assertEquals($referenced_property, $this->info->getReferencedProperty());
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
        $this->setExpectedException($exception);
        $this->assertSame($this->info, $this->info->setIntegerSize($integer_size));
        $this->assertEquals($integer_size, $this->info->getIntegerSize());
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
        $this->setExpectedException($exception);
        $this->assertSame($this->info, $this->info->setScale($scale));
        $this->assertEquals($scale, $this->info->getScale());
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
        $this->setExpectedException($exception);
        $this->assertSame($this->info, $this->info->setPrecision($precision));
        $this->assertEquals($precision, $this->info->getPrecision());
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
        $this->setExpectedException($exception);
        $this->assertSame($this->info, $this->info->setLength($length));
        $this->assertEquals($length, $this->info->getLength());
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
        $this->setExpectedException($exception);
        $this->assertSame($this->info, $this->info->setType($type));
        if ($this->info->isComplexType()) {
            $this->assertEquals($type, $this->info->getTypeHint());
        }
        $this->assertEquals($type, $this->info->getType());
        $this->assertSame($this->info, $this->info->setType('string'));
        $this->assertEquals('string', $this->info->getType());
        $this->assertNotEquals('string', $this->info->getTypeHint());
    }

    /**
     * @dataProvider setTypeHintProvider
     * @param string $type
     * @param string $exception
     */
    public function testSetTypeHint($type, $exception)
    {
        $this->setExpectedException($exception);
        $this->assertSame($this->info, $this->info->setTypeHint($type));
        $this->assertEquals($type, $this->info->getTypeHint());
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
        $this->setExpectedException($exception);
        $this->assertSame($this->info, $this->info->setFullyQualifiedType($type));
        $this->assertEquals($type, $this->info->getFullyQualifiedType());
    }
}
