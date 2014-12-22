<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * Different typed columns
 * @ORM\Entity
 */
class Types
{
    use Generated\TypesMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column
     * @AG\Generate
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     * @AG\Generate
     */
    private $smallint;

    /**
     * @ORM\Column(type="integer")
     * @AG\Generate
     */
    private $integer;

    /**
     * @ORM\Column(type="bigint")
     * @AG\Generate
     */
    private $bigint;

    /**
     * @ORM\Column(type="decimal", scale=1, precision=10)
     * @AG\Generate
     */
    private $decimal;

    /**
     * @ORM\Column(type="float")
     * @AG\Generate
     */
    private $float;

    /**
     * @ORM\Column(type="string", length=255)
     * @AG\Generate
     */
    private $string;

    /**
     * @ORM\Column(type="text")
     * @AG\Generate
     */
    private $text;

    /**
     * @ORM\Column(type="guid")
     * @AG\Generate
     */
    private $guid;

    /**
     * @ORM\Column(type="blob")
     * @AG\Generate
     */
    private $blob;

    /**
     * @ORM\Column(type="boolean")
     * @AG\Generate
     */
    private $boolean;

    /**
     * @ORM\Column(type="boolean")
     * @AG\Generate
     */
    private $is_this_boolean;

    /**
     * @ORM\Column(type="date")
     * @AG\Generate
     */
    private $date;

    /**
     * @ORM\Column(type="datetime")
     * @AG\Generate
     */
    private $datetime;

    /**
     * @ORM\Column(type="array")
     * @AG\Generate
     */
    private $array;

    /**
     * @ORM\Column(type="json_array")
     * @AG\Generate
     */
    private $json_array;
    /**
     * @ORM\Column(type="object")
     * @AG\Generate
     */
    private $object;
}
