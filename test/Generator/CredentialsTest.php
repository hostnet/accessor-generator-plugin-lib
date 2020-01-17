<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

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

    protected function setUp(): void
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

    public function testGetPasswordTooManyArguments(): void
    {
        $credentials = new Credentials();

        $this->expectException(\BadMethodCallException::class);

        $credentials->getPassword('pass');
    }

    public function testSetPasswordTooManyArguments(): void
    {
        $credentials = new Credentials();

        $this->expectException(\BadMethodCallException::class);

        $credentials->setPassword(1, 2);
    }

    public function testGetPasswordEmpty(): void
    {
        $credentials = new Credentials();
        $property    = new \ReflectionProperty($credentials, 'password');
        $property->setAccessible(true);
        $property->setValue($credentials, null);

        $this->expectException(\LogicException::class);

        $credentials->getPassword();
    }

    public function testSetPasswordArray(): void
    {
        $credentials = new Credentials();

        $this->expectException(\InvalidArgumentException::class);

        $credentials->setPassword([]);
    }
}
