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

class A3DataBaseColumn
{

    private A3DataBaseConnection $connection;
    private string $tableName;
    private string $columnName;

    public function __construct($connection, $table_name, $column_name)
    {
        $this->connection = $connection;
        $this->tableName = $table_name;
        $this->columnName = $column_name;
    }

    public function drop(): array|bool
    {
        return A3DataBaseQuery::query($this->connection, "ALTER TABLE `$this->tableName` DROP COLUMN `$this->columnName`");
    }

    public function rename($name = null): array|bool
    {
        if (is_null($name)) {
            self::__error('error_parameters_count', [__FUNCTION__, '1'], __FUNCTION__);
        }
        if (!is_string($name) || $name === '') {
            self::__error('error_parameter_type', ['name', 'string'], __FUNCTION__);
        }
        if (!preg_match("/^[a-zA-Z0-9_]+$/", $name)) {
            self::__error('a3database_error_name', [$name], __FUNCTION__);
        }
        $type = $this->getColumnData($this->tableName, $this->columnName);
        if ($type) {
            return A3DataBaseQuery::query($this->connection, "ALTER TABLE `$this->tableName` CHANGE `$this->columnName` `$name` $type");
        }
        return false;
    }

    private function getColumnData($table_name, $name): mixed
    {
        $data = A3DataBaseQuery::query($this->connection, "SHOW FULL COLUMNS FROM `$table_name` WHERE Field='$name'");
        if ($data && is_array($data) && count($data)) {
            return A3GetPathValue($data[0], 'Type');
        }
        return false;
    }

    public function __call($method, $parameters)
    {
        self::__error('undefined_method', [$method], __FUNCTION__);
    }

    private static function __error($text, $replace, $a3Function): void
    {
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => $replace,
            'a3Class' => 'A3DataBaseColumn',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }

}