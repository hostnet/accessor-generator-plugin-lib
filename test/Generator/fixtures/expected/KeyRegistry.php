<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

/**
 * This class holds all the keys for the Traits in the same directory. It also
 * provides methods to statically add keys (useful when testing).
 */
class KeyRegistry
{
    private static $public_key_paths = [
       'database.table.column' => '',
       'database.table.column_again' => '',
    ];

    private static $private_key_paths = [
       'database.table.column' => '',
       'database.table.column_again' => '',
    ];

    public static function getPublicKeyPath($alias)
    {
        return self::$public_key_paths[$alias] ?? '';
    }

    public static function getPrivateKeyPath($alias)
    {
        return self::$private_key_paths[$alias] ?? '';
    }

    public static function addPublicKeyPath($alias, $path)
    {
        self::$public_key_paths[$alias] = $path;
    }

    public static function addPrivateKeyPath($alias, $path)
    {
        self::$private_key_paths[$alias] = $path;
    }
}
