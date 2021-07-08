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
    public function testGetWeatherTooManyArguments(): void
    {
        $constant_default = new ConstantDefault();

        $this->expectException(\BadMethodCallException::class);

        $constant_default->getWeather(1);
    }

    public function testGetWeatherEmpty(): void
    {
        $constant_default = new ConstantDefault();
        $property         = new \ReflectionProperty($constant_default, 'weather');
        $property->setAccessible(true);
        $property->setValue($constant_default, null);

        $this->expectException(\LogicException::class);

        $constant_default->getWeather();
    }

    public function testSetWeatherTooManyArguments(): void
    {
        $constant_default = new ConstantDefault();

        $this->expectException(\BadMethodCallException::class);

        $constant_default->setWeather(1, 2);
    }

    public function testSetWeatherInvalidArgument(): void
    {
        $constant_default = new ConstantDefault();

        $this->expectException(\InvalidArgumentException::class);

        $constant_default->setWeather('1');
    }

    public function testSetWeatherInvalidDomain(): void
    {
        if (PHP_INT_SIZE < 8) {
            $this->markTestSkipped('only valid for a 64bit system');
        } else {
            $constant_default = new ConstantDefault();

            $this->expectException(\DomainException::class);

            $constant_default->setWeather(PHP_INT_MAX);
        }
    }

    public function testGetWeatherInvalidDomain(): void
    {
        $constant_default = new ConstantDefault();
        $property         = new \ReflectionProperty($constant_default, 'weather');
        $property->setAccessible(true);
        $property->setValue($constant_default, PHP_INT_MAX * 10);

        $this->expectException(\DomainException::class);

        $constant_default->getWeather();
    }

    public function testSetWeather(): void
    {
        $constant_default = new ConstantDefault();
        self::assertSame($constant_default, $constant_default->setWeather(Weather::RAIN));
        self::assertEquals(Weather::RAIN, $constant_default->getWeather());
    }
}
