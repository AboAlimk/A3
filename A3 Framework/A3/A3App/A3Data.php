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
use A3String;

class A3Data{
    private static $A3DataArray = [];
    public static function process($key, $value){
        if($value){
            self::A3DataSetItem($key,$value);
        }else{
            if(A3String::is($key)){
                $path = A3String::getPath($key);
                return A3GetPathValue(self::$A3DataArray, $path,null);
            }else if(is_array($key)){
                foreach($key as $k=>$v){
                    self::A3DataSetItem($k,$v);
                }
            }
        }
    }
    private static function A3DataSetItem($key,$value){
        self::$A3DataArray = A3SetPathValue(self::$A3DataArray,A3String::getPath($key),$value);
    }
    public static function loadDataFile($name,$file){
        self::$A3DataArray[$name] = require $file;
    }
}