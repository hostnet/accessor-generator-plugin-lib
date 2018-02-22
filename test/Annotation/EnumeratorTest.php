<?php
namespace Hostnet\Component\AccessorGenerator\Annotation;

class EnumeratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $enumerator = new Enumerator();
        $enumerator->name  = 'Foo';
        $enumerator->value = '\\Some\\Random\\Class';

        self::assertEquals('\\Some\\Random\\Class', $enumerator->getEnumeratorClass());
        self::assertEquals('Foo', $enumerator->getName());
    }
}
