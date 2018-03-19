<?php
declare(strict_types=1);
/**
 * @copyright 2017-2018 Hostnet B.V.
 */

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Generator\fixtures\CredentialsAgain;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated\KeyRegistry;
use PHPUnit\Framework\TestCase;

class CredentialsAgainTest extends TestCase
{
    /**
     * @var CredentialsAgain
     */
    private $credentials_again;

    protected function setUp()
    {
        KeyRegistry::addPublicKeyPath(
            'database.table.column_again',
            'file:///' . __DIR__ . '/Key/credentials_public_key.pem'
        );

        $this->credentials_again = new CredentialsAgain();
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSetPasswordTooManyArguments(): void
    {
        $credentials_again = new CredentialsAgain();
        $credentials_again->setPassword(1, 2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetPasswordArray(): void
    {
        $credentials = new CredentialsAgain();
        $credentials->setPassword([]);
    }
}
