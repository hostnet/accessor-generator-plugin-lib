<?php
declare(strict_types=1);
/**
 * @copyright 2016-2018 Hostnet B.V.
 */

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Credentials;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated\KeyRegistry;
use PHPUnit\Framework\TestCase;

class CredentialsTest extends TestCase
{
    /**
     * @var Credentials
     */
    private $credentials;

    protected function setUp()
    {
        KeyRegistry::addPublicKeyPath(
            'database.table.column',
            'file:///' . __DIR__ . '/Key/credentials_public_key.pem'
        );
        KeyRegistry::addPrivateKeyPath(
            'database.table.column',
            'file:///' . __DIR__ . '/Key/credentials_private_key.pem'
        );

        $this->credentials = new Credentials();
    }

    public function testPassword(): void
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
    public function testGetPasswordTooManyArguments(): void
    {
        $credentials = new Credentials();
        $credentials->getPassword('pass');
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSetPasswordTooManyArguments(): void
    {
        $credentials = new Credentials();
        $credentials->setPassword(1, 2);
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetPasswordEmpty(): void
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
    public function testSetPasswordArray(): void
    {
        $credentials = new Credentials();
        $credentials->setPassword([]);
    }
}
