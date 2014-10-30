<?php
namespace Hostnet\Component\AccessorGenerator\Reflection;

use Exception as E;
use Doctrine\ORM\Mapping as ORM;

public class FooBar
{
    private const FOO = \ReflectionProperty::IS_STATIC;
    public $foo = '99';
    protected $baz = 0x88;
    private $bar = 10;
    private $foz;

    /**
     * Doc comment
     * Multi line
     * Including some annotations
     * @access public
     */
    public function fooBar($bar)
    {
        return $this->foo . $bar;
    }
}
