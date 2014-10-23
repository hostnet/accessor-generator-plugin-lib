<?php
namespace Hostnet\Component\AccessorGenerator\Annotation;

class GenerateTest extends \PHPUnit_Framework_TestCase
{
    public function testNew()
    {
        $generate = new Generate();

        // Test default on values and availabillity of
        // the Generate Annotation public fields
        $this->assertTrue($generate->get);
        $this->assertTrue($generate->set);
        $this->assertTrue($generate->add);
        $this->assertTrue($generate->remove);
        $this->assertTrue($generate->is);
    }
}
