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
            'A',
            'A\A',
            'A\B',
            'B',
            'B\A',
        ];

        self::assertSame($sorted_set, UniqueImports::filter($sorted_set));
    }

    public function testFilterUnsortedSet(): void
    {
        self::assertSame(
            [
                'A',
                'A\A',
                'A\B',
                'B',
                'B\A',
            ],
            UniqueImports::filter(
                [
                    'B',
                    'A\A',
                    'B\A',
                    'A',
                    'A\B',
                ]
            )
        );
    }

    public function testFilterDuplicatesSet(): void
    {
        self::assertEquals(
            [
                'A',
                'A\A',
                'B',
            ],
            UniqueImports::filter(
                [
                    'A\A',
                    'B',
                    'B',
                    'A',
                    'A\A',
                    'B',
                ]
            )
        );
    }

    public function testFilterDuplicatesWithDifferentKeysSet(): void
    {
        self::assertSame(
            [
                0         => 'A',
                1         => 'A\A',
                'alias_a' => 'B',
                'alias_b' => 'B',
                2         => 'B',
            ],
            UniqueImports::filter(
                [
                    2         => 'B',
                    'alias_a' => 'B',
                    1         => 'A',
                    0         => 'A\A',
                    'alias_b' => 'B',
                ]
            )
        );
    }
}
