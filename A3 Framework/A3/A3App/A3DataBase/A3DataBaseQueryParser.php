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

use A3App\A3Callable;
use A3String;

class A3DataBaseQueryParser
{

    public static function insert($table_name, $insert): bool|string
    {
        if (is_array($insert) && count($insert)) {
            if (!empty($insert[0]) && is_array($insert[0])) {
                $out = '';
                foreach ($insert as $item) {
                    $out .= self::getSingleInsert($table_name, $item);
                }
                return $out;
            } else {
                return self::getSingleInsert($table_name, $insert);
            }
        }
        return false;
    }

    private static function getSingleInsert($table_name, $insert): string
    {
        $keys = [];
        $values = [];
        foreach ($insert as $key => $value) {
            $keys[] = $key;
            $values[] = "'$value'";
        }
        $keys = implode(',', $keys);
        $keys = "($keys) VALUES ";
        $values = implode(',', $values);
        $values = "($values)";
        return "INSERT into `$table_name` $keys $values;";
    }

    public static function where($where, $vars = null): bool|string
    {
        if (is_callable($where)) {
            $whereSt = new A3DataBaseWhere();
            A3Callable::call($where, $whereSt, $vars);
            return " WHERE (" . $whereSt->getWhere() . ")";
        } else if (is_string($where)) {
            return " WHERE (" . (is_null($vars) ? $where : A3String::replace($where, '?', $vars)) . ")";
        }
        return false;
    }

    public static function update($update): bool|string
    {
        $out = [];
        if (is_array($update) && count($update)) {
            foreach ($update as $key => $value) {
                $out[] = "$key = '$value'";
            }
            if (count($out)) {
                $out = implode(',', $out);
                return " SET $out";
            }
        }
        return false;
    }

    public static function select($select): string
    {
        if (is_array($select) && count($select)) {
            $out = implode(',', $select);
        } else {
            $out = '*';
        }
        return "SELECT $out";
    }

    public static function orderBy($orderBy): string
    {
        if (is_array($orderBy) && count($orderBy)) {
            $singleOrderBy = self::getOrderBy($orderBy);
            if ($singleOrderBy !== false) {
                return " ORDER BY $singleOrderBy";
            } else {
                $arr = [];
                foreach ($orderBy as $item) {
                    $singleOrderBy = self::getOrderBy($item);
                    if ($singleOrderBy !== false) {
                        $arr[] = $singleOrderBy;
                    }
                }
                $arr = implode(',', $arr);
                return " ORDER BY $arr";
            }
        }
        return '';
    }

    private static function getOrderBy($orderBy): bool|string
    {
        if (count($orderBy) >= 1 && is_string($orderBy[0])) {
            $id = $orderBy[0];
            $ascending = !isset($orderBy[1]) || $orderBy[1];
            $ascending = $ascending ? ' ASC' : ' DESC';
            return $id . $ascending;
        }
        return false;
    }

    public static function limit($limit, $offset): string
    {
        if (!is_null($limit)) {
            if (!is_null($offset)) {
                $limit = $offset . ',' . $limit;
            }
            return " LIMIT $limit";
        }
        return '';
    }

    public static function groupBy($groupBy): string
    {
        if (is_array($groupBy) && count($groupBy)) {
            $groupBy = implode(',', $groupBy);
            return " GROUP BY $groupBy";
        }
        return '';
    }

}