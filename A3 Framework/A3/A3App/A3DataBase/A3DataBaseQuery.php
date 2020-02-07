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

class A3DataBaseQuery{
    public static function query($connection,$statement){
        $result = $connection->getConnection()->query($statement);
        if($result && is_object($result) && get_class($result) === 'mysqli_result'){
            return $result->fetch_all(MYSQLI_ASSOC);
        }else if(is_bool($result)){
            return $result;
        }
        return false;
    }
    public static function existsQuery($connection,$statement){
        return self::countQuery($connection,$statement) > 0;
    }
    public static function countQuery($connection,$statement){
        $result = $connection->getConnection()->query($statement);
        if($result && is_object($result) && get_class($result) === 'mysqli_result'){
            $result = $result->fetch_row();
            if($result && is_array($result) && count($result)){
                return intval($result[0]);
            }
        }
        return 0;
    }
    public static function multiQuery($connection,$statement){
        return $connection->getConnection()->multi_query($statement);
    }
}