<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Plugin;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Comic\Obelix;

/**
 * @ORM\Entity
 */
class SubNamespace
{
    use Generated\SubNamespaceMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column
     */
    private $id;

    /**
     * @ORM\Column
     * @AG\Generate
     **/
    private $asterix = Comic\Asterix::class;

    private $obelix = Obelix::class;

    /**
     * @ORM\OneToOne(targetEntity="Hostnet\Component\AccessorGenerator\Generator\fixtures\Comic\Obelix")
     * @var Comic\Obelix
     */
    private $friend;

    /**
     * @ORM\Column
     * @AG\Generate(get="none")
     **/
    public $super_namespace = Plugin::NAME;
}
