<?php
namespace Hostnet\Component\AccessorGenerator\Annotation;

define('COLOR', '#FF0000');

class Constant  {
    const COLOR = '#00FF00';

    private $constant = self :: /*valid?*/ class;
    private $color = COLOR;
    private $color = self::COLOR;
    private $color = An\Other\Place::class;
}

