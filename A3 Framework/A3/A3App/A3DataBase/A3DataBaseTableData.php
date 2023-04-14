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

class A3DataBaseTableData
{

    private string $tableName;
    private string $charset;
    private string $collation;
    private string $autoIncrement;
    private array $columns = [];

    public function __construct($table_name)
    {
        $this->tableName = $table_name;
        $this->charset = A3Settings('mysqli.charset');
        $this->collation = A3Settings('mysqli.collation');
    }

    public function __call($method, $parameters)
    {
        $tableMethods = ['charset', 'collation', 'autoIncrement'];
        if (!in_array($method, $tableMethods) && !in_array($method, A3DataBaseColumnData::$columnTypes)) {
            self::__error('undefined_method', [$method], __FUNCTION__);
        } else if (empty($parameters) || empty($parameters[0])) {
            self::__error('error_parameters_count', [$method, 1], __FUNCTION__);
        } else if (!is_string($parameters[0])) {
            self::__error('error_parameter_type', [$method, 'string'], __FUNCTION__);
        }
        if (in_array($method, $tableMethods)) {
            $this->{$method} = $parameters[0];
        } else if (in_array($method, A3DataBaseColumnData::$columnTypes)) {
            $name = strtolower($parameters[0]);
            if (!preg_match("/^[a-zA-Z0-9_]+$/", $name)) {
                self::__error('a3database_error_name', [$name], __FUNCTION__);
            }
            if (array_key_exists($name, $this->columns)) {
                self::__error('a3database_error_column_name_exists', [$parameters[0]], __FUNCTION__);
            }
            $column = new A3DataBaseColumnData($parameters[0], $method);
            $this->columns[$name] = $column;
            return $column;
        }
    }

    public function getData(): string
    {
        $data = [];
        foreach ($this->columns as $name => $column) {
            $data[] = $column->getData(isset($this->autoIncrement) && strtolower($name) === strtolower($this->autoIncrement));
        }
        $data = implode(",", $data);
        $text = "CREATE TABLE IF NOT EXISTS `$this->tableName` ($data)";
        if ($this->charset) {
            $text .= " DEFAULT CHARSET = $this->charset";
        }
        if ($this->collation) {
            $text .= " COLLATE = $this->collation";
        }
        return $text;
    }

    private static function __error($text, $replace, $a3Function): void
    {
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => $replace,
            'a3Class' => 'A3DataBaseTableData',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }

}