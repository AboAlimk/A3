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
use A3String;

class A3DataBaseQueryParser{
    public static function insert($table_name,$insert){
        if(is_array($insert) && count($insert)){
            if(!empty($insert[0]) && is_array($insert[0])){
                $out = '';
                foreach($insert as $item){
                    $out .= self::getSingleInsert($table_name,$item);
                }
                return $out;
            }else{
                return self::getSingleInsert($table_name,$insert);
            }
        }
        return false;
    }
    private static function getSingleInsert($table_name,$insert){
        $keys = [];
        $values = [];
        foreach($insert as $key=>$value){
            $keys[] = $key;
            $values[] = "'".$value."'";
        }
        $keys = '('.implode(',',$keys).') VALUES ';
        $values = '('.implode(',',$values).')';
        return "INSERT into $table_name ".$keys.$values.";";
    }
    public static function where($where,$vars = null){
        if(is_callable($where)){
            $whereSt = new A3DataBaseWhere();
            A3Callable::call($where,$whereSt,$vars);
            return ' WHERE ('.$whereSt->getWhere().')';
        }else if(is_string($where)){
            return ' WHERE ('.(is_null($vars)?$where:A3String::replace($where,'?',$vars)).')';
        }
        return false;
    }
    public static function update($update){
        $out = [];
        if(is_array($update) && count($update)){
            foreach($update as $key=>$value){
                $out[] = "$key = '$value'";
            }
            if(count($out)){
                return ' SET '.implode(',',$out);
            }
        }
        return false;
    }
    public static function select($select){
        $out = '';
        if(is_array($select) && count($select)){
            $out = implode(',',$select);
        }else{
            $out = '*';
        }
        return 'SELECT '.$out;
    }
    public static function orderby($orderby){
        if(is_array($orderby) && count($orderby)){
            $singleOrderby = self::getOrderby($orderby);
            if($singleOrderby !== false){
                return ' ORDER BY '.$singleOrderby;
            }else{
                $arr = [];
                foreach($orderby as $item){
                    $singleOrderby = self::getOrderby($item);
                    if($singleOrderby !== false){
                        $arr[] = $singleOrderby;
                    }
                }
                return ' ORDER BY '.implode(',',$arr);
            }
        }
        return '';
    }
    private static function getOrderby($orderby){
        if(count($orderby) >= 1 && !is_array($orderby[0]) && is_string($orderby[0])){
            $id = $orderby[0];
            $ascending = !isset($orderby[1])?true:(boolean) $orderby[1];
            $ascending = $ascending?' ASC':' DESC';
            return $id.$ascending;
        }
        return false;
    }
    public static function limit($limit,$offset){
        if(!is_null($limit)){
            $limit = $limit;
            if(!is_null($offset)){
                $limit = $offset.','.$limit;
            }
            return ' LIMIT '.$limit;
        }
        return '';
    }
    public static function groupby($groupby){
        if(!is_null($groupby)&&is_array($groupby)&&count($groupby)){
            return ' GROUP BY '.implode(',',$groupby);
        }
        return '';
    }
}