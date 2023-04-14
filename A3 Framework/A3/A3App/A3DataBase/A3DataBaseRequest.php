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

use A3App\A3Callable;
use A3Error;

class A3DataBaseRequest
{

    private static A3DataBaseConnection $baseConnection;
    private A3DataBaseConnection $connection;

    public function __construct($connection = null)
    {
        if (is_null($connection)) {
            $this->connection = self::getBaseConnection();
        } else if (!$connection instanceof A3DataBaseConnection) {
            self::__error('a3database_error_connection_type', [''], __FUNCTION__);
        } else {
            $this->connection = $connection;
        }
        $this->connection->connect();
    }

    private function getBaseConnection(): A3DataBaseConnection
    {
        if (!isset(self::$baseConnection)) {
            self::$baseConnection = A3DataBaseConnection::new();
        }
        return self::$baseConnection;
    }

    public function getLastError()
    {
        return $this->connection->getConnection()->error;
    }

    public function statement($statement = null, $replace = null): A3DataBaseStatement
    {
        if (is_null($statement)) {
            self::__error('error_parameters_count', [__FUNCTION__, '1 or 2'], __FUNCTION__);
        }
        if (!is_string($statement) || $statement === '') {
            self::__error('error_parameter_type', [__FUNCTION__, 'string'], __FUNCTION__);
        }
        return new A3DataBaseStatement($this->connection, $statement, $replace);
    }

    public function table($name = null): A3DataBaseTable
    {
        if (is_null($name)) {
            self::__error('error_parameters_count', [__FUNCTION__, 1], __FUNCTION__);
        }
        if (!is_string($name) || $name === '') {
            self::__error('error_parameter_type', ['table name', 'string'], __FUNCTION__);
        }
        return new A3DataBaseTable($this->connection, $name);
    }

    public function createTable($name = null, $callable = null, $vars = null): bool|array
    {
        if (is_null($name) || is_null($callable)) {
            self::__error('error_parameters_count', [__FUNCTION__, 2], __FUNCTION__);
        }
        if (!is_string($name) || $name === '') {
            self::__error('error_parameter_type', ['table name', 'string'], __FUNCTION__);
        }
        if (!preg_match("/^[a-zA-Z0-9_]+$/", $name)) {
            self::__error('a3database_error_name', [$name], __FUNCTION__);
        }
        if (!is_callable($callable)) {
            self::__error('error_parameter_type', ['table data', 'callable'], __FUNCTION__);
        }
        $table_data = new A3DataBaseTableData($name);
        A3Callable::call($callable, $table_data, $vars);
        return A3DataBaseQuery::query($this->connection, $table_data->getData());
    }

    public function __call($method, $parameters)
    {
        self::__error('a3database_error_connect_method', [$method], __FUNCTION__);
    }

    private static function __error($text, $replace, $a3Function): void
    {
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => $replace,
            'a3Class' => 'A3DataBaseRequest',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }

}