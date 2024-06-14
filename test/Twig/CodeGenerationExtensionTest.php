<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Twig;

use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Error\RuntimeError;
use Twig\Loader\ArrayLoader;

/**
 * @covers \Hostnet\Component\AccessorGenerator\Twig\CodeGenerationExtension
 */
class CodeGenerationExtensionTest extends TestCase
{
    private $twig;

    /**
     * Do class wide initalization and do NOT use setUp function because we do not
     * need a new TwigEnvironment for every function call.
     *
     * @param string $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $data_name = '')
    {
        // Call Parent constructor
        parent::__construct($name, $data, $data_name);

        // Create some very simple templates to test the filters
        $loader = new ArrayLoader([
            'classify'            => '{{ data | classify }}',
            'singularize'         => '{{ data | singularize }}',
            'twos_complement_min' => '{{ data | twos_complement_min }}',
            'twos_complement_max' => '{{ data | twos_complement_max }}',
            'perline_stars'       => " {% perline %}\n * {{data}} *\n{% endperline %}",
            'perline_indent'      => "    {% perline %}\n    {{data}}\n    {% endperline %}",
            'decimal_right_shift' => '{{ data | decimal_right_shift(amount) }}',
            'string'              => '{% if data is string %}true{% else %}false{% endif %}',
        ]);

        $this->twig = new Environment($loader);
        $this->twig->addExtension(new CodeGenerationExtension());
    }

    /**
     * Test if the name of the Twig extension is the nice one we thought of.
     */
    public function testGetName(): void
    {
        $cge = new CodeGenerationExtension();
        self::assertEquals('Hostnet Twig Code Generation Extension', $cge->getName());
    }

    /**
     * Check that we register the PerLine token parser and no others.
     */
    public function testGetTokenParsers(): void
    {
        $cge     = new CodeGenerationExtension();
        $parsers = $cge->getTokenParsers();

        self::assertCount(1, $parsers, 'One and only one parser given');
        self::assertInstanceOf(PerLineTokenParser::class, current($parsers));
    }

    /**
     * Provide input and expected output values
     * ordered as an array of string tuples.
     * @return string[][]
     */
    public function classifyProvider(): array
    {
        return [
            ['test_string', 'TestString'],
            ['testString', 'TestString'],
            ['test__string', 'TestString'],
            ['TEST', 'TEST'],
            ['TEST_STRING', 'TESTSTRING'],
            ['tEST_STRING', 'TESTSTRING'],
        ];
    }

    /**
     * @dataProvider classifyProvider
     */
    public function testClassify($input, $output): void
    {
        self::assertEquals($output, $this->twig->render('classify', ['data' => $input]));
    }

    public function twosComplementMaxProvider(): array
    {
        return [
            [                -10, null           , RuntimeError::class],
            [                  0, null           , RuntimeError::class],
            [                 16, 32767                               ],
            [  PHP_INT_SIZE << 3, PHP_INT_MAX                         ],
            [  PHP_INT_SIZE << 4, PHP_INT_MAX                         ],
        ];
    }

    /**
     * @dataProvider twosComplementMinProvider
     */
    public function testTwosComplementMin($input, $output, $exception = null): void
    {
        $exception && $this->expectException($exception);
        self::assertEquals($output, $this->twig->render('twos_complement_min', ['data' => $input]));
    }

    public function twosComplementMinProvider(): array
    {
        return [
            [                -10, null           , RuntimeError::class],
            [                  0, null           , RuntimeError::class],
            [                 16, -32768                              ],
            [  PHP_INT_SIZE << 3, -PHP_INT_MAX - 1                    ],
            [  PHP_INT_SIZE << 4, -PHP_INT_MAX - 1                    ],
        ];
    }

    /**
     * @dataProvider twosComplementMaxProvider
     */
    public function testTwosComplementMax($input, $output, $exception = null): void
    {
        $exception && $this->expectException($exception);
        self::assertEquals($output, $this->twig->render('twos_complement_max', ['data' => $input]));
    }

    /**
     * Provide input and expected output values
     * ordered as an array of string tuples.
     * @return string[][]
     */
    public function singularizeProvider(): array
    {
         return [
            ['categoria', 'categorias'],
            ['house', 'houses'],
            ['powerhouse', 'powerhouses'],
            ['Bus', 'Buses'],
            ['bus', 'buses'],
            ['menu', 'menus'],
            ['news', 'news'],
            ['food_menu', 'food_menus'],
            ['Menu', 'Menus'],
            ['FoodMenu', 'FoodMenus'],
            ['quiz', 'quizzes'],
            ['matrix_row', 'matrix_rows'],
            ['matrix', 'matrices'],
            ['vertex', 'vertices'],
            ['index', 'indices'],
            ['Alias', 'Aliases'],
            ['Medium', 'Media'],
            ['NodeMedia', 'NodeMedia'],
            ['alumnus', 'alumni'],
            ['bacillus', 'bacilli'],
            ['cactus', 'cacti'],
            ['focus', 'foci'],
            ['fungus', 'fungi'],
            ['nucleus', 'nuclei'],
            ['octopus', 'octopuses'],
            ['radius', 'radii'],
            ['stimulus', 'stimuli'],
            ['syllabus', 'syllabi'],
            ['terminus', 'termini'],
            ['virus', 'viri'],
            ['person', 'people'],
            ['glove', 'gloves'],
            ['crisis', 'crises'],
            ['tax', 'taxes'],
            ['wave', 'waves'],
            ['bureau', 'bureaus'],
            ['cafe', 'cafes'],
            ['roof', 'roofs'],
            ['foe', 'foes'],
            ['cookie', 'cookies'],
            ['', ''],
         ];
    }

    /**
     * @dataProvider singularizeProvider
     */
    public function testSingularize($singular, $plural): void
    {
        self::assertEquals($singular, $this->twig->render('singularize', ['data' => $plural]));
    }

    public function perLineProvider(): array
    {
        return [
            ['perline_stars', "Line 1\nLine 2\nLine 3", " * Line 1 *\n * Line 2 *\n * Line 3 *\n"],
            ['perline_stars', "Line 1\n\nLine 3", " * Line 1 *\n *  *\n * Line 3 *\n"],
            ['perline_indent', "Line 1\n\nLine 3", "    Line 1\n\n    Line 3\n"],
        ];
    }

    /**
     * @dataProvider perLineProvider
     */
    public function testPerLine($template, $input, $output): void
    {
        self::assertEquals($output, $this->twig->render($template, ['data' => $input]));
    }

    public function decimalRightShiftProvider(): array
    {
        return [
            [  10,  0, 10           ],
            [  10,  1,  1.0         ],
            [   1,  0,  1           ],
            [   1, 10,   .0000000001],
            [1000,  4,   .1         ],
            ['1.0', 2, '0.010'      ],
            [ 'a', null, null, RuntimeError::class],
            [ [],  null, null, RuntimeError::class],
            [ 1,   [],   null, RuntimeError::class],
        ];
    }

    /**
     * @dataProvider decimalRightShiftProvider
     */
    public function testDecimalRightShift($input, $amount, $output, $exception = null): void
    {
        $exception && $this->expectException($exception);
        self::assertEquals(
            $output,
            $this->twig->render('decimal_right_shift', ['data' => $input, 'amount' => $amount])
        );
    }

    /**
     * @dataProvider stringProvider
     */
    public function testString($input, $output): void
    {
        self::assertEquals($output, $this->twig->render('string', ['data' => $input]));
    }

    public function stringProvider(): array
    {
        return [
            [1, 'false'],
            ['ORM', 'true'],
        ];
    }
}
