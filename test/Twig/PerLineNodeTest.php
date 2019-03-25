<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Twig;

use PHPUnit\Framework\TestCase;
use Twig\Compiler;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Node\Expression\NameExpression;
use Twig\Node\Node;
use Twig\Node\PrintNode;
use Twig\Node\TextNode;

/**
 * @covers \Hostnet\Component\AccessorGenerator\Twig\PerLineNode
 */
class PerLineNodeTest extends TestCase
{
    public function parseProvider()
    {
        $data = new PrintNode(new NameExpression('data', 1), 1);
        $text = new TextNode('TEXT', 1);

        return [
            [ new Node([$data]),               '  * ', '',           'prefix_data.php'],
            [ new Node([$data]),               '    ', '',           'indent_data.php'],
            [ new Node([$data]),               '    ', '// POSTFIX', 'indent_data_postfix.php'],
            [ new Node([$data]),               '  * ', '// POSTFIX', 'prefix_data_postfix.php'],
            [ new Node([$data]),               ''    , '// POSTFIX', 'data_postfix.php'],
            [ new Node([$data, $text, $data]), '  * ', '// POSTFIX', 'prefix_data_text_data_postfix.php'],
            [ new Node([$data]),               '', '',               'data.php'],
        ];
    }

    /**
     * @dataProvider parseProvider
     * @param Node $lines content nodes of the preline block
     * @param string $prefix prefix to be put before each line
     * @param string $postfix postfix to be put after each line
     * @param string $file filename of file inside of fixtures directory to
     *                     use as refernce output.
     */
    public function testParse(Node $lines, $prefix, $postfix, $file): void
    {
        $compiler = new Compiler(new Environment(new ArrayLoader()));
        $node     = new PerLineNode($lines, $prefix, $postfix, 1);
        $node->compile($compiler);

        self::assertStringEqualsFile(__DIR__ . '/fixtures/' . $file, $compiler->getSource());
    }
}
