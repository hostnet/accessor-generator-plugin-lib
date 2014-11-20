<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Collections\Collection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Feature;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Software;

/**
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class SoftwareTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFeatures()
    {
        $software = new Software();
        $features = $software->getFeatures();

        $this->assertEmpty($features);
        $this->assertInstanceOf(Collection::class, $features);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testGetFeaturesTooManyArguments()
    {
        $software = new Software();
        $software->getFeatures(1);
    }

    /**
     * @depends testGetFeatures
     */
    public function testAddFeature()
    {
        $feature  = new Feature();
        $software = new Software();

        // Add feature and retrieve it back, also check
        // the return type of the add method.
        $this->assertSame($software, $software->addFeature($feature));
        $this->assertSame($feature, $software->getFeatures()->first());
        $this->assertCount(1, $software->getFeatures());
    }

    /**
     * @depends testGetFeatures
     * @expectedException LogicException
     */
    public function testAddFeatureToMultiple()
    {
        $f = new Feature();
        $a = new Software();
        $b = new Software();

        $a->addFeature($f);
        $b->addFeature($f);
    }


    /**
     * @depends testGetFeatures
     */
    public function testAddFeatureReference()
    {
        $feature  = new Feature();
        $software = new Software();

        // Create a reference to check that we actually
        // get a reference and not a copy or a copy-on-write.
        $features = $software->getFeatures();

        // Add feature and retrieve it back, also check
        // the return type of the add method.
        $this->assertSame($software, $software->addFeature($feature));
        $this->assertSame($feature, $features->first());
        $this->assertCount(1, $features);

        //Add same feature again, no error expected
        $this->assertSame($software, $software->addFeature($feature));
        $this->assertSame($feature, $features->first());
        $this->assertCount(1, $features);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testAddFeatureTooManyArguments()
    {
        $feature  = new Feature();
        $software = new Software();

        $software->addFeature($feature, 2);
    }

    /**
     * @depends testGetFeatures
     * @depends testAddFeature
     */
    public function testRemoveFeature()
    {
        $feature  = new Feature();
        $software = new Software();

        // Create a reference to check that we actually
        // get a reference and not a copy or a copy-on-write.
        $features = $software->getFeatures();

        // Add and remove feature and retrieve it back, also check
        // the return type of the add method.
        $this->assertEmpty($features);
        $software->addFeature($feature);
        $this->assertNotEmpty($features);
        $this->assertSame($software, $software->removeFeature($feature));
        $this->assertEmpty($features);

        // Remove same feature again, no error expected
        $software->removeFeature($feature);
        $this->assertEmpty($features);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testRemoveFeatureTooManyArguments()
    {
        $feature  = new Feature();
        $software = new Software();

        $software->removeFeature($feature, 2);
    }
}
