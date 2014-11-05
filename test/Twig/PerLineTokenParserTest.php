<?php
namespace Hostnet\Component\AccessorGenerator\Twig;

use Doctrine\Tests\Common\Inflector\InflectorTest;
use Twig_Token as Token;

/**
 * @covers Hostnet\Component\AccessorGenerator\Twig\PerLineTokenParser
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class PerLineTokenParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Our class should parse {% perline %} tags
     */
    public function testGetTag()
    {
        $this->assertEquals('perline', (new PerLineTokenParser())->getTag());
    }

    public function parseProvider()
    {
        $simple_lines  = new \Twig_Node_Print(new \Twig_Node_Expression_Name('data', 1), 1);
        $complex_lines = new \Twig_Node([
            new \Twig_Node_Print(new \Twig_Node_Expression_Name('data', 1), 1),
            new \Twig_Node_Text('/* infix */', 1),
            new \Twig_Node_Print(new \Twig_Node_Expression_Name('data', 1), 1),
        ]);

        return [
            ["{% perline %}\nCONTENTS\n{% endperline %}", new \Twig_Node_Text('CONTENTS', 2), '', ''],
            ["  {% perline %}\n  * {{ data }} //POST\n  {% endperline %}", $simple_lines, '  * ', ' //POST'],
            ["{% perline %}\n{{ data }} //POST\n{% endperline %}", $simple_lines, '', ' //POST'],
            ["  {% perline %}\n  {{ data }}/* infix */{{ data }}{% endperline %}", $complex_lines, '  ', ''],
        ];
    }

    /**
     * Test if we parse the content between a {% perline %} and {% endperline %}
     * tag into a valis PerLineNode with its own child nodes
     *
     * @dataProvider parseProvider
     * @param string $template template contents to tokeninze (input)
     * @param \Twig_Node $lines expected internal nodes to be returned (output)
     * @param string $prefix expected prefix (output)
     * @param string $postfix expected postfix (output)
     */
    public function testParse($template, \Twig_Node $lines, $prefix, $postfix)
    {
        // Setup a token stream and feed it into our token parser.
        $twig         = new \Twig_Environment();
        $stream       = $twig->tokenize($template, 'found');
        $token_parser = new PerLineTokenParser();
        $twig_parser  = new TestParser($twig);

        // Token parsers read the stream form their internal Twig parsers,
        // here its feeded into the twig parser, and the Twig parser is feeded,
        // into the token parser.
        $twig_parser->setStream($stream);
        $token_parser->setParser($twig_parser);

        // Align the stream and pick the right token as start point.
        while (!$stream->getCurrent()->test('perline')) {
            $stream->next();
        }
        $node = $token_parser->parse($stream->next());

        // check if we get a valid node back (of type PerLineNode)
        $this->assertInstanceOf(PerLineNode::class, $node);

        // Check the contents of what we parsed.
        if ($node instanceof PerLineNode) {
            $this->assertEquals($prefix, $node->getAttribute('prefix'));
            $this->assertEquals($postfix, $node->getAttribute('postfix'));
            $this->assertEquals($lines->__toString(), $node->getNode('lines')->__toString());
        }
    }
}
