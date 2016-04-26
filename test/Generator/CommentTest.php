<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Comment;

/**
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class CommentTest extends \PHPUnit_Framework_TestCase
{
    public function __construct($col = null)
    {
        $this->col = $col;
    }

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
