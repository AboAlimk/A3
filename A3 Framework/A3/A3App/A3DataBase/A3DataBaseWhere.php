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

class A3DataBaseWhere{
    private $whereObject = [];
    public function __construct(){}
    public function where($key,$comparer = null,$value = null){
        $this->addWhere('and',$key,$comparer,$value);
        return $this;
    }
    public function orWhere($key,$comparer = null,$value = null){
        $this->addWhere('or',$key,$comparer,$value);
        return $this;
    }
    private function addWhere($and_or,$key,$comparer,$value = null){
        if(is_callable($key)){
            $where = new A3DataBaseWhere();
            A3Callable::call($key,$where,is_array($comparer)?$comparer:null);
            $where = [$and_or,$where->getWhere()];
        }else{
            if(is_null($comparer)){
                return $this;
            }
            if(is_null($value)){
                $value = $comparer;
                $comparer = '=';
            }
            $comparer = ' '.trim($comparer).' ';
            $where = $key.$comparer.'"'.$value.'"';
            $where = [$and_or,$where];
        }
        $this->whereObject[] = $where;
        return $this;
    }
    public function between($key,$min,$max){
        $this->whereObject[] = ['and',$key.' BETWEEN "'.$min.'" AND "'.$max.'"'];
        return $this;
    }
    public function orBetween($key,$min,$max){
        $this->whereObject[] = ['or',$key.' BETWEEN "'.$min.'" AND "'.$max.'"'];
        return $this;
    }
    public function notBetween($key,$min,$max){
        $this->whereObject[] = ['and',$key.' NOT BETWEEN "'.$min.'" AND "'.$max.'"'];
        return $this;
    }
    public function orNotBetween($key,$min,$max){
        $this->whereObject[] = ['or',$key.' NOT BETWEEN "'.$min.'" AND "'.$max.'"'];
        return $this;
    }
    public function in($key,$arr){
        if(is_array($arr) && count($arr)){
            $arr = $this->fixArray($arr);
            $this->whereObject[] = ['and',$key.' IN ('.implode(',',$arr).')'];
        }
        return $this;
    }
    public function orIn($key,$arr){
        if(is_array($arr) && count($arr)){
            $arr = $this->fixArray($arr);
            $this->whereObject[] = ['or',$key.' IN ('.implode(',',$arr).')'];
        }
        return $this;
    }
    public function notIn($key,$arr){
        if(is_array($arr) && count($arr)){
            $arr = $this->fixArray($arr);
            $this->whereObject[] = ['and',$key.' NOT IN ('.implode(',',$arr).')'];
        }
        return $this;
    }
    public function orNotIn($key,$arr){
        if(is_array($arr) && count($arr)){
            $arr = $this->fixArray($arr);
            $this->whereObject[] = ['or',$key.' NOT IN ('.implode(',',$arr).')'];
        }
        return $this;
    }
    private function fixArray($arr){
        return array_map(function($value){
            return "'".$value."'";
        },$arr);
    }
    public function getWhere(){
        $out = '';
        foreach($this->whereObject as $key=>$where){
            if($key === 0){
                $out .= '('.$where[1].')';
            }else{
                $out .= ' '.strtoupper($where[0]).' ('.$where[1].')';
            }
        }
        return $out;
    }
    public function __call($method,$parameters){
        self::__error('a3database_error_table_where_method',$method,__FUNCTION__);
    }
    private static function __error($text,$replace,$a3Function){
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => [$replace],
            'a3Class' => 'A3DataBaseWhere',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }
}