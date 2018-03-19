<?php
declare(strict_types=1);
/**
 * @copyright 2015-2018 Hostnet B.V.
 */

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Comment;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function testGetCol(): void
    {
        $comment = new Comment('test');
        self::assertEquals('test', $comment->getCol());
    }

    public function testGetColTooManyArguments(): void
    {
        $comment = new Comment();

        $this->expectException(\BadMethodCallException::class);
        $comment->getCol('yiha');
    }
}
