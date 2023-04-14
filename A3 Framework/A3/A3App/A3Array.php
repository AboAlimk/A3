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

namespace A3App;

class A3Array
{

    public static function is($arr): bool
    {
        return is_array($arr);
    }

    public static function count(array $arr): int
    {
        return count($arr);
    }

    public static function keys(array $arr): array
    {
        return array_keys($arr);
    }

    public static function values(array $arr): array
    {
        return array_values($arr);
    }

    public static function get($arr, $path, $def = false)
    {
        $path = is_array($path)?$path:[$path];
        if(!$arr||!is_array($arr)){
            return $def;
        }
        for($i=0;$i<count($path);$i++){
            $e = $path[$i];
            if(is_array($arr)&&array_key_exists($e,$arr)){
                $arr = $arr[$e];
            }else{
                return $def;
            }
        }
        return $arr;
    }

    public static function set(array $arr, $path, $value)
    {
        $result = $arr;
        if (empty($path)) {
            return $result;
        }
        $tmp = &$result;
        while (count($path) > 0) {
            $key = array_shift($path);
            if (!is_array($tmp)) {
                $tmp = [];
            }
            $tmp = &$tmp[$key];
        }
        $tmp = $value;
        return $result;
    }

    public static function merge(): array
    {
        return call_user_func_array('array_merge', func_get_args());
    }

    public static function keysCase(array $arr, $case): array
    {
        return array_change_key_case($arr, $case);
    }

    public static function chunk(array $arr, $limit): array
    {
        return array_chunk($arr, $limit);
    }

    public static function limit(array $arr, $limit): array
    {
        return array_slice($arr, 0, $limit);
    }

    public static function rLimit(array $arr, $limit): array
    {
        return array_slice($arr, -$limit, $limit);
    }

    public static function diff(): array
    {
        return call_user_func_array('array_diff', func_get_args());
    }

    public static function contains(array $arr, $item): bool
    {
        return in_array($item, $arr);
    }

    public static function containsKey(array $arr, $key): bool
    {
        return array_key_exists($key, $arr);
    }

    public static function random(array $arr, $count): int|array|string
    {
        return array_rand($arr, $count);
    }

    public static function reverse(array $arr): array
    {
        return array_reverse($arr);
    }

    public static function sort(array $arr, $sort): array
    {
        if ($sort === A3AS_ACS) {
            sort($arr);
        } else if ($sort === A3AS_DESCS) {
            rsort($arr);
        } else if ($sort === A3AS_KACS) {
            ksort($arr);
        } else if ($sort === A3AS_KDESCS) {
            krsort($arr);
        }
        return $arr;
    }

    public static function unique(array $arr): array
    {
        return array_unique($arr);
    }

    public static function first(array $arr)
    {
        return reset($arr);
    }

    public static function end(array $arr)
    {
        return end($arr);
    }

    public static function removeFirst(array $arr): array
    {
        array_shift($arr);
        return $arr;
    }

    public static function removeEnd(array $arr): array
    {
        array_pop($arr);
        return $arr;
    }

    public static function shuffle(array $arr): array
    {
        shuffle($arr);
        return $arr;
    }

    public static function addStart(): array
    {
        $arr = func_get_arg(0);
        $push = self::removeFirst(func_get_args());
        return self::merge($push,$arr);
    }

    public static function addEnd(): array
    {
        $arr = func_get_arg(0);
        $push = self::removeFirst(func_get_args());
        return self::merge($arr, $push);
    }

}