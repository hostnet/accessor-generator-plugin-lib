<?php
declare(strict_types=1);
/**
 * @copyright 2014-2018 Hostnet B.V.
 */

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\SubNamespace;
use PHPUnit\Framework\TestCase;

class SubNamespaceTest extends TestCase
{
    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetAsterixTooManyArguments(): void
    {
        $subnamespace = new SubNamespace();
        $subnamespace->getAsterix(1);
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetAsterixEmpty(): void
    {
        $subnamespace = new SubNamespace();
        $property     = new \ReflectionProperty($subnamespace, 'asterix');
        $property->setAccessible(true);
        $property->setValue($subnamespace, null);
        $subnamespace->getAsterix();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetAsterixTooManyArguments(): void
    {
        $subnamespace = new SubNamespace();
        $subnamespace->setAsterix('1', 2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetAsterixInvalidArgument(): void
    {
        $subnamespace = new SubNamespace();
        $subnamespace->setAsterix([]);
    }

    public function testSetAsterix(): void
    {
        $subnamespace = new SubNamespace();
        self::assertSame($subnamespace, $subnamespace->setAsterix('panoramix'));
        self::assertEquals('panoramix', $subnamespace->getAsterix());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetSuperNamespaceTooManyArguments(): void
    {
        $subnamespace = new SubNamespace();
        $subnamespace->setSuperNamespace('1', 2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetSuperNamespaceInvalidArgument(): void
    {
        $subnamespace = new SubNamespace();
        $subnamespace->setSuperNamespace([]);
    }

    public function testSetSuperNamespace(): void
    {
        $subnamespace = new SubNamespace();
        self::assertSame($subnamespace, $subnamespace->setSuperNamespace('panoramix'));
        self::assertEquals('panoramix', $subnamespace->super_namespace);
    }
}
