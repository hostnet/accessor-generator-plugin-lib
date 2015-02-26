<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * Different typed columns
 */
class GenerateTypes
{
    use Generated\GenerateTypesMethodsTrait;

    /**
     * @AG\Generate(type="integer")
     */
    private $integer;

    /**
     * @AG\Generate(type="float")
     */
    private $float;

    /**
     * @AG\Generate(type="string")
     */
    private $string;

    /**
     * @AG\Generate(type="boolean")
     */
    private $boolean;

    /**
     * @AG\Generate(type="boolean")
     */
    private $is_this_boolean;

    /**
     * @AG\Generate(type="\DateTime")
     */
    private $datetime;

    /**
     * @AG\Generate(type="array")
     */
    private $array;

    /**
     * @AG\Generate(type="object")
     */
    private $object;
}
