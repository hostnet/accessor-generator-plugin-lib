<?php
/**
 * @copyright 2015-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Comment;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function testGetCol(): void
    {
        $comment = new Comment();
        self::assertNull($comment->getCol());

        $comment = new Comment('test');
        self::assertEquals('test', $comment->getCol());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetColTooManyArguments(): void
    {
        $comment = new Comment();
        $comment->getCol('yiha');
    }
}
