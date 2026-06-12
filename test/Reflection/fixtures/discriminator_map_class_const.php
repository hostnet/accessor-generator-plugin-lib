<?php
namespace Test;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Attribute as AG;

/**
 * Entity with a DiscriminatorMap that uses ::class syntax in attribute arguments.
 * The T_CLASS tokens from ::class must not confuse getName() into thinking it found
 * the class declaration before the actual 'abstract class' keyword.
 */
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorMap([
    'child_a' => ChildA::class,
    'child_b' => ChildB::class,
])]
abstract class ParentEntity
{
    /**
     * @AG\Generate(set="none")
     */
    #[ORM\Column(type: 'string')]
    private string $name;
}
