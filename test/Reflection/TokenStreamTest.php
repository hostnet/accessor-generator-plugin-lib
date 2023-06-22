<?php
/**
 * @copyright 2014-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Reflection;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\AccessorGenerator\Reflection\TokenStream
 */
class TokenStreamTest extends TestCase
{
    private const SOURCE     = 'tokens.php';
    private const PHP_8_SIZE = 105;

    /**
     * @var TokenStream
     */
    private $stream;

    protected function setUp(): void
    {
        $this->stream = new TokenStream(file_get_contents(__DIR__ . '/fixtures/' . self::SOURCE));
    }

    public function typeProvider(): array
    {
            return [
                [0, T_OPEN_TAG],
                [1, T_NAMESPACE],
                [4, ';'],
                [-1, null, \OutOfBoundsException::class],
                [self::PHP_8_SIZE, null, \OutOfBoundsException::class],
                [32, T_PRIVATE],
            ];
    }

    /**
     * @dataProvider typeProvider
     * @param string $source
     * @param int $loc
     * @param string $type
     * @param string $exception
     */
    public function testType($loc, $type, $exception = null): void
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
                [0, "<?php\n"],
                [1, 'namespace'],
                [3, 'Hostnet\Component\AccessorGenerator\Reflection'],
                [4, ';'],
                [-1, null, \OutOfBoundsException::class],
                [self::PHP_8_SIZE, null, \OutOfBoundsException::class],
                [32, 'private'],
            ];
    }

    /**
     * @dataProvider valueProvider
     * @param int $loc
     * @param string $value
     * @param string $exception
     */
    public function testValue($loc, $value, $exception = null): void
    {
        $exception && $this->expectException($exception);
        self::assertEquals($value, $this->stream->value($loc));
    }

    public function scanProvider(): array
    {
            return [
                // Boundary Checks
                [[], -2, null, \OutOfBoundsException::class],
                [[], -1, null],
                [[], 0, null],
                [[], self::PHP_8_SIZE, null, \OutOfBoundsException::class],
                [[], self::PHP_8_SIZE - 1, null],

                // Scan does not probe current value.
                [[T_OPEN_TAG], -1, 0],
                [[T_OPEN_TAG], 0, null],

                // Able to find last item
                [[T_WHITESPACE], self::PHP_8_SIZE - 2, self::PHP_8_SIZE - 1],

                // Test a token that is there to find
                [[T_PRIVATE], 0, 32],
            ];
    }

    /**
     * @dataProvider       scanProvider
     * @param int[]|char[] $tokens
     * @param int $input_loc
     * @param int|null $output_loc
     * @param string $exception
     */
    public function testScan(array $tokens, $input_loc, $output_loc, $exception = null): void
    {
        $exception && $this->expectException($exception);
        self::assertSame($output_loc, $this->stream->scan($input_loc, $tokens));
    }

    public function nextProvider(): array
    {
            return [
                // Boundary checks
                [-2, null, [], \OutOfBoundsException::class],
                [-1, 0, []],
                [0, 1, []],
                [self::PHP_8_SIZE, null, [], \OutOfBoundsException::class],
                [self::PHP_8_SIZE - 1, null, []],
                [self::PHP_8_SIZE - 2, self::PHP_8_SIZE - 1, []],

                // Scan from private keyword on line 9
                [32, 34], // Skip white space
                [32, 36, [T_WHITESPACE, T_CONST]],

                // Scan when no available maches can be found
                [self::PHP_8_SIZE - 2, null],
            ];
    }

    /**
     * @dataProvider       nextProvider
     * @param int $input_loc
     * @param int|null $output_loc
     * @param int[]|char[] $tokens
     * @param string $exception
     */
    public function testNext($input_loc, $output_loc, array $tokens = null, $exception = null): void
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
                [-1, null, [], \OutOfBoundsException::class],
                [0, null, []],
                [1, 0, []],
                [self::PHP_8_SIZE + 1, null, [], \OutOfBoundsException::class],
                [self::PHP_8_SIZE, self::PHP_8_SIZE - 1, []],
                [self::PHP_8_SIZE - 1, self::PHP_8_SIZE - 2, []],

                [34, 32], // Skip from const on line 9 to private on line 9
                [36, 32, [T_WHITESPACE, T_CONST]], // Skip from FOO on line 9 to private on line 9
                [31, 30, [T_PUBLIC, T_PRIVATE]], // Skip from 5 spaces on line 9 to { on line 8

                // Scan when no available maches can be found
                [3, null, [T_NAMESPACE, T_NS_SEPARATOR, T_WHITESPACE, T_STRING, T_OPEN_TAG]],

            ];
    }

    /**
     * @dataProvider       previousProvider
     * @param int $input_loc
     * @param int|null $output_loc
     * @param int[]|char[] $tokens
     * @param string $exception
     */
    public function testPrevious($input_loc, $output_loc, array $tokens = null, $exception = null): void
    {
        $exception && $this->expectException($exception);
        if ($tokens === null) {
            self::assertSame($output_loc, $this->stream->previous($input_loc));
        } else {
            self::assertSame($output_loc, $this->stream->previous($input_loc, $tokens));
        }
    }
}
