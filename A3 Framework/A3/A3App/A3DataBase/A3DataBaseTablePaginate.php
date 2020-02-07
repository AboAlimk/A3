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

class A3DataBaseTablePaginate{
    private $connection;
    private $table_name;
    private $items_per_page;
    private $current_page;
    private $start_offset;
    private $select;
    private $where;
    private $orderby;
    public function __construct($connection,$table_name,$items_per_page,$current_page,$start_offset){
        $this->connection = $connection;
        $this->table_name = $table_name;
        $this->items_per_page = $items_per_page;
        $this->current_page = $current_page;
        $this->start_offset = $start_offset;
    }
    public function select(){
        $select = func_get_args();
        if(func_num_args() === 0){
            $select = '*';
        }else if(func_num_args() === 1 && is_array(func_get_arg(0))){
            $select = func_get_arg(0);
        }
        $this->select = $select;
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
    public function getInfo(){
        $totalCount = A3DataBaseQuery::countQuery($this->connection,$this->queryBuilderInfo());
        if($totalCount){
            $total = ceil($totalCount/$this->items_per_page);
            $current = $this->current_page;
            $first = 1;
            $last = $total;
            $prev = $current === $first ? null : $current - 1;
            $next = $current === $last ? null : $current + 1;
            $hasPrev = $current > $first;
            $hasNext = $current < $last;
            return [
                'totalItems' => $totalCount,
                'total' => $total,
                'current' => $current,
                'first' => $first,
                'last' => $last,
                'prev' => $prev,
                'next' => $next,
                'hasPrev' => $hasPrev,
                'hasNext' => $hasNext
            ];
        }
        return false;
    }
    public function get(){
        return new A3DataBaseGet(A3DataBaseQuery::query($this->connection,$this->queryBuilder()));
    }
    private function queryBuilder(){
        $select = A3DataBaseQueryParser::select($this->select);
        $from = ' FROM '.$this->table_name;
        $orderby = A3DataBaseQueryParser::orderby($this->orderby);
        $limit = A3DataBaseQueryParser::limit($this->items_per_page,$this->start_offset);
        return $select.$from.$this->where.$orderby.$limit;
    }
    private function queryBuilderInfo(){
        $select = 'SELECT COUNT(*) FROM '.$this->table_name;
        $orderby = A3DataBaseQueryParser::orderby($this->orderby);
        return $select.$this->where.$orderby;
    }
    public function __call($method,$parameters){
        self::__error('undefined_method',[$method],__FUNCTION__);
    }
    private static function __error($text,$replace,$a3Function){
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => $replace,
            'a3Class' => 'A3DataBaseTablePaginate',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }
}