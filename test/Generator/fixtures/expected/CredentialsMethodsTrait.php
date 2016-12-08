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

        list($env_key_length, $iv_length, $pieces) = explode(',', $this->password, 3);
        $env_key                                   = hex2bin(substr($pieces, 0, $env_key_length));
        $iv                                        = hex2bin(substr($pieces, $env_key_length, $iv_length));
        $sealed_data                               = hex2bin(substr($pieces, $env_key_length + $iv_length));

        openssl_open($sealed_data, $open_data, $env_key, openssl_get_privatekey('file://' . getcwd() . '/test/Generator/Key/credentials_private_key.pem'), 'AES256', $iv);

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

        $iv = openssl_random_pseudo_bytes(32);
        openssl_seal($password, $sealed_data, $env_keys, [openssl_get_publickey('file://' . getcwd() . '/test/Generator/Key/credentials_public_key.pem')], 'AES256', $iv);

        $env_key        = bin2hex($env_keys[0]);
        $iv             = bin2hex($iv);
        $sealed_data    = bin2hex($sealed_data);

        $this->password = sprintf('%d,%d,%s%s%s', strlen($env_key), strlen($iv), $env_key, $iv, $sealed_data);

        return $this;
    }
}
