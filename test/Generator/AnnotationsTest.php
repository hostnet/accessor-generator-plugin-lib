<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Annotations;
use PHPUnit\Framework\TestCase;

class AnnotationsTest extends TestCase
{
    public function testGetStupidTooManyArguments(): void
    {
        $annotations = new Annotations();

        $this->expectException(\BadMethodCallException::class);

        $annotations->getStupid(1);
    }

    public function testGetStupidEmpty(): void
    {
        $annotations = new Annotations();
        $property    = new \ReflectionProperty($annotations, 'stupid');
        $property->setAccessible(true);
        $property->setValue($annotations, null);

        $this->expectException(\LogicException::class);

        $annotations->getStupid();
    }

    public function testSetStupidTooManyArguments(): void
    {
        $annotations = new Annotations();

        $this->expectException(\BadMethodCallException::class);

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
