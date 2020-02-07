<?php
/**
 *
 *   A3 Framework
 *   Version: 1.0
 *   Date: 02-2020
 *   Author: Abdulsattar Alkhalaf
 *   AboAlimk@gmail.com
 *
 */
namespace A3App\A3DataBase;

class A3DataBaseConnections{
    private static $connections = [];
    public static function add($connectionId,$connection){
        self::$connections[$connectionId] = $connection;
    }
    public static function exists($connectionId){
        return array_key_exists($connectionId,self::$connections);
    }
    public static function get($connectionId){
        $connection = array_key_exists($connectionId,self::$connections) ? self::$connections[$connectionId] : false;
        return ($connection && is_object($connection) && get_class($connection) === 'mysqli') ? $connection : false;
    }
    public static function closeAll(){
        foreach(self::$connections as $connectionId=>$connection){
            $connection->close();
        }
        self::$connections = [];
    }
}