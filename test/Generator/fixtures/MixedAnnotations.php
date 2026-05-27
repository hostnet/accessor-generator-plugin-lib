<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Attribute as AG;

/**
 * Fixture for the mixed-mode pattern: docblock ORM annotations control the
 * column mapping, native #[AG\Generate] attributes control accessor generation.
 * Also exercises the `use DateTime;` (non-compound name) edge case.
 *
 * @ORM\Entity
 */
class MixedAnnotations
{
    use Generated\MixedAnnotationsMethodsTrait;

    /**
     * @ORM\Column(type="string", length=100)
     */
    #[AG\Generate]
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    #[AG\Generate(set: 'none')]
    private $count;

    /**
     * @ORM\Column(type="datetime")
     */
    #[AG\Generate(set: 'none')]
    private DateTime $created_at;
}
