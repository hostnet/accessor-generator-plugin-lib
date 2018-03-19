<?php
declare(strict_types=1);
/**
 * @copyright 2014-2018 Hostnet B.V.
 */

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Annotations;
use PHPUnit\Framework\TestCase;

class AnnotationsTest extends TestCase
{
    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetStupidTooManyArguments(): void
    {
        $annotations = new Annotations();
        $annotations->getStupid(1);
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetStupidEmpty(): void
    {
        $annotations = new Annotations();
        $property    = new \ReflectionProperty($annotations, 'stupid');
        $property->setAccessible(true);
        $property->setValue($annotations, null);
        $annotations->getStupid();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetStupidTooManyArguments(): void
    {
        $annotations = new Annotations();
        $annotations->setStupid(new \DateTime(), 2);
    }

    public function testSetStupid(): void
    {
        $annotations = new Annotations();
        $date        = new \DateTime();
        self::assertSame($annotations, $annotations->setStupid($date));
        self::assertSame($date, $annotations->getStupid());
    }
}
