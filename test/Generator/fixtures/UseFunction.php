<?php

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use const Hostnet\Component\AccessorGenerator\Generator\BYE as DAG;
use const Hostnet\Component\AccessorGenerator\Generator\BYE;
use function Hostnet\Component\AccessorGenerator\Generator\fixtures\destroy as kaboom;
use function sprintf;

/**
 * @ORM\Entity
 */
class UseFunction
{
    use Generated\UseFunctionMethodsTrait;

    /**
     * @var int
     *
     * @ORM\Column
     * @AG\Generate
     */
    private $count = 0;

    public function formattedCount(): string
    {
        return sprintf('Items: %d', $this->getCount());
    }

    public function destroy(): void
    {
        kaboom();

        echo BYE.DAG.PHP_EOL;
    }
}
