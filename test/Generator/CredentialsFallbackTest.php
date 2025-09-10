<?php
/**
 * @copyright 2025-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\Credentials;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated\KeyRegistry;
use PHPUnit\Framework\TestCase;

class CredentialsFallbackTest extends TestCase
{
    private Credentials $credentials;

    protected function setUp(): void
    {
        KeyRegistry::addPublicKeyPath(
            'database.table.column',
            'file:///' . __DIR__ . '/Key/credentials_public_key.pem'
        );
        KeyRegistry::addPrivateKeyPath(
            'database.table.column',
            'file:///' . __DIR__ . '/Key/credentials_private_key_not_matching.pem'
        );

        $this->credentials = new Credentials();
        $this->credentials->setPassword('password');
    }

    public function testGetPasswordWithoutFallback(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('openssl_open failed. Message:');
        $this->credentials->getPassword();
    }

    public function testGetPasswordWithoutPrivateKeyInFile(): void
    {
        KeyRegistry::addPrivateKeyPath(
            'database.table.column_fallback',
            'file:///' . __DIR__ . '/Key/credentials_public_key.pem'
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('does not contain a private key.');
        $this->credentials->getPassword();
    }

    public function testGetPasswordFallbackKeyNotMatching(): void
    {
        KeyRegistry::addPrivateKeyPath(
            'database.table.column_fallback',
            'file:///' . __DIR__ . '/Key/credentials_private_key_not_matching.pem'
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Decryption failed: [openssl_open failed. Message:');
        $this->expectExceptionMessage('Fallback also failed: [openssl_open failed. Message:');
        $this->credentials->getPassword();
    }

    public function testGetPasswordSuccess(): void
    {
        KeyRegistry::addPrivateKeyPath(
            'database.table.column_fallback',
            'file:///' . __DIR__ . '/Key/credentials_private_key.pem'
        );

        self::assertSame('password', $this->credentials->getPassword());
    }
}
