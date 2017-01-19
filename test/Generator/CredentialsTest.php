<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Credentials;

class CredentialsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Credentials
     */
    private $credentials;

    protected function setUp()
    {
        $this->credentials = new Credentials();
    }

    public function testPassword()
    {
        // change the keys.
        $this->credentials->setPassword('password');
        self::assertEquals('password', $this->credentials->getPassword());

        $very_long_password =
            'veryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryvery'
            . 'veryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryvery'
            . 'veryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryvery'
            . 'veryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryvery'
            . 'veryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryvery'
            . 'veryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryvery'
            . 'veryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryvery'
            . 'veryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryvery'
            . 'veryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryvery'
            . 'longpassword';

        $this->credentials->setPassword($very_long_password);
        self::assertEquals($very_long_password, $this->credentials->getPassword());
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testGetPasswordTooManyArguments()
    {
        $credentials = new Credentials();
        $credentials->getPassword('pass');
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSetPasswordTooManyArguments()
    {
        $credentials = new Credentials();
        $credentials->setPassword(1, 2);
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetPasswordEmpty()
    {
        $credentials = new Credentials();
        $property    = new \ReflectionProperty($credentials, 'password');
        $property->setAccessible(true);
        $property->setValue($credentials, null);
        $credentials->getPassword();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetPasswordArray()
    {
        $credentials = new Credentials();
        $credentials->setPassword([]);
    }
}
