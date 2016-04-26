<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Address;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAddressEmpty()
    {
        $user = new User();
        self::assertNull($user->getAddress());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetAddressTooManyArguments()
    {
        $user = new User();
        $user->getAddress(1);
    }

    public function testSetAddress()
    {
        $user    = new User();
        $address = new Address();

        $user->setAddress($address);
        self::assertSame($address, $user->getAddress());

        $user->setAddress(null);
        self::assertNull($user->getAddress());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetAddressTooManyArguments()
    {
        $user    = new User();
        $address = new Address();
        $user->setAddress($address, 1);
    }
}
