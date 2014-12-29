<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Collections\Collection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Feature;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Software;

/**
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class FeatureTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->feature = new Feature();
        $this->method  = new \ReflectionMethod($this->feature, 'setSoftware');
        $this->method->setAccessible(true);
    }

    /**
     * We are testing a private function here because
     * it is part of the api used by the programmer
     * when (s)he uses the generated code. We want
     * to be sure the association is kept in sync on
     * both side.
     * @expectedException \LogicException
     */
    public function testSetParentNotSynced()
    {
        $this->method->invoke($this->feature, new Software());
    }

    /**
     * We are testing a private function here because
     * it is part of the api used by the programmer.
     *
     * @expectedException \BadMethodCallException
     */
    public function testSetParentTooManyArguments()
    {
        $this->method->invoke($this->feature, new Software(), 2);
    }

    /**
     * We are testing a private function here because
     * it is part of the api used by the programmer.
     *
     */
    public function testSetParentNoArguments()
    {
        $this->method->invoke($this->feature);
    }
}
