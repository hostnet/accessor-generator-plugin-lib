<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Bicycle;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Boat;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Car;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\PracticalVehicleOwner;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\VehicleInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * @covers \Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated\PracticalVehicleOwnerMethodsTrait
 */
class PracticalVehicleOwnerTest extends TestCase
{
    use ProphecyTrait;
    
    public function testAddVehicle(): void
    {
        $boat = new Boat();
        $car  = new Car();

        $owner = new PracticalVehicleOwner();
        $owner->addVehicle($boat)->addVehicle($car);

        self::assertSame([$boat, $car], $owner->vehicles->toArray());
    }

    public function testAddWrongVehicle(): void
    {
        $owner = new PracticalVehicleOwner();

        $this->expectException(MissingPropertyException::class);

        $owner->addVehicle($this->prophesize(VehicleInterface::class)->reveal());
    }

    public function testAddCustomVehicle(): void
    {
        $owner   = new PracticalVehicleOwner();
        $bicycle = new Bicycle();
        $owner->addVehicle($bicycle)->addVehicle($bicycle);

        self::assertContains($bicycle, $owner->vehicles);
    }

    public function testAddVehicleTooManyArguments(): void
    {
        $car   = new Car();
        $owner = new PracticalVehicleOwner();

        $this->expectException(\BadMethodCallException::class);

        $owner->addVehicle($car, $car);
    }

    public function testAddVehicleToMultipleOwners(): void
    {
        $owner = new PracticalVehicleOwner();
        $thief = new PracticalVehicleOwner();

        $bicycle = new Bicycle();

        $owner->addVehicle($bicycle);

        $this->expectException(\LogicException::class);

        $thief->addVehicle($bicycle);
    }
}
