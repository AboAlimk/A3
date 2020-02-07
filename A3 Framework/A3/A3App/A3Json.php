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

class A3Json{
    public static function json(){
        $arguments = func_get_args();
        if($arguments && is_array($arguments) && count($arguments)){
            return json_encode($arguments);
        }
    }
    public static function jsonX(){
        $arguments = func_get_args();
        $arr = [];
        for($i=0;$i<count($arguments);$i++){
            $arr["x".($i+1)]=$arguments[$i];
        }
        return json_encode($arr);
    }
}