<?php
/**
 *
 *   A3 Framework
 *   Version: 1.2
 *   Date: 04-2023
 *   Author: Abdulsattar Alkhalaf
 *   AboAlimk@gmail.com
 *
 */

namespace A3App\A3DataBase;

use mysqli;

class A3DataBaseConnections
{

    private static array $connections = [];

    public static function add($connectionId, $connection): void
    {
        self::$connections[$connectionId] = $connection;
    }

    public static function exists($connectionId): bool
    {
        return array_key_exists($connectionId, self::$connections);
    }

    public static function get($connectionId): bool|mysqli
    {
        $connection = array_key_exists($connectionId, self::$connections) ? self::$connections[$connectionId] : false;
        return (is_object($connection) && get_class($connection) === mysqli::class) ? $connection : false;
    }

    public static function closeAll(): void
    {
        foreach (self::$connections as $connection) {
            $connection->close();
        }
        self::$connections = [];
    }

}