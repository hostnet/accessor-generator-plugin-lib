<?php
/**
 * @copyright 2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\UseFunction;
use PHPUnit\Framework\TestCase;

class UseFunctionTest extends TestCase
{
    public function testGeneric()
    {
        $use_function = new UseFunction();

        self::assertEquals('Items: 0', $use_function->formattedCount());
        self::assertEquals('Items: 1', $use_function->setCount(1)->formattedCount());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetCountTooManyArguments()
    {
        $use_function = new UseFunction();

        $use_function->getCount(1);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetCountTooManyArguments()
    {
        $use_function = new UseFunction();

        $use_function->setCount(1, 2);
    }
}
