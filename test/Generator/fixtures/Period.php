<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'periode')]
class Period
{
    /**
     * A very nice and long
     * multi line description...
     *
     *
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private $id;
}
