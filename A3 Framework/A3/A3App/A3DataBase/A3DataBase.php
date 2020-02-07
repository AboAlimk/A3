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
use A3Error;

class A3DataBase{
    public static function connect($connection = null){
        return new A3DataBaseRequest($connection);
    } 
    public function __call($method,$parameters){
        self::__error('a3database_error_method',$method,__FUNCTION__);
    }
    public static function __callStatic($method,$parameters){
        self::__error('a3database_error_method',$method,__FUNCTION__);
    }
    private static function __error($text,$replace,$a3Function){
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => [$replace],
            'a3Class' => 'A3DataBase',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace(),
        ]);
    }
}