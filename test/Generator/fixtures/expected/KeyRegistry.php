<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

/**
 * This class holds all the keys for the Traits in the same directory. It also
 * provides methods to statically add keys (useful when testing).
 */
class KeyRegistry
{
    /**
     * @var array
     */
    private static $public_key_paths = [
       'database.table.column' => '',
       'database.table.column_again' => '',
    ];

    /**
     * @var array
     */
    private static $private_key_paths = [
       'database.table.column' => '',
       'database.table.column_again' => '',
    ];

    /**
     * Gets the file path where the public key is located for the given encryption alias.
     *
     * @return string
     */
    public static function getPublicKeyPath($alias)
    {
        return self::$public_key_paths[$alias] ?? '';
    }

    /**
     * Gets the file path where the private key is located for the given encryption alias.
     *
     * @return string
     */
    public static function getPrivateKeyPath($alias)
    {
        return self::$private_key_paths[$alias] ?? '';
    }

    /**
     * Add a public key file path for a specific alias manually. If the alias already
     * exists, the existing path is overwritten.
     *
     * @param string $alias
     * @param string $path
     */
    public static function addPublicKeyPath($alias, $path)
    {
        self::$public_key_paths[$alias] = $path;
    }

    /**
     * Add a private key file path for a specific alias manually. If the alias already
     * exists, the existing path is overwritten.
     *
     * @param string $alias
     * @param string $path
     */
    public static function addPrivateKeyPath($alias, $path)
    {
        self::$private_key_paths[$alias] = $path;
    }
}
