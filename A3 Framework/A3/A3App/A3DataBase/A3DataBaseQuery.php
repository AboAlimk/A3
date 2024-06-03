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

class A3DataBaseQuery
{

    public static function query($connection, $statement): bool|array
    {
        $result = self::executeQuery($connection, $statement);
        if (is_object($result) && get_class($result) === 'mysqli_result') {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else if (is_bool($result)) {
            return $result;
        }
        return false;
    }

    public static function existsQuery($connection, $statement): bool
    {
        return self::countQuery($connection, $statement) > 0;
    }

    public static function countQuery($connection, $statement): int
    {
        $result = $connection->getConnection()->query($statement);
        if (is_object($result) && get_class($result) === 'mysqli_result') {
            $result = $result->fetch_row();
            if ($result && is_array($result) && count($result)) {
                return intval($result[0]);
            }
        }
        return 0;
    }

    public static function multiQuery($connection, $statement, $getLastId)
    {
        if(is_bool($statement)){
            return false;
        }
        if(is_string($statement)){
            $statement = [$statement];
        }
        $ids = [];
        $result = false;
        foreach($statement as $st){
            $result = self::query($connection, $st);
            if($result === true && $connection->getConnection()->insert_id){
                $ids[] = $connection->getConnection()->insert_id;
            }
        }
        return $getLastId ? $ids : $result;
    }

    private static function executeQuery($connection, $statement){
        if (version_compare(phpversion(), '8.2', '>=')) {
            return $connection->getConnection()->execute_query($statement);
        } else {
            return $connection->getConnection()->query($statement);
        }
    }
}
