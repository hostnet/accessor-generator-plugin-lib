<?php
declare(strict_types=1);
/**
 * @copyright 2014-2018 Hostnet B.V.
 */

namespace Hostnet\Component\AccessorGenerator\Twig;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\AccessorGenerator\Twig\PerLineTokenParser
 */
class PerLineTokenParserTest extends TestCase
{
    /**
     * Our class should parse {% perline %} tags
     */
    public function testGetTag(): void
    {
        self::assertEquals('perline', (new PerLineTokenParser())->getTag());
    }

    public function parseProvider(): array
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
     * Flattens all \Twig_nodes in a tree.
     *
     * @param \Twig_Node $node
     * @return \Generator
     */
    private function iterateAllNodes(\Twig_Node $node): ?\Generator
    {
        yield $node;

        foreach ($node as $child) {
            yield from $this->iterateAllNodes($child);
        }
    }

    /**
     * Test if we parse the content between a {% perline %} and {% endperline %}
     * tag into a valis PerLineNode with its own child nodes
     *
     * @dataProvider parseProvider
     *
     * @param string     $template template contents to tokeninze (input)
     * @param \Twig_Node $lines expected internal nodes to be returned (output)
     * @param string     $prefix expected prefix (output)
     * @param string     $postfix expected postfix (output)
     *
     * @throws \Twig_Error_Syntax
     */
    public function testParse($template, \Twig_Node $lines, $prefix, $postfix): void
    {
        // Setup a token stream and feed it into our token parser.
        $twig = new TestEnvironment(new CodeGenerationExtension());

        $stream = $twig->parse($twig->tokenize(new \Twig_Source($template, 'found')));

        $per_line = null;
        foreach ($this->iterateAllNodes($stream) as $node) {
            if ($node instanceof \Twig_Node && $node->getNodeTag() === 'perline') {
                $per_line = $node;
                break;
            }
        }

        // check if we get a valid node back (of type PerLineNode)
        self::assertInstanceOf(PerLineNode::class, $per_line);

        // Check the contents of what we parsed.
        self::assertEquals($prefix, $per_line->getAttribute('prefix'));
        self::assertEquals($postfix, $per_line->getAttribute('postfix'));
        self::assertEquals($lines->__toString(), $per_line->getNode('lines')->__toString());
    }
}
