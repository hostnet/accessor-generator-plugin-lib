<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Comment;
use PHPUnit\Framework\TestCase;

/**
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class CommentTest extends TestCase
{
    public function testGetCol()
    {
        $comment = new Comment();
        self::assertNull($comment->getCol());

        $comment = new Comment('test');
        self::assertEquals('test', $comment->getCol());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetColTooManyArguments()
    {
        $comment = new Comment();
        $comment->getCol('yiha');
    }
}
