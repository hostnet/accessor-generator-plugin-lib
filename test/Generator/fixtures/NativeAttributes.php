<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Attribute as AG;

/**
 * @ORM\Entity
 */
class NativeAttributes
{
    use Generated\NativeAttributesMethodsTrait;

    #[ORM\Column(type: 'string', length: 255)]
    #[AG\Generate]
    private $label;

    #[ORM\Column(type: 'integer')]
    #[AG\Generate(get: 'public', set: 'none')]
    private $count;
}
