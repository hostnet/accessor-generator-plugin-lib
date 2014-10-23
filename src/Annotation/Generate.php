<?php
namespace Hostnet\Component\AccessorGenerator\Annotation;

/**
 * Annotation to activate accessor method generation
 * for a property. You can disable generation of cer-
 * tain methods by setting them to false in your an-
 * notation.
 *
 * The this annotation is designed to be used with
 * doctrine/annotations.
 *
 * @Annotation
 * @Target("PROPERTY")
 * @see http://doctrine-common.readthedocs.org/en/latest/reference/annotations.html
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class Generate
{
    public $get    = true;
    public $set    = true;
    public $add    = true;
    public $remove = true;
    public $is     = true;
}
