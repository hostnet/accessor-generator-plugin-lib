<?php
namespace Hostnet\Component\AccessorGenerator\Twig;

class TestParser extends \Twig_Parser
{
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
            $this->env->getUnaryOperators(),
            $this->env->getBinaryOperators()
        );
    }

    public function setStream(\Twig_TokenStream $stream)
    {
        $this->stream = $stream;
    }
}
