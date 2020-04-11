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

class A3DataBaseTable{
    private $connection;
    private $table_name;
    public function __construct($connection,$table_name){
        $this->connection = $connection;
        $this->table_name = $table_name;
    }
    public function select(){
        $select = func_get_args();
        if(func_num_args() === 0){
            $select = '*';
        }else if(func_num_args() === 1 && is_array(func_get_arg(0))){
            $select = func_get_arg(0);
        }
        return new A3DataBaseTableSelect($this->connection,$this->table_name,func_get_args());
    }
    public function insert($insert = null,$getLastId = false){
        if(is_null($insert)){
            self::__error('error_parameters_count',[__FUNCTION__,'1'],__FUNCTION__);
        }
        if(!is_array($insert) || !count($insert)){
            self::__error('error_parameter_type',['insert values','array'],__FUNCTION__);
        }
        $insert = array_map(function($value){
            return addslashes($value);
        },$insert);
        $statement = A3DataBaseQueryParser::insert($this->table_name,$insert);
        if($statement!==false){
            $query = A3DataBaseQuery::multiQuery($this->connection,$statement);
            if($getLastId){
                return $insertId = $this->connection->getConnection()->insert_id;
            }else{
                return $query;
            }
        }
        return false;
    }
    public function paginate($perPage = 10,$currentPage = 1){
        if(!is_numeric($perPage)){
            self::__error('error_parameter_type',['items per page','number'],__FUNCTION__);
        }
        if(!is_numeric($currentPage)){
            self::__error('error_parameter_type',['current page','number'],__FUNCTION__);
        }
        if($perPage < 0){
            $perPage = 10;
        }
        if($currentPage < 1){
            $currentPage = 1;
        }
        $startOffset = ($currentPage - 1) * $perPage;
        return new A3DataBaseTablePaginate($this->connection,$this->table_name,$perPage,$currentPage,$startOffset);
    }
    public function delete(){
        return new A3DataBaseTableDelete($this->connection,$this->table_name);
    }
    public function update($update = null){
        if(is_null($update)){
            self::__error('error_parameters_count',[__FUNCTION__,'1'],__FUNCTION__);
        }
        if(!is_array($update) || !count($update)){
            self::__error('error_parameter_type',['update values','array'],__FUNCTION__);
        }
        return new A3DataBaseTableUpdate($this->connection,$this->table_name,$update);
    }
    public function truncate(){
        return A3DataBaseQuery::query($this->connection,'TRUNCATE TABLE '.$this->table_name);
    }
    public function drop(){
        return A3DataBaseQuery::query($this->connection,'DROP TABLE '.$this->table_name);
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
        return A3DataBaseQuery::query($this->connection,'ALTER TABLE '.$this->table_name.' RENAME TO '.$name);
    }
    public function createColumn($name = null,$type = null,$callable = null,$vars = null){
        $types = A3DataBaseColumnData::$columnTypes;
        $types = array_map('strtolower', $types);
        if(is_null($name) || is_null($type) || is_null($callable)){
            self::__error('error_parameters_count',[__FUNCTION__,'3'],__FUNCTION__);
        }
        if(!is_string($name) || $name === ''){
            self::__error('error_parameter_type',['name','string'],__FUNCTION__);
        }
        if(!preg_match("/^[a-zA-Z0-9_]+$/",$name)){
            self::__error('a3database_error_name',[$name],__FUNCTION__);
        }
        if(!is_string($type) || $type === ''){
            self::__error('error_parameter_type',['type','string'],__FUNCTION__);
        }
        if(!in_array(strtolower($type),$types)){
            self::__error('a3database_error_column_type',[$type],__FUNCTION__);
        }
        if(!is_callable($callable)){
            self::__error('error_parameter_type',['column data','callable'],__FUNCTION__);
        }
        $column = new A3DataBaseColumnData($name,$type);
        A3Callable::call($callable,$column,$vars);
        return A3DataBaseQuery::query($this->connection, 'ALTER TABLE '.$this->table_name.' ADD '.$column->getData());
    }
    public function column($name = null){
        if(is_null($name)){
            self::__error('error_parameters_count',[__FUNCTION__,'1'],__FUNCTION__);
        }
        if(!is_string($name) || $name === ''){
            self::__error('error_parameter_type',['name','string'],__FUNCTION__);
        }
        return new A3DataBaseColumn($this->connection,$this->table_name,$name);
    }
    public function __call($method,$parameters){
        self::__error('undefined_method',[$method],__FUNCTION__);
    }    
    private static function __error($text,$replace,$a3Function){
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => $replace,
            'a3Class' => 'A3DataBaseTable',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }
}
