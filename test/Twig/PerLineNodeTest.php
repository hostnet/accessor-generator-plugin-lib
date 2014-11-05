<?php
namespace Hostnet\Component\AccessorGenerator\Twig;

use Doctrine\Tests\Common\Inflector\InflectorTest;
use Twig_Node as Node;
use Twig_Node_Expression_Name as Name;
use Twig_Node_Print as PrintNode;
use Twig_Node_Text as TextNode;
use Twig_Token as Token;

/**
* @covers Hostnet\Component\AccessorGenerator\Twig\PerLineNode
* @author Hidde Boomsma <hboomsma@hostnet.nl>
*/
class PerLineNodeTest extends \PHPUnit_Framework_TestCase
{
    public function parseProvider()
    {
        $data = new PrintNode(new Name('data', 1), 1);
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
     *
     * @dataProvider parseProvider
     * @param Node $lines content nodes of the preline block
     * @param string $prefix prefix to be put before each line
     * @param string $postifx postfix to be put after each line
     * @param string $file filename of file inside of fixtures directory to
     *                     use as refernce output.
     */
    public function testParse(Node $lines, $prefix, $postfix, $file)
    {
        $compiler = new \Twig_Compiler(new \Twig_Environment());
        $node     = new PerLineNode($lines, $prefix, $postfix, 1);
        $node->compile($compiler);

        $this->assertEquals(file_get_contents(__DIR__ . '/fixtures/' . $file), $compiler->getSource());
    }
}
