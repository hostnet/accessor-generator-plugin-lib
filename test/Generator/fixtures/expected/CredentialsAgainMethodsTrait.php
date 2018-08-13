<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\CredentialsAgain;

trait CredentialsAgainMethodsTrait
{
    /**
     * Sets password
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param string $password
     *
     * @return $this|CredentialsAgain
     */
    public function setPassword($password)
    {
        if (\func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setPassword() has one argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if ($password === null
            || \is_scalar($password)
            || \is_callable([$password, '__toString'])
        ) {
            $password = (string)$password;
        } else {
            throw new \InvalidArgumentException(
                'Parameter password must be convertible to string.'
            );
        }

        if (false == ($public_key_path = KeyRegistry::getPublicKeyPath('database.table.column_again'))) {
            throw new \InvalidArgumentException('A public key path must be set to use this method.');
        }

        if (false === ($public_key = openssl_get_publickey($public_key_path))) {
            throw new \InvalidArgumentException(sprintf('The path "%s" does not contain a public key.', $public_key_path));
        }

        $iv = openssl_random_pseudo_bytes(32);
        openssl_seal($password, $sealed_data, $env_keys, [$public_key], 'AES256', $iv);

        $env_key        = bin2hex($env_keys[0]);
        $iv             = bin2hex($iv);
        $sealed_data    = bin2hex($sealed_data);

        $this->password = sprintf('%d,%d,%s%s%s', strlen($env_key), strlen($iv), $env_key, $iv, $sealed_data);

        return $this;
    }
}
