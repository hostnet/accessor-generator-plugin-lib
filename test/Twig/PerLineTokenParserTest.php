<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Twig;

use PHPUnit\Framework\TestCase;
use Twig\Node\Expression\NameExpression;
use Twig\Node\Node;
use Twig\Node\PrintNode;
use Twig\Node\TextNode;
use Twig\Source;

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

    public function parseProvider()
    {
        $simple_lines  = new PrintNode(new NameExpression('data', 1), 1);
        $complex_lines = new Node([
            new PrintNode(new NameExpression('data', 1), 1),
            new TextNode('/* infix */', 1),
            new PrintNode(new NameExpression('data', 1), 1),
        ]);

        return [
            ["{% perline %}\nCONTENTS\n{% endperline %}", new TextNode('CONTENTS', 2), '', ''],
            ["  {% perline %}\n  * {{ data }} //POST\n  {% endperline %}", $simple_lines, '  * ', ' //POST'],
            ["{% perline %}\n{{ data }} //POST\n{% endperline %}", $simple_lines, '', ' //POST'],
            ["  {% perline %}\n  {{ data }}/* infix */{{ data }}{% endperline %}", $complex_lines, '  ', ''],
        ];
    }

    /**
     * Flattens all Nodes in a tree.
     *
     * @param Node $node
     *
     * @return \Generator
     */
    private function iterateAllNodes(Node $node)
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
     * @param string $template template contents to tokeninze (input)
     * @param Node   $lines expected internal nodes to be returned (output)
     * @param string $prefix expected prefix (output)
     * @param string $postfix expected postfix (output)
     */
    public function testParse($template, Node $lines, $prefix, $postfix): void
    {
        // Setup a token stream and feed it into our token parser.
        $twig = new TestEnvironment(new CodeGenerationExtension());

        $stream = $twig->parse($twig->tokenize(new Source($template, 'found')));

        $per_line = null;
        foreach ($this->iterateAllNodes($stream) as $node) {
            if ($node instanceof Node && $node->getNodeTag() === 'perline') {
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
