<?php
/**
 * @copyright 2021-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\AccessorGenerator\Generator\UniqueImports
 */
class UniqueImportsTest extends TestCase
{
    public function testFilterEmptySet(): void
    {
        self::assertEmpty(UniqueImports::filter([]));
    }

    public function testFilterSortedSet(): void
    {
        $sorted_set = [
            'A\X',
            'A\X\A',
            'A\X\B',
            'B\X',
            'B\X\A',
        ];

        self::assertSame($sorted_set, UniqueImports::filter($sorted_set));
    }

    public function testFilterUnsortedSet(): void
    {
        self::assertSame(
            [
                'A\X',
                'A\X\A',
                'A\X\B',
                'B\X',
                'B\X\A',
            ],
            UniqueImports::filter(
                [
                    'B\X',
                    'A\X\A',
                    'B\X\A',
                    'A\X',
                    'A\X\B',
                ]
            )
        );
    }

    public function testFilterDuplicatesSet(): void
    {
        self::assertEquals(
            [
                'A\X',
                'A\X\A',
                'B\X',
            ],
            UniqueImports::filter(
                [
                    'A\X\A',
                    'B\X',
                    'B\X',
                    'A\X',
                    'A\X\A',
                    'B\X',
                ]
            )
        );
    }

    public function testFilterDuplicatesWithDifferentKeysSet(): void
    {
        self::assertSame(
            [
                0         => 'A\X',
                1         => 'A\X\A',
                'alias_a' => 'B\X',
                'alias_b' => 'B\X',
                2         => 'B\X',
            ],
            UniqueImports::filter(
                [
                    2         => 'B\X',
                    'alias_a' => 'B\X',
                    1         => 'A\X',
                    0         => 'A\X\A',
                    'alias_b' => 'B\X',
                ]
            )
        );
    }

    public function testFilterDropsNonCompoundClassNames(): void
    {
        // Non-compound class names (no backslash) produce `use DateTime;` which
        // PHP warns has no effect. They must be stripped from trait output.
        self::assertSame(
            ['A\X', 'B\X'],
            array_values(UniqueImports::filter(['DateTime', 'A\X', 'B\X']))
        );
    }

    public function testFilterKeepsFunctionAndConstImports(): void
    {
        // `use function sprintf;` and `use const PHP_EOL;` are intentional even
        // when non-compound and must not be removed.
        // After filtering and sorting the order is alphabetical.
        self::assertSame(
            ['A\X', 'const PHP_EOL', 'function sprintf'],
            array_values(UniqueImports::filter(['function sprintf', 'const PHP_EOL', 'A\X']))
        );
    }
}
