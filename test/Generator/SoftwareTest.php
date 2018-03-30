<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Collections\Collection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\EmptyFeature;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Feature;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\NiceFeature;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Software;
use PHPUnit\Framework\TestCase;

class SoftwareTest extends TestCase
{
    public function testGetFeatures()
    {
        $software = new Software();
        $features = $software->getFeatures();

        self::assertEmpty($features);
        self::assertInstanceOf(Collection::class, $features);
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
        self::assertSame($software, $software->addFeature($feature));
        self::assertSame($feature, $software->getFeatures()->first());
        self::assertCount(1, $software->getFeatures());
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
        self::assertSame($software, $software->addFeature($feature));
        self::assertSame($feature, $features->first());
        self::assertCount(1, $features);

        //Add same feature again, no error expected
        self::assertSame($software, $software->addFeature($feature));
        self::assertSame($feature, $features->first());
        self::assertCount(1, $features);
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
        self::assertEmpty($features);
        $software->addFeature($feature);
        self::assertNotEmpty($features);
        self::assertSame($software, $software->removeFeature($feature));
        self::assertEmpty($features);

        // Remove same feature again, no error expected
        $software->removeFeature($feature);
        self::assertEmpty($features);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testRemoveFeatureTooManyArguments()
    {
        $feature  = new Feature();
        $software = new Software();

        $software->removeFeature($feature, 2);
    }

    /**
     * @expectedException \Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException
     */
    public function testNonExistingPropertyWithInterface()
    {
        if (PHP_VERSION_ID < 70100) {
            self::markTestSkipped('Problem does not exists below PHP7.1');
        }

        $software = new Software();
        $feature  = new EmptyFeature();
        $software->addFeature($feature);
    }

    public function testExistingPropertyWithInterface()
    {
        $software = new Software();
        $feature  = new NiceFeature();
        $software->addFeature($feature);
        self::assertSame($software, $feature->getSoftware());
    }
}
