<?php
declare(strict_types=1);
/**
 * @copyright 2014-2018 Hostnet B.V.
 */

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\ConstantDefault;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Weather;
use PHPUnit\Framework\TestCase;

class ConstantDefaultTest extends TestCase
{
    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetWeatherTooManyArguments(): void
    {
        $constant_default = new ConstantDefault();
        $constant_default->getWeather(1);
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetWeatherEmpty(): void
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
    public function testSetWeatherTooManyArguments(): void
    {
        $constant_default = new ConstantDefault();
        $constant_default->setWeather(1, 2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetWeatherInvalidArgument(): void
    {
        $constant_default = new ConstantDefault();
        $constant_default->setWeather('1');
    }

    /**
     * @expectedException \DomainException
     */
    public function testSetWeatherInvalidDomain(): void
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
    public function testGetWeatherInvalidDomain(): void
    {
        $constant_default = new ConstantDefault();
        $property         = new \ReflectionProperty($constant_default, 'weather');
        $property->setAccessible(true);
        $property->setValue($constant_default, PHP_INT_MAX * 10);
        $constant_default->getWeather();
    }


    public function testSetWeather(): void
    {
        $constant_default = new ConstantDefault();
        self::assertSame($constant_default, $constant_default->setWeather(Weather::RAIN));
        self::assertEquals(Weather::RAIN, $constant_default->getWeather());
    }
}
