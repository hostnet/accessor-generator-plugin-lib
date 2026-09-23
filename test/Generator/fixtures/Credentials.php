<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Attribute as AG;

#[ORM\Entity]
#[ORM\Table(name: 'credentials')]
class Credentials
{
    use Generated\CredentialsMethodsTrait;

    /**
     *
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private $id;

    #[ORM\Column(name: 'password', type: 'string')]
    #[AG\Generate(encryption_alias: 'database.table.column')]
    private $password;
}
