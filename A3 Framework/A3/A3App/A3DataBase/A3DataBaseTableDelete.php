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
use A3App\A3Callable;
use A3Error;

class A3DataBaseTableDelete{
    private $connection;
    private $table_name;
    private $where;
    public function __construct($connection,$table_name){
        $this->connection = $connection;
        $this->table_name = $table_name;
    }
    public function where($where = null,$vars = null){
        if(is_null($where)){
            self::__error('error_parameters_count',[__FUNCTION__,'1'],__FUNCTION__);
        }
        if((!is_string($where) || $where === '') && !is_callable($where)){
            self::__error('error_parameter_type',['where','string or callable'],__FUNCTION__);
        }
        $this->where = A3DataBaseQueryParser::where($where,$vars);
        return $this;
    }
    public function do(){
        if($this->where !== false){
            return A3DataBaseQuery::query($this->connection,'DELETE from '.$this->table_name.$this->where);
        }
        return false;
    }
    public function __call($method,$parameters){
        self::__error('undefined_method',[$method],__FUNCTION__);
    }
    private static function __error($text,$replace,$a3Function){
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => $replace,
            'a3Class' => 'A3DataBaseTableDelete',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }
}