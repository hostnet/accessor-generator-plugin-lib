<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\SubNamespace;
use PHPUnit\Framework\TestCase;

class SubNamespaceTest extends TestCase
{
    public function testGetAsterixTooManyArguments(): void
    {
        $subnamespace = new SubNamespace();

        $this->expectException(\BadMethodCallException::class);

        $subnamespace->getAsterix(1);
    }

    public function testGetAsterixEmpty(): void
    {
        $subnamespace = new SubNamespace();
        $property     = new \ReflectionProperty($subnamespace, 'asterix');
        $property->setAccessible(true);
        $property->setValue($subnamespace, null);

        $this->expectException(\LogicException::class);

        $subnamespace->getAsterix();
    }

    public function testSetAsterixTooManyArguments(): void
    {
        $subnamespace = new SubNamespace();

        $this->expectException(\BadMethodCallException::class);

        $subnamespace->setAsterix('1', 2);
    }

    public function testSetAsterixInvalidArgument(): void
    {
        $subnamespace = new SubNamespace();

        $this->expectException(\InvalidArgumentException::class);

        $subnamespace->setAsterix([]);
    }

    public function testSetAsterix(): void
    {
        $subnamespace = new SubNamespace();
        self::assertSame($subnamespace, $subnamespace->setAsterix('panoramix'));
        self::assertEquals('panoramix', $subnamespace->getAsterix());
    }

    public function testSetSuperNamespaceTooManyArguments(): void
    {
        $subnamespace = new SubNamespace();

        $this->expectException(\BadMethodCallException::class);

        $subnamespace->setSuperNamespace('1', 2);
    }

    public function testSetSuperNamespaceInvalidArgument(): void
    {
        $subnamespace = new SubNamespace();

        $this->expectException(\InvalidArgumentException::class);

        $subnamespace->setSuperNamespace([]);
    }

    public function testSetSuperNamespace(): void
    {
        $subnamespace = new SubNamespace();
        self::assertSame($subnamespace, $subnamespace->setSuperNamespace('panoramix'));
        self::assertEquals('panoramix', $subnamespace->super_namespace);
    }
}
