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

class A3DataBaseGet{
    private $result;
    public function __construct($result){
        $this->result = is_array($result) ? $result : [];
    }
    public function asArray(){
        $this->result = array_map(function($arr){
            return array_values($arr);
        },$this->result);
        return $this;
    }
    public function all(){
        return $this->result;
    }
    public function count(){
        return count($this->result);
    }
    public function index($index = null){
        if(!is_null($index)){
            return count($this->result) >= $index ? $this->result[$index] : null;
        }
        return null;
    }
    public function first($length = null){
        if(is_null($length)){
            return count($this->result) ? reset($this->result) : null;
        }
        return count($this->result) >= $length ? array_slice($this->result, 0, $length) : null;
    }
    public function last($length = null){
        if(is_null($length)){
            return count($this->result) ? end($this->result) : null;
        }
        return count($this->result) >= $length ? array_slice($this->result, -$length, $length) : null;
    }
    public function __call($method,$parameters){
        self::__error('undefined_method',[$method],__FUNCTION__);
    }
    private static function __error($text,$replace,$a3Function){
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => $replace,
            'a3Class' => 'A3DataBaseGet',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }
}