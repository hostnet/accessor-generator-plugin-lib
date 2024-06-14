// line 1
$lines = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros) {
    yield ($context["data"] ?? null);
})() ?? new \EmptyIterator())) ? '' : new Markup($tmp, $this->env->getCharset());
$lines = explode("\n", $lines);
foreach ($lines as $key => $line) {
    yield $key > 0 ? '  * ' : '* ' ;
    yield "$line";
    yield "// POSTFIX\n";
}
