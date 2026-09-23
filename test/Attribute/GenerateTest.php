<?php
/**
 * @copyright 2026-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Attribute;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\AccessorGenerator\Attribute\Generate
 */
class GenerateTest extends TestCase
{
    public function testValidVisibilitiesAreAccepted(): void
    {
        $generate = new Generate(
            get: Generate::VISIBILITY_PUBLIC,
            set: Generate::VISIBILITY_PROTECTED,
            add: Generate::VISIBILITY_PRIVATE,
            remove: Generate::VISIBILITY_NONE,
            is: Generate::VISIBILITY_PUBLIC
        );

        self::assertSame(Generate::VISIBILITY_PUBLIC, $generate->getGet());
        self::assertSame(Generate::VISIBILITY_PROTECTED, $generate->getSet());
        self::assertSame(Generate::VISIBILITY_PRIVATE, $generate->getAdd());
        self::assertSame(Generate::VISIBILITY_NONE, $generate->getRemove());
        self::assertSame(Generate::VISIBILITY_PUBLIC, $generate->getIs());
    }

    public function testNullIsAcceptedForNullableVisibilities(): void
    {
        $generate = new Generate();

        self::assertNull($generate->getGet());
        self::assertNull($generate->getSet());
        self::assertNull($generate->getAdd());
        self::assertNull($generate->getRemove());
    }

    public function invalidVisibilityParamProvider(): array
    {
        return [
            ['get'],
            ['set'],
            ['add'],
            ['remove'],
            ['is'],
        ];
    }

    /**
     * @dataProvider invalidVisibilityParamProvider
     */
    public function testInvalidVisibilityThrows(string $param_name): void
    {
        $this->expectException(\DomainException::class);

        new Generate(...[$param_name => 'bogus']);
    }
}
