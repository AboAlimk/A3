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
namespace A3App;

class A3Array{
    public static function is($arr){
        return is_array($arr);
    }
    public static function count($arr){
        return count($arr);
    }
    public static function keys($arr){
        return array_keys($arr);
    }
    public static function values($arr){
        return array_values($arr);
    }
    public static function get( $arr , $path , $def = false ){
        $path = is_array($path)?$path:[$path];
        if(!$arr||!is_array($arr)){
            return $def;
        }
        for($i=0;$i<count($path);$i++){
            $ii = $path[$i];
            if(is_array($arr)&&array_key_exists($ii,$arr)){
                $arr = $arr[$ii];
            }else{
                return $def;
            }
        }
        return $arr;
    }
    public static function set( $arr , $path , $value ){
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
    public static function merge(){
        return call_user_func_array('array_merge',func_get_args());
    }
    public static function keysCase($arr,$case){
        return array_change_key_case($arr,$case);
    }
    public static function chunk($arr,$limit){
        return array_chunk($arr,$limit);
    }
    public static function limit($arr,$limit){
        return array_slice($arr,0,$limit);
    }
    public static function rLimit($arr,$limit){
        return array_slice($arr,-$limit,$limit);
    }
    public static function diff(){
        return call_user_func_array('array_diff',func_get_args());
    }
    public static function contains($arr,$item){
        return in_array($item,$arr);
    }
    public static function containsKey($arr,$key){
        return array_key_exists($key,$arr);
    }
    public static function random($arr,$count){
        return array_rand($arr,$count);
    }
    public static function reverse($arr){
        return array_reverse($arr);
    }
    public static function sort($arr,$sort){
        if($sort === A3AS_ACS){
            sort($arr);
        }else if($sort === A3AS_DESCS){
            rsort($arr);
        }else if($sort === A3AS_KACS){
            ksort($arr);
        }else if($sort === A3AS_KDESCS){
            krsort($arr);
        }   
        return $arr;
    }
    public static function unique($arr){
        return array_unique($arr);
    }
    public static function first($arr){
        return reset($arr);
    }
    public static function end($arr){
        return end($arr);
    }
    public static function removeFirst($arr){
        array_shift($arr);
        return $arr;
    }
    public static function removeEnd($arr){
        array_pop($arr);
        return $arr;
    }
    public static function shuffle($arr){
        shuffle($arr);
        return $arr;
    }
    public static function addStart(){
        $arr = func_get_arg(0);
        $push = self::removeFirst(func_get_args());
        return self::merge($push,$arr);
    }
    public static function addEnd(){
        $arr = func_get_arg(0);
        $push = self::removeFirst(func_get_args());
        return self::merge($arr,$push);
    }
}