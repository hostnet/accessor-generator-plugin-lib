<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Hostnet\Component\AccessorGenerator\Attribute as AG;
use Symfony\Component\Console as Stupid;

class Annotations
{
    use Generated\AnnotationsMethodsTrait;

    /**
     * @Stupid\Really
     */
    #[AG\Generate(type: '\DateTime')]
    public ?\DateTime $stupid;
}
