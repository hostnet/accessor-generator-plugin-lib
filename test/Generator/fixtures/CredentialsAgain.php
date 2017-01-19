<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * @ORM\Entity
 * @ORM\Table(name="credentials_again")
 */
class CredentialsAgain
{
    use Generated\CredentialsAgainMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="password", type="string")
     * @AG\Generate(get="none", encryption_alias="database.table.column_again")
     */
    private $password;
}
