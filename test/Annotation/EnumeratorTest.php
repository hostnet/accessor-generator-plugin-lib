<?php
/**
 * @copyright 2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Annotation;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\AccessorGenerator\Attribute\Enumerator
 */
class EnumeratorTest extends TestCase
{
    public function testGetters(): void
    {
        $enumerator = new Enumerator(value: '\\Some\\Random\\Class', name: 'Foo');

        self::assertEquals('\\Some\\Random\\Class', $enumerator->getEnumeratorClass());
        self::assertEquals('Foo', $enumerator->getName());
    }
}
