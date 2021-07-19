<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

use Hostnet\Component\AccessorGenerator\Annotation\Generate;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionClass;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionProperty;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\AccessorGenerator\AnnotationProcessor\PropertyInformation
 */
class PropertyInformationTest extends TestCase
{
    /**
     * @var PropertyInformation
     */
    private $info;

    /**
     * @var PropertyInformation
     */
    private $minimal_info;

    protected function setUp(): void
    {
        $class = $this->getMockBuilder(ReflectionClass::class)->disableOriginalConstructor()->getMock();
        $class->expects(self::any())->method('getNamespace')->willReturn('');
        $class->expects(self::any())->method('getUseStatements')->willReturn([]);
        $class->expects(self::any())->method('getName')->willReturn('Test');

        $property = new ReflectionProperty(
            'test',
            \ReflectionProperty::IS_PRIVATE,
            null,
            file_get_contents(__DIR__ . '/fixtures/doc_block.txt'),
            $class
        );

        $this->info         = new PropertyInformation($property);
        $this->minimal_info = new PropertyInformation(new ReflectionProperty('test'));
    }

    public function testProcessAnnotations(): void
    {
        $processor = $this->createMock(AnnotationProcessorInterface::class);
        $processor->expects(self::atLeastOnce())->method('processAnnotation');

        /** @var AnnotationProcessorInterface $processor */
        $this->info->registerAnnotationProcessor($processor);
        $this->info->processAnnotations();
    }

    public function testGetDocumentation(): void
    {
        self::assertEquals('Hidde', $this->info->getDocumentation());
    }

    public function testGetMethods(): void
    {
        self::assertEquals('test', $this->info->getName());
        self::assertNull($this->info->getDefault());
        self::assertNull($this->info->getType());
        self::assertEquals('', $this->info->getFullyQualifiedType());
        self::assertNull($this->info->getEncryptionAlias());
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
        self::assertNull($this->info->getIndex());
        self::assertFalse($this->info->isReferencingCollection());
        self::assertNull($this->info->getGetVisibility());
        self::assertNull($this->info->getSetVisibility());
        self::assertNull($this->info->getAddVisibility());
        self::assertNull($this->info->getRemoveVisibility());
    }

    public function testBasicSetMethods(): void
    {
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

        self::assertSame($this->info, $this->info->setIndex('garbage'));
        self::assertSame('garbage', $this->info->getIndex());

        self::assertSame($this->info, $this->info->setReferencingCollection(true));
        self::assertTrue($this->info->isReferencingCollection());
    }

    public function setReferencedPropertyProvider(): array
    {
        return [
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
     * @throws \DomainException
     * @throws \InvalidArgumentException
     */
    public function testSetReferencedProperty($referenced_property, $exception): void
    {
        $exception && $this->expectException($exception);
        self::assertSame($this->info, $this->info->setReferencedProperty($referenced_property));
        self::assertEquals($referenced_property, $this->info->getReferencedProperty());
    }

    public function setIntegerSizeProvider(): array
    {
        return [
            [-1,                      \RangeException::class          ],
            [0,                       \RangeException::class          ],
            [1,                       null                            ],
            [0b100,                   null                            ],
            [010,                     null                            ],
            [0x8,                     null                            ],
            [32,                      null                            ],
            [PHP_INT_SIZE << 3,       null                            ],
            [(PHP_INT_SIZE << 3) + 1, \RangeException::class          ],
        ];
    }

    /**
     * @dataProvider setIntegerSizeProvider
     * @param string $integer_size
     * @param string $exception
     * @throws \RangeException
     */
    public function testSetIntegerSize($integer_size, $exception): void
    {
        $exception && $this->expectException($exception);
        self::assertSame($this->info, $this->info->setIntegerSize($integer_size));
        self::assertEquals($integer_size, $this->info->getIntegerSize());
    }

    public function setScaleProvider(): array
    {
        //http://dev.mysql.com/doc/refman/5.0/en/precision-math-decimal-characteristics.html
        $max = 30;
        return [
            [-1,       \RangeException::class          ],
            [0,        null                            ],
            [null,     null                            ],
            [0b1,      null                            ],
            [0x1,      null                            ],
            [01,       null                            ],
            [$max,     null                            ],
            [$max + 1, \RangeException::class          ],
        ];
    }

    /**
     * @dataProvider setScaleProvider
     * @param string $scale
     * @param string $exception
     * @throws \InvalidArgumentException
     * @throws \RangeException
     */
    public function testSetScale($scale, $exception): void
    {
        $exception && $this->expectException($exception);
        self::assertSame($this->info, $this->info->setScale($scale));
        self::assertEquals($scale, $this->info->getScale());
    }

    public function setPrecisionProvider(): iterable
    {
        //http://dev.mysql.com/doc/refman/5.0/en/precision-math-decimal-characteristics.html
        $max = 65;
        return [
            [-1,       \RangeException::class          ],
            [0,        null                            ],
            [null,     null                            ],
            [0b1,      null                            ],
            [0x1,      null                            ],
            [01,       null                            ],
            [$max,     null                            ],
            [$max + 1, \RangeException::class          ],
        ];
    }

    /**
     * @dataProvider setPrecisionProvider
     * @param mixed $precision
     * @param string $exception
     * @throws \RangeException
     */
    public function testSetPrecision($precision, $exception): void
    {
        $exception && $this->expectException($exception);
        self::assertSame($this->info, $this->info->setPrecision($precision));
        self::assertEquals($precision, $this->info->getPrecision());
    }

    public function setLengthProvider(): array
    {
        return [
            [-1,           \RangeException::class          ],
            [0,            null                            ],
            [PHP_INT_SIZE, null                            ],
            [0xFF,         null                            ],
        ];
    }

    /**
     * @dataProvider setLengthProvider
     * @param string $length
     * @param string $exception
     * @throws \InvalidArgumentException
     * @throws \RangeException
     */
    public function testSetLength($length, $exception): void
    {
        $exception && $this->expectException($exception);
        self::assertSame($this->info, $this->info->setLength($length));
        self::assertEquals($length, $this->info->getLength());
    }

    public function setTypeProvider(): iterable
    {
        return array_merge([['integer', null]], $this->setTypeHintProvider());
    }

    public function setTypeHintProvider(): array
    {
        return [
            ['\\Test',  null                            ],
            ['Enum',    null                            ],
            ['enum',    \DomainException::class         ],
            ['10',      \DomainException::class         ],
            ['1A',      \DomainException::class         ],
            ['',        \DomainException::class         ],
        ];
    }

    /**
     * @dataProvider setTypeProvider
     * @param string $type
     * @param string $exception
     * @throws \DomainException
     * @throws \InvalidArgumentException
     */
    public function testSetType($type, $exception): void
    {
        $exception && $this->expectException($exception);
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
     * @throws \DomainException
     * @throws \InvalidArgumentException
     */
    public function testSetTypeHint($type, $exception): void
    {
        $exception && $this->expectException($exception);
        self::assertSame($this->info, $this->info->setTypeHint($type));
        self::assertEquals($type, $this->info->getTypeHint());
    }

    public function setFullyQualifiedTypeProvider(): array
    {
        return [
            ['integer', \DomainException::class         ],
            ['\\Test',  null                            ],
            ['Enum',    \DomainException::class         ],
            ['enum',    \DomainException::class         ],
            ['10',      \DomainException::class         ],
            ['1A',      \DomainException::class         ],
            ['',        null                            ],
        ];
    }

    /**
     * @dataProvider setFullyQualifiedTypeProvider
     * @param string $type
     * @param string $exception
     * @throws \DomainException
     * @throws \InvalidArgumentException
     */
    public function testSetFullyQualifiedType($type, $exception): void
    {
        $exception && $this->expectException($exception);
        self::assertSame($this->info, $this->info->setFullyQualifiedType($type));
        self::assertEquals($type, $this->info->getFullyQualifiedType());
    }

    public function setEncryptionProvider(): array
    {
        return [
            ['alias',   null                           ],
        ];
    }

    /**
     * @dataProvider setEncryptionProvider
     * @param string $encryption
     * @param string $exception
     * @throws \InvalidArgumentException
     */
    public function testSetEncryption($encryption_alias, $exception): void
    {
        $exception && $this->expectException($exception);
        self::assertSame($this->info, $this->info->setEncryptionAlias($encryption_alias));

        self::assertEquals($encryption_alias, $this->info->getEncryptionAlias());
        self::assertSame($this->info, $this->info->setEncryptionAlias('string'));
        self::assertEquals('string', $this->info->getEncryptionAlias());
    }

    public function testGetNamespaceEmptyClass(): void
    {
        self::assertSame('', $this->minimal_info->getNamespace());
    }
}
