<?php
namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

use Hostnet\Component\AccessorGenerator\Reflection\ReflectionProperty;

/**
 * @covers Hostnet\Component\AccessorGenerator\PropertyInformation
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class PropertyInformationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PropertyInformation
     */
    private $info;

    public function setUp()
    {
        $property   = new ReflectionProperty(
            'test',
            ReflectionProperty::IS_PRIVATE,
            null,
            '/**
              * Hidde
              * @Hostnet\Component\AccessorGenerator\Annotation\Generate(get=false)
              */'
        );
        $this->info = new PropertyInformation($property);
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
        $this->assertEquals('string', $this->info->getType());
        $this->assertEquals(0, $this->info->getScale());
        $this->assertEquals(0, $this->info->getPrecision());
        $this->assertEquals(0, $this->info->getLength());
        $this->assertEquals(32, $this->info->getIntegerSize());

        $this->assertEquals(false, $this->info->isCollection());
        $this->assertEquals(false, $this->info->isFixedPointNumber());
        $this->assertEquals(false, $this->info->isNullable());
        $this->assertEquals(false, $this->info->isUnique());

        $this->assertEquals(false, $this->info->willGenerateAdd());
        $this->assertEquals(false, $this->info->willGenerateRemove());
        $this->assertEquals(false, $this->info->willGenerateSet());
        $this->assertEquals(false, $this->info->willGenerateGet());
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
        // Determine max precision for system arch.
        $max = PropertyInformation::numberOfSignificantDecimalDigitsFloat();
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
        // Determine max precision for system arch.
        $max = PropertyInformation::numberOfSignificantDecimalDigitsFloat();
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
        return [
            ['integer', null],
            ['\\Test',  null],
            ['enum',    \DomainException::class],
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
        $this->assertEquals($type, $this->info->getType());
    }
}
