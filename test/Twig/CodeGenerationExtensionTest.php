<?php
namespace Hostnet\Component\AccessorGenerator\Twig;

use Doctrine\Tests\Common\Inflector\InflectorTest;

class CodeGenerationExtensionTest extends \PHPUnit_Framework_TestCase
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
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        // Call Parent constructor
        parent::__construct($name, $data, $dataName);

        // Create some very simple templates to test the filters
        $loader = new \Twig_Loader_Array([
            'classify'       => '{{ data | classify }}',
            'singularize'    => '{{ data | singularize }}',
            'perline_stars'  => " {% perline %}\n * {{data}} *\n{% endperline %}",
            'perline_indent' => "    {% perline %}\n    {{data}}\n    {% endperline %}",
        ]);

        $this->twig = new \Twig_Environment($loader);
        $this->twig->addExtension(new CodeGenerationExtension());
    }

    /**
     * Test if the name of the Twig extension is the nice one we thought of.
     */
    public function testGetName()
    {
        $cge = new CodeGenerationExtension();
        $this->assertEquals('Hostnet Twig Code Generation Extension', $cge->getName());
    }

    /**
     * Check that we register the PerLine token parser and no others.
     */
    public function testGetTokenParsers()
    {
        $cge     = new CodeGenerationExtension();
        $parsers = $cge->getTokenParsers();

        $this->assertTrue(count($parsers) == 1, 'One and only one parser given');
        $this->assertInstanceOf(PerLineTokenParser::class, current($parsers));
    }

    /**
     * Provide input and expected output values
     * ordered as an array of string tuples.
     * @return string[][]
     */
    public function classifyProvider()
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
    public function testClassify($input, $output)
    {
        $this->assertEquals($output, $this->twig->render('classify', ['data' => $input]));
    }

    /**
     * Provide input and expected output values
     * ordered as an array of string tuples.
     * @return string[][]
     */
    public function singularizeProvider()
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
            ['Media', 'Media'],
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
    public function testSingularize($singular, $plural)
    {
        $this->assertEquals($singular, $this->twig->render('singularize', ['data' => $plural]));
    }

    public function perLineProvider()
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
    public function testPerLine($template, $input, $output)
    {
        $this->assertEquals($output, $this->twig->render($template, ['data' => $input]));
    }
}
