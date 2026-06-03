<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Annotation;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\AccessorGenerator\Attribute\Generate
 */
class GenerateTest extends TestCase
{
    public function testDefaults(): void
    {
        $generate = new Generate();
        $generate->setDefaultVisibility(Generate::VISIBILITY_PUBLIC);

        self::assertSame(Generate::VISIBILITY_PUBLIC, $generate->getGet());
        self::assertSame(Generate::VISIBILITY_PUBLIC, $generate->getSet());
        self::assertSame(Generate::VISIBILITY_PUBLIC, $generate->getAdd());
        self::assertSame(Generate::VISIBILITY_PUBLIC, $generate->getRemove());
        self::assertSame(Generate::VISIBILITY_PUBLIC, $generate->getIs());
        self::assertTrue($generate->isStrict());
        self::assertNull($generate->getType());
        self::assertNull($generate->getEncryptionAlias());
    }

    public function testTypeAndStrictnessAndEncryptionAlias(): void
    {
        $generate = new Generate(strict: false, type: \stdClass::class, encryption_alias: 'database.table.column');

        self::assertFalse($generate->isStrict());
        self::assertSame(\stdClass::class, $generate->getType());
        self::assertSame('database.table.column', $generate->getEncryptionAlias());
    }

    /**
     * @dataProvider newProvider
     */
    public function testNew($given, $expected): void
    {
        $generate = new Generate(get: $given, set: $given, add: $given, remove: $given, is: $given);

        self::assertSame($expected, $generate->getGet());
        self::assertSame($expected, $generate->getSet());
        self::assertSame($expected, $generate->getAdd());
        self::assertSame($expected, $generate->getRemove());
        self::assertSame($expected, $generate->getIs());
    }

    public function newProvider(): array
    {
        return [
            [Generate::VISIBILITY_PUBLIC, Generate::VISIBILITY_PUBLIC],
            [Generate::VISIBILITY_PROTECTED, Generate::VISIBILITY_PROTECTED],
            [Generate::VISIBILITY_PRIVATE, Generate::VISIBILITY_PRIVATE],
            [Generate::VISIBILITY_NONE, Generate::VISIBILITY_NONE],
        ];
    }

    /**
     * @dataProvider getMostLimitedVisibilityProvider
     */
    public function testGetMostLimitedVisibility($expected, array $input): void
    {
        self::assertSame($expected, Generate::getMostLimitedVisibility(... $input));
    }

    public function getMostLimitedVisibilityProvider(): array
    {
        return [[
            Generate::VISIBILITY_NONE,
            [Generate::VISIBILITY_PUBLIC, Generate::VISIBILITY_NONE],
        ], [
            Generate::VISIBILITY_PUBLIC,
            [],
        ], [
            Generate::VISIBILITY_PUBLIC,
            [Generate::VISIBILITY_PUBLIC],
        ], [
            Generate::VISIBILITY_PROTECTED,
            [Generate::VISIBILITY_PUBLIC, Generate::VISIBILITY_PROTECTED],
        ], [
            Generate::VISIBILITY_PRIVATE,
            [Generate::VISIBILITY_PUBLIC, Generate::VISIBILITY_PRIVATE],
        ],];
    }
}
