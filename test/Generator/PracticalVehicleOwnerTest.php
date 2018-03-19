<?php
declare(strict_types=1);
/**
 * @copyright 2016-2018 Hostnet B.V.
 */

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Bicycle;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Boat;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Car;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\PracticalVehicleOwner;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\VehicleInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated\PracticalVehicleOwnerMethodsTrait
 */
class PracticalVehicleOwnerTest extends TestCase
{
    public function testAddVehicle(): void
    {
        $boat = new Boat();
        $car  = new Car();

        $owner = new PracticalVehicleOwner();
        $owner->addVehicle($boat)->addVehicle($car);

        self::assertSame([$boat, $car], $owner->vehicles->toArray());
    }

    /**
     * @expectedException \Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException
     */
    public function testAddWrongVehicle(): void
    {

        $owner = new PracticalVehicleOwner();
        $owner->addVehicle($this->prophesize(VehicleInterface::class)->reveal());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testAddVehicleTooManyArguments(): void
    {
        $car   = new Car();
        $owner = new PracticalVehicleOwner();
        $owner->addVehicle($car, $car);
    }

    /**
     * @expectedException \LogicException
     */
    public function testAddVehicleToMultipleOwners(): void
    {
        $owner = new PracticalVehicleOwner();
        $thief = new PracticalVehicleOwner();

        $bicycle = new Bicycle();

        $owner->addVehicle($bicycle);
        $thief->addVehicle($bicycle);
    }
}
