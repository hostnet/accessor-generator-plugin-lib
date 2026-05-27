<?php
/**
 * @copyright 2026-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Reflection;

use Hostnet\Component\AccessorGenerator\Attribute\Generate;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\AccessorGenerator\Reflection\AttributeInstantiator
 */
class AttributeInstantiatorTest extends TestCase
{
    public function testInstantiatesKnownAttribute(): void
    {
        $result = AttributeInstantiator::instantiate(
            "AG\\Generate(set: 'none')",
            ['AG' => 'Hostnet\Component\AccessorGenerator\Attribute']
        );

        self::assertCount(1, $result);
        self::assertInstanceOf(Generate::class, $result[0]);
        self::assertEquals('none', $result[0]->getSet());
    }

    public function testSkipsUnloadableAttributeClass(): void
    {
        $result = AttributeInstantiator::instantiate(
            'NonExistent\Attr',
            []
        );

        self::assertSame([], $result);
    }

    /**
     * A file-level `use DateTime;` (non-compound name) must not produce a PHP
     * warning that aborts the synthetic-file include. PHP warns because
     * `DateTime` lives in the global namespace and `use DateTime;` has no effect.
     */
    public function testNonCompoundUseStatementDoesNotError(): void
    {
        // Simulate a source file that has both a real import and a bare global
        // class import (`use DateTime;`), which is what triggers the bug.
        $use_statements = [
            'AG'       => 'Hostnet\Component\AccessorGenerator\Attribute',
            'DateTime' => 'DateTime',
        ];

        $result = AttributeInstantiator::instantiate("AG\\Generate(set: 'none')", $use_statements);

        self::assertCount(1, $result);
        self::assertInstanceOf(Generate::class, $result[0]);
    }

    public function testNonCompoundUseStatementWithoutAlias(): void
    {
        // Same scenario but stored without an alias key (numeric index).
        $use_statements = [
            'AG' => 'Hostnet\Component\AccessorGenerator\Attribute',
            0    => 'DateTime',
        ];

        $result = AttributeInstantiator::instantiate("AG\\Generate(set: 'none')", $use_statements);

        self::assertCount(1, $result);
        self::assertInstanceOf(Generate::class, $result[0]);
    }
}
