<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Credentials;

trait CredentialsMethodsTrait
{
    /**
     * Gets password
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return string
     */
    public function getPassword()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getPassword() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->password === null) {
            throw new \LogicException(sprintf(
                'Property password is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        if (false == ($private_key_path = KeyRegistry::getPrivateKeyPath('database.table.column'))) {
            throw new \InvalidArgumentException('A private key path must be set to use this method.');
        }

        if (false === ($private_key = openssl_get_privatekey($private_key_path))) {
            throw new \InvalidArgumentException(sprintf('The path %s does not contain a private key.', $private_key_path));
        }

        list($env_key_length, $iv_length, $pieces) = explode(',', $this->password, 3);
        $env_key                                   = hex2bin(substr($pieces, 0, $env_key_length));
        $iv                                        = hex2bin(substr($pieces, $env_key_length, $iv_length));
        $sealed_data                               = hex2bin(substr($pieces, $env_key_length + $iv_length));

        openssl_open($sealed_data, $open_data, $env_key, $private_key, 'AES256', $iv);

        return $open_data;
    }

    /**
     * Sets password
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param  string $password
     * @return $this|Credentials
     */
    public function setPassword($password)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setPassword() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($password === null
            || is_scalar($password)
            || is_callable([$password, '__toString'])
        ) {
            $password = (string)$password;
        } else {
            throw new \InvalidArgumentException(
                'Parameter password must be convertible to string.'
            );
        }

        if (false == ($public_key_path = KeyRegistry::getPublicKeyPath('database.table.column'))) {
            throw new \InvalidArgumentException('A public key path must be set to use this method.');
        }

        if (false === ($public_key = openssl_get_publickey($public_key_path))) {
            throw new \InvalidArgumentException(sprintf('The path %s does not contain a public key.', $public_key_path));
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
