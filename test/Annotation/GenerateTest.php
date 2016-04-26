<?php
namespace Hostnet\Component\AccessorGenerator\Annotation;

class GenerateTest extends \PHPUnit_Framework_TestCase
{
    public function testNew()
    {
        $generate = new Generate();

        // Test default on values and availabillity of
        // the Generate Annotation public fields
        self::assertTrue($generate->get);
        self::assertTrue($generate->set);
        self::assertTrue($generate->add);
        self::assertTrue($generate->remove);
        self::assertTrue($generate->is);
    }
}
