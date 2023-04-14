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
        $result = $connection->getConnection()->query($statement);
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
        $ids = [];
        $result = $connection->getConnection()->multi_query($statement);
        if ($result) {
            do {
                if ($connection->getConnection()->insert_id) {
                    $ids[] = $connection->getConnection()->insert_id;
                }
                if ($connection->getConnection()->more_results()) {
                    if ($_result = $connection->getConnection()->store_result()) {
                        $_result->free();
                    }
                }
            } while ($connection->getConnection()->next_result());
        }
        return $getLastId ? $ids : $result;
    }

}