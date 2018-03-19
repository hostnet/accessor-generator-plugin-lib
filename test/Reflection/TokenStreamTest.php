<?php
declare(strict_types=1);
/**
 * @copyright 2014-2018 Hostnet B.V.
 */

namespace Hostnet\Component\AccessorGenerator\Reflection;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\AccessorGenerator\Reflection\TokenStream
 */
class TokenStreamTest extends TestCase
{
    private const SOURCE = 'tokens.php';
    private const SIZE   = 116;

    /**
     * @var TokenStream
     */
    private $stream;

    public function setUp()
    {
        $this->stream = new TokenStream(file_get_contents(__DIR__ . '/fixtures/' . self::SOURCE));
    }

    public function typeProvider(): array
    {
        return [
            [         0, T_OPEN_TAG                                ],
            [         1, T_NAMESPACE                               ],
            [        10, ';'                                       ],
            [        -1, null,         \OutOfBoundsException::class],
            [self::SIZE, null,         \OutOfBoundsException::class],
            [        42, T_PRIVATE,                                ],
        ];
    }

    /**
     * @dataProvider typeProvider
     */
    public function testType(int $loc, ?string $type, string $exception = null): void
    {
        $exception && $this->expectException($exception);
        $output = $this->stream->type($loc);

        self::assertEquals(
            $type,
            $output,
            sprintf(
                'Type [%s => %s] does not match [%s => %s]',
                $output,
                is_long($output) ? token_name($output) : $output,
                $type,
                is_long($type) ? token_name($type) : $type
            )
        );
    }

    public function valueProvider(): array
    {
        return [
            [         0, "<?php\n"                                       ],
            [         1, 'namespace'                                     ],
            [         7, 'AccessorGenerator'                             ],
            [        10, ';'                                             ],
            [        -1, null,               \OutOfBoundsException::class],
            [self::SIZE, null,               \OutOfBoundsException::class],
            [        42, 'private',                                      ],
        ];
    }

    /**
     * @dataProvider valueProvider
     */
    public function testValue(int $loc, ?string $value, string $exception = null): void
    {
        $exception && $this->expectException($exception);
        self::assertEquals($value, $this->stream->value($loc));
    }

    public function scanProvider(): array
    {
        return [
            // Boundary Checks
            [[],            -2, null, \OutOfBoundsException::class],
            [[],            -1, null                              ],
            [[],             0, null                              ],
            [[],    self::SIZE, null, \OutOfBoundsException::class],
            [[], self::SIZE -1, null,                             ],

            // Scan does not probe current value.
            [[T_OPEN_TAG], -1, 0],
            [[T_OPEN_TAG], 0, null],

            // Able to find last item
            [[T_WHITESPACE], self::SIZE - 2, self::SIZE -1],

            // Test a token that is there to find
            [[T_PRIVATE], 0, 42],
        ];
    }

    /**
     * @dataProvider scanProvider
     */
    public function testScan(array $tokens, int $input_loc, ?int $output_loc, string $exception = null): void
    {
        $exception && $this->expectException($exception);
        self::assertSame($output_loc, $this->stream->scan($input_loc, $tokens));
    }

    public function nextProvider(): array
    {
        return [
            // Boundary checks
            [           -2,        null    , [], \OutOfBoundsException::class],
            [           -1,           0    , [],                             ],
            [            0,           1    , []                              ],
            [   self::SIZE,        null    , [], \OutOfBoundsException::class],
            [self::SIZE - 1,       null    , []                              ],
            [self::SIZE - 2, self::SIZE - 1, []                              ],

            // Scan from private keyword on line 9
            [42, 44], // Skip white space
            [42, 46, [T_WHITESPACE, T_CONST]],

            // Scan when no available maches can be found
            [self::SIZE - 2, null],
        ];
    }

    /**
     * @dataProvider nextProvider
     */
    public function testNext(int $input_loc, ?int $output_loc, array $tokens = null, string $exception = null): void
    {
        $exception && $this->expectException($exception);
        if ($tokens === null) {
            self::assertSame($output_loc, $this->stream->next($input_loc));
        } else {
            self::assertSame($output_loc, $this->stream->next($input_loc, $tokens));
        }
    }

    public function previousProvider(): array
    {
        return [
            // Boundary checks
            [            -1,          null, [], \OutOfBoundsException::class],
            [             0,          null, []                              ],
            [             1,             0, []                              ],
            [self::SIZE + 1,          null, [], \OutOfBoundsException::class],
            [    self::SIZE, self::SIZE -1, [],                             ],
            [self::SIZE - 1, self::SIZE -2, [],                             ],

            // Scan from private keyword on line 9
            [44, 42], // Skip white space
            [46, 42, [T_WHITESPACE, T_CONST]],
            [41, 40, [T_PUBLIC, T_PRIVATE]],

            // Scan when no available maches can be found
            [5, null, [T_NAMESPACE, T_NS_SEPARATOR, T_WHITESPACE, T_STRING, T_OPEN_TAG]],
        ];
    }

    /**
     * @dataProvider previousProvider
     */
    public function testPrevious(int $input_loc, ?int $output_loc, array $tokens = null, string $exception = null): void
    {
        $exception && $this->expectException($exception);
        if ($tokens === null) {
            self::assertSame($output_loc, $this->stream->previous($input_loc));
        } else {
            self::assertSame($output_loc, $this->stream->previous($input_loc, $tokens));
        }
    }
}
