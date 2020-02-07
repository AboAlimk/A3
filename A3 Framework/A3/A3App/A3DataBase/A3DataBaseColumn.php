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

class A3DataBaseColumn{
    private $connection;
    private $table_name;
    private $column_name;
    public function __construct($connection,$table_name,$column_name){
        $this->connection = $connection;
        $this->table_name = $table_name;
        $this->column_name = $column_name;
    }
    public function drop(){
        return A3DataBaseQuery::query($this->connection,'ALTER TABLE '.$this->table_name.' DROP COLUMN '.$this->column_name);
    }
    public function rename($name = null){
        if(is_null($name)){
            self::__error('error_parameters_count',[__FUNCTION__,'1'],__FUNCTION__);
        }
        if(!is_string($name) || $name === ''){
            self::__error('error_parameter_type',['name','string'],__FUNCTION__);
        }
        if(!preg_match("/^[a-zA-Z0-9_]+$/",$name)){
            self::__error('a3database_error_name',[$name],__FUNCTION__);
        }
        $type = $this->getColumnData($this->table_name,$this->column_name);
        if($type){
            return A3DataBaseQuery::query($this->connection,'ALTER TABLE '.$this->table_name.' CHANGE '.$this->column_name.' '.$name.' '.$type);
        }
        return false;
    }
    private function getColumnData($table_name,$name){
        $data = A3DataBaseQuery::query($this->connection,"SHOW FULL COLUMNS FROM $table_name WHERE Field='$name'");
        if($data && count($data)){
            return A3GetPathValue($data[0],'Type');
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
            'a3Class' => 'A3DataBaseColumn',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }
}