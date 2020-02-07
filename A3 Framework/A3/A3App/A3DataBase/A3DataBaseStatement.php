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
use A3String;

class A3DataBaseStatement{
    private $connection;
    private $statement;
    public function __construct($connection,$statement,$replace = null){
        $this->connection = $connection;
        $this->statement = is_null($replace) ? $statement : A3String::replace($statement,'?',$replace);
    }
    public function get(){
        return new A3DataBaseGet(A3DataBaseQuery::query($this->connection,$this->statement));
    }
    public function getResult(){
        return A3DataBaseQuery::query($this->connection,$this->statement);
    }
    public function __call($method,$parameters){
        self::__error('undefined_method',[$method],__FUNCTION__);
    }
    private static function __error($text,$replace,$a3Function){
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => $replace,
            'a3Class' => 'A3DataBaseStatement',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }
}