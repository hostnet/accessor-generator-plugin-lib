<?php
namespace Hostnet\Component\AccessorGenerator\Twig;

/**
 * A helper class to to allow setting a TokenStream
 * directly into a TwigParser to create smaller unit
 * tests.
 *
 * Used for testing TokenParsers because they need
 * a Twig Parser to be fed, including a stream but
 * without this class, everything will start running
 * and the intermediate result is not available.
 *
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class TestParser extends \Twig_Parser
{
    /**
     * Register this parser in the env,
     * register the node visitors from
     * the env in this parser and register
     * a default expression parser.
     *
     * @override
     * @param \Twig_Environment $env
     */
    public function __construct(\Twig_Environment $env)
    {
        parent::__construct($env);

        // handlers
        $this->handlers = $this->env->getTokenParsers();
        $this->handlers->setParser($this);

        // visitors
        $this->visitors = $this->env->getNodeVisitors();

        // expression parser
        $this->expressionParser = new \Twig_ExpressionParser(
            $this,
            $this->env
        );
    }

    /**
     * Inject the token stream that will
     * be parsed by this twig parser.
     *
     * @param \Twig_TokenStream $stream
     */
    public function setStream(\Twig_TokenStream $stream)
    {
        $this->stream = $stream;
    }
}
