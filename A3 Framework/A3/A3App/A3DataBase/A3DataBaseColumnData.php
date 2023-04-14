<?php
/**
 *
 *   A3 Framework
 *   Version: 1.2
 *   Date: 04-2023
 *   Author: Abdulsattar Alkhalaf
 *   AboAlimk@gmail.com
 *
 */

namespace A3App\A3DataBase;

use A3Error;

class A3DataBaseColumnData
{

    private string $columnName;
    private string $columnType;
    private int $columnLength;
    private string $columnCharset;
    private string $columnCollation;
    private bool $columnNotNull;
    private bool $columnUnique;
    private mixed $columnDefault;
    private mixed $columnComment;

    public static array $columnTypes = ['int', 'tinyInt', 'smallInt', 'mediumInt', 'bigInt', 'decimal', 'float', 'double', 'boolean', 'date', 'datetime', 'timestamp', 'time', 'char', 'varchar', 'tinyText', 'text', 'mediumText', 'longText'];

    public function __construct($column_name, $column_type)
    {
        $this->columnName = $column_name;
        $this->columnType = strtoupper($column_type);
    }

    public function length($column_length): A3DataBaseColumnData
    {
        $this->columnLength = $column_length;
        return $this;
    }

    public function charset($column_charset): A3DataBaseColumnData
    {
        if (in_array(strtolower($this->columnType), ['char', 'varchar', 'tinyText', 'text', 'mediumText', 'longText'])) {
            $this->columnCharset = $column_charset;
        }
        return $this;
    }

    public function collation($column_collation): A3DataBaseColumnData
    {
        if (in_array(strtolower($this->columnType), ['char', 'varchar', 'tinyText', 'text', 'mediumText', 'longText'])) {
            $this->columnCollation = $column_collation;
        }
        return $this;
    }

    public function notNull(): A3DataBaseColumnData
    {
        $this->columnNotNull = true;
        return $this;
    }

    public function unique(): A3DataBaseColumnData
    {
        $this->columnUnique = true;
        return $this;
    }

    public function default($column_default): A3DataBaseColumnData
    {
        $this->columnDefault = $column_default;
        return $this;
    }

    public function comment($column_comment): A3DataBaseColumnData
    {
        $this->columnComment = $column_comment;
        return $this;
    }

    public function getData($isPrimary = false): string
    {
        $text = "`$this->columnName` $this->columnType";
        if (isset($this->columnLength) && $this->columnLength) {
            $text .= "($this->columnLength)";
        }
        if (isset($this->columnCharset) && $this->columnCharset && !$isPrimary) {
            $text .= " CHARACTER SET $this->columnCharset";
        }
        if (isset($this->columnCollation) && $this->columnCollation && !$isPrimary) {
            $text .= " COLLATE $this->columnCollation";
        }
        if (isset($this->columnNotNull) && $this->columnNotNull && !$isPrimary) {
            $text .= " NOT NULL";
        }
        if (isset($this->columnUnique) && $this->columnUnique) {
            $text .= " UNIQUE";
        }
        if (isset($this->columnDefault) && $this->columnDefault && !$isPrimary) {
            $text .= " DEFAULT '$this->columnDefault'";
        }
        if ($isPrimary) {
            $text .= " UNSIGNED AUTO_INCREMENT PRIMARY KEY";
        }
        if (isset($this->columnComment) && $this->columnComment) {
            $text .= " COMMENT '$this->columnComment'";
        }
        return $text;
    }

    public function __call($method, $parameters)
    {
        self::__error([$method], __FUNCTION__);
    }

    private static function __error($replace, $a3Function): void
    {
        A3Error::errorTrigger([
            'text' => 'undefined_method',
            'replace' => $replace,
            'a3Class' => 'A3DataBaseColumnData',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }

}