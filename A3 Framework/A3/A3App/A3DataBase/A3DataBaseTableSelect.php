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

class A3DataBaseTableSelect{
    private $connection;
    private $table_name;
    private $select;
    private $where;
    private $orderby;
    private $limit;
    private $offset;
    private $groupby;
    public function __construct($connection,$table_name,$select){
        $this->connection = $connection;
        $this->table_name = $table_name;
        $this->select = $select;
    }
    public function orderby($id = null, $ascending = true){
        if(is_null($id)){
            self::__error('error_parameters_count',[__FUNCTION__,'1 or 2'],__FUNCTION__);
        }
        if((!is_string($id) || $id === '') && (!is_array($id) || !count($id))){
            self::__error('error_parameter_type',['order id','string or array'],__FUNCTION__);
        }
        if(is_string($id)){
            $this->orderby = [$id , $ascending];
        }else if(is_array($id)){
            $this->orderby = $id;
        }
        return $this;
    }
    public function limit($limit = null,$offset = null){
        if(is_null($limit)){
            self::__error('error_parameters_count',[__FUNCTION__,'1 or 2'],__FUNCTION__);
        }
        if(!is_numeric($limit)){
            self::__error('error_parameter_type',['limit','number'],__FUNCTION__);
        }
        if(!is_null($offset) && !is_numeric($offset)){
            self::__error('error_parameter_type',['limit offset','number'],__FUNCTION__);
        }
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }
    public function groupby(){
        $this->groupby = func_get_args();
        return $this;
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
    public function exists(){
        return A3DataBaseQuery::existsQuery($this->connection,$this->queryBuilder(true));
    }
    public function get(){
        return new A3DataBaseGet(A3DataBaseQuery::query($this->connection,$this->queryBuilder()));
    }
    private function queryBuilder($exists = false){
        $select = $exists ? 'SELECT COUNT(*)' : A3DataBaseQueryParser::select($this->select);
        $from = ' FROM '.$this->table_name;
        $orderby = A3DataBaseQueryParser::orderby($this->orderby);
        $groupby = A3DataBaseQueryParser::groupby($this->groupby);
        $limit = A3DataBaseQueryParser::limit($this->limit,$this->offset);
        return $select.$from.$this->where.$groupby.$orderby.$limit;
    }
    public function __call($method,$parameters){
        self::__error('undefined_method',[$method],__FUNCTION__);
    }
    private static function __error($text,$replace,$a3Function){
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => $replace,
            'a3Class' => 'A3DataBaseTableSelect',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }
}