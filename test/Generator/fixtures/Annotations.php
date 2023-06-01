<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Symfony\Component\Console as Stupid;

class Annotations
{
    use Generated\AnnotationsMethodsTrait;

    /**
     * @AG\Generate(type="\DateTime")
     * @Stupid\Really
     */
    public ?\DateTime $stupid;
}
