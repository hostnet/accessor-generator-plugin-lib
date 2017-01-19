<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

/**
 * This class provides methods to add your own key on
 */
class KeyRegistry
{
    private static $public_key_paths = [
       'database.table.column' => 'file:///home/msteltenpool/projects/libs/maarten-accessor-generator-plugin//test/Generator/Key/credentials_public_key.pem',
       'database.table.column_again' => 'file:///home/msteltenpool/projects/libs/maarten-accessor-generator-plugin//test/Generator/Key/credentials_public_key.pem',
    ];

    private static $private_key_paths = [
       'database.table.column' => 'file:///home/msteltenpool/projects/libs/maarten-accessor-generator-plugin//test/Generator/Key/credentials_private_key.pem',
       'database.table.column_again' => 'file:///home/msteltenpool/projects/libs/maarten-accessor-generator-plugin//test/Generator/Key/credentials_private_key.pem',
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
