<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\CredentialsAgain;

class CredentialsAgainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CredentialsAgain
     */
    private $credentials_again;

    protected function setUp()
    {
        $this->credentials_again = new CredentialsAgain();
    }

    public function testSetPassword()
    {
        $this->credentials_again->setPassword('password');
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSetPasswordTooManyArguments()
    {
        $credentials_again = new CredentialsAgain();
        $credentials_again->setPassword(1, 2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetPasswordArray()
    {
        $credentials = new CredentialsAgain();
        $credentials->setPassword([]);
    }
}
