<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

class Comment
{
    use Generated\CommentMethodsTrait;

    public function __construct($col = null)
    {
        $this->col = $col;
    }

    /**
     * --> col <--
     * @ORM\Column
     * @AG\Generate(set="none")
     */
    private $col = null;
}
