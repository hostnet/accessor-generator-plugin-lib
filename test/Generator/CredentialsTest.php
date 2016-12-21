<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Collections\Collection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Attribute;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Period;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Product;
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
}
