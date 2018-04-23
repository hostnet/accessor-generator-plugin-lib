<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\ConstantDefault;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Weather;
use PHPUnit\Framework\TestCase;

class ConstantDefaultTest extends TestCase
{
    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetWeatherTooManyArguments()
    {
        $constant_default = new ConstantDefault();
        $constant_default->getWeather(1);
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetWeatherEmpty()
    {
        $constant_default = new ConstantDefault();
        $property         = new \ReflectionProperty($constant_default, 'weather');
        $property->setAccessible(true);
        $property->setValue($constant_default, null);
        $constant_default->getWeather();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetWeatherTooManyArguments()
    {
        $constant_default = new ConstantDefault();
        $constant_default->setWeather(1, 2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetWeatherInvalidArgument()
    {
        $constant_default = new ConstantDefault();
        $constant_default->setWeather('1');
    }

    /**
     * @expectedException \DomainException
     */
    public function testSetWeatherInvalidDomain()
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped('only valid for a 64bit system');
        } else {
            $constant_default = new ConstantDefault();
            $constant_default->setWeather(PHP_INT_MAX);
        }
    }

    /**
     * @expectedException \DomainException
     */
    public function testGetWeatherInvalidDomain()
    {
        $constant_default = new ConstantDefault();
        $property         = new \ReflectionProperty($constant_default, 'weather');
        $property->setAccessible(true);
        $property->setValue($constant_default, PHP_INT_MAX * 10);
        $constant_default->getWeather();
    }


    public function testSetWeather()
    {
        $constant_default = new ConstantDefault();
        self::assertSame($constant_default, $constant_default->setWeather(Weather::RAIN));
        self::assertEquals(Weather::RAIN, $constant_default->getWeather());
    }
}
