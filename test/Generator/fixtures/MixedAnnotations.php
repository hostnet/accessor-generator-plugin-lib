<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Attribute as AG;

/**
 * Fixture for the mixed-mode pattern: docblock ORM annotations control the
 * column mapping, native #[AG\Generate] attributes control accessor generation.
 * Also exercises the `use DateTime;` (non-compound name) edge case.
 */
#[ORM\Entity]
class MixedAnnotations
{
    use Generated\MixedAnnotationsMethodsTrait;

    #[AG\Generate]
    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    #[AG\Generate(set: 'none')]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $count;

    #[AG\Generate(set: 'none')]
    #[ORM\Column(type: 'datetime')]
    private DateTime $created_at;
}
