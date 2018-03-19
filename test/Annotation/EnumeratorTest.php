<?php
declare(strict_types=1);
/**
 * @copyright 2018 Hostnet B.V.
 */

namespace Hostnet\Component\AccessorGenerator\Annotation;

use PHPUnit\Framework\TestCase;

class EnumeratorTest extends TestCase
{
    public function testGetters(): void
    {
        $enumerator        = new Enumerator();
        $enumerator->name  = 'Foo';
        $enumerator->value = '\\Some\\Random\\Class';

        self::assertEquals('\\Some\\Random\\Class', $enumerator->getEnumeratorClass());
        self::assertEquals('Foo', $enumerator->getName());
    }
}
