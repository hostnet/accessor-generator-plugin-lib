<?php

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
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
    }
}
