<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Twig;

use Twig\Error\SyntaxError;
use Twig\Node\Node;
use Twig\Node\TextNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * Parse Twig tag {% perline %}
 *
 * A per line block looks like:
 *
 * {% perline %}
 * prefix {{ foo }} baz {{ bar }} postfix
 * {% perline %}
 *
 * After parsing a PerLineNode will be returned
 */
class PerLineTokenParser extends AbstractTokenParser
{
    /**
     * @see Twig_TokenParserInterface::getTag()
     */
    public function getTag(): string
    {
        return 'perline';
    }

    /**
     * Parses perline token and returns PerLineNode.
     *
     * Parse everything within the perline block and then restructure the
     * contents into some thing nice to build a PerLineNode out of.
     *
     * @param Token $token
     *
     * @throws SyntaxError
     *
     * @return PerLineNode
     */
    public function parse(Token $token): PerLineNode
    {
        $stream = $this->parser->getStream();

        // perline is a very simple tag, not having anything more than its name
        // between the braces, so we expect the closing brace immediately.
        $stream->expect(Token::BLOCK_END_TYPE);

        // sub-parse everything until we reach the endperline tag.
        $body = $this->parser->subparse(function (Token $token) {
            return $token->test('endperline');
        }, true);

        // make sure our closing tag is also closed neatly and advance the
        // stream to allow continuation of parsing.
        $stream->expect(Token::BLOCK_END_TYPE);

        // turn out body in a nicely formatted PerLineNode
        return $this->parseBody($body);
    }

    /**
     * Parse the body into a prefix, postfix and all the
     * other twig nodes that will compile into multiple
     * lines.
     *
     * @param Node $body
     *
     * @return PerLineNode
     */
    private function parseBody(Node $body): PerLineNode
    {
        $prefix  = '';                       // Text before the (possibly) multi line expression
        $postfix = '';                       // Text before the (possibly) multi line expression
        $lineno  = $body->getTemplateLine(); // The line number where we found the {% perline %} tag

        // If the body does not contain a list of tags, the body itself is the
        // only useful content of the perline tags, so we return only the body
        // tag. This is the case when the perline tags could be removed without
        // an effect in the generated code.
        if (count($body) === 0) {
            return new PerLineNode($body, '', '', $lineno, $this->getTag());
        }

        // Get all the nodes as array, because it will be modified.
        $nodes = $body->getIterator()->getArrayCopy();

        // Check for prefix, the first node should be a text node to have a
        // prefix.
        $first = reset($nodes);
        if ($first instanceof TextNode) {
            $prefix = $first->getAttribute('data');
            array_shift($nodes);
        }

        // Check for postfix, the last node should be a text node to have a
        // postfix.
        $last = end($nodes);
        if ($last instanceof TextNode) {
            $postfix = rtrim($last->getAttribute('data'));
            array_pop($nodes);
        }

        // After we ditched the prefix and postfix there could only be one node
        // left, ready to be returned and used directly. If there are multiple
        // we wrap those in a Twig Node.
        if (count($nodes) === 1) {
            $nodes = current($nodes);
        } else {
            $nodes = new Node($nodes);
        }

        // Return the new structured node.
        return new PerLineNode($nodes, $prefix, $postfix, $lineno, $this->getTag());
    }
}
