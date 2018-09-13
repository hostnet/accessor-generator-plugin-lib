<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Comment;

trait CommentMethodsTrait
{
    /**
     * --> col <--
     *
     * @throws \BadMethodCallException
     *
     * @return string|null
     */
    public function getCol(): ?string
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getCol() has no arguments but %d given.',
                    \func_num_args()
                )
            );
        }

        if ($this->col === null) {
            return null;
        }

        return $this->col;
    }
}
