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

class A3DataBaseColumnData{
    private $column_name;
    private $column_type;
    private $column_length;
    private $column_charset;
    private $column_collation;
    private $column_notnull;
    private $column_unique;
    private $column_default;
    private $column_comment;
    public static $columnTypes = ['int','tinyInt','smallInt','mediumInt','bigInt','decimal','float','double','boolean','date','datetime','timestap','time','char','varchar','tinyText','text','mediumText','longText'];
    public function __construct($column_name,$column_type){
        $this->column_name = $column_name;
        $this->column_type = strtoupper($column_type);
    }
    public function length($column_length){
        $this->column_length = $column_length;
        return $this;
    }
    public function charset($column_charset){
        if(in_array(strtolower($this->column_type),['char','varchar','tinyText','text','mediumText','longText'])){
            $this->column_charset = $column_charset;
        }
        return $this;
    }
    public function collation($column_collation){
        if(in_array(strtolower($this->column_type),['char','varchar','tinyText','text','mediumText','longText'])){
            $this->column_collation = $column_collation;
        }
        return $this;
    }
    public function notNull(){
        $this->column_notnull = true;
        return $this;
    }
    public function unique(){
        $this->column_unique = true;
        return $this;
    }
    public function default($column_default){
        $this->column_default = $column_default;
        return $this;
    }
    public function comment($column_comment){
        $this->column_comment = $column_comment;
        return $this;
    }
    public function getData($isPrimary = false){
        $text = $this->column_name.' '.$this->column_type;
        if($this->column_length){
            $text .= '('.$this->column_length.')';
        }
        if($this->column_charset && !$isPrimary){
            $text .= ' CHARACTER SET '.$this->column_charset;
        }
        if($this->column_collation && !$isPrimary){
            $text .= ' COLLATE '.$this->column_collation;
        }
        if($this->column_notnull && !$isPrimary){
            $text .= ' NOT NULL';
        }
        if($this->column_unique){
            $text .= ' UNIQUE';
        }
        if($this->column_default && !$isPrimary){
            $text .= " DEFAULT '".$this->column_default."'";
        }
        if($isPrimary){
            $text .= " UNSIGNED AUTO_INCREMENT PRIMARY KEY";
        }
        if($this->column_comment){
            $text .= " COMMENT '".$this->column_comment."'";
        }
        return $text;
    }
    public function __call($method,$parameters){
        self::__error('undefined_method',[$method],__FUNCTION__);
    }
    private static function __error($text,$replace,$a3Function){
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => $replace,
            'a3Class' => 'A3DataBaseColumnData',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }
}