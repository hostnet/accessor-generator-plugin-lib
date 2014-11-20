<?php
namespace Hostnet\Component\AccessorGenerator\Annotation;

class DefaultArrays  {
    private $a = array  /* comment */ ('string');
    private $b = ['string'];
    private $c = [0 => 'string'];
    private $d = [0 => 'string', 1 => 2];
    private $e = [0 => 'string', 1 => 2, 'three' => 3];
    private $f = [0 => 'string', /* yeah */ 1 => '2', 'three' => 3];
    private $g = [0 => 'string', array('1' => ['2']), 'three' => 3];
    private $h = [0 => 'string', array('1' => [('2')]), 'three' => 3];
}

