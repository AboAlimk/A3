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
use mysqli;

class A3DataBaseConnection
{

    private $host;
    private $port;
    private $dbname;
    private $dbuser;
    private $dbpass;
    private $connection = false;

    public function __construct()
    {
        $this->host = A3Settings('mysqli.host');
        $this->port = A3Settings('mysqli.port');
        $this->dbname = A3Settings('mysqli.database');
        $this->dbuser = A3Settings('mysqli.username');
        $this->dbpass = A3Settings('mysqli.password');
    }

    public function __call($method, $parameters)
    {
        $isGet = str_starts_with($method, 'get');
        if (!in_array($method, ['host', 'getHost', 'port', 'getPort', 'dbname', 'getDbname', 'dbuser', 'getDbuser', 'dbpass', 'getDbpass', 'getConnection'])) {
            self::__error('undefined_method', [$method], __FUNCTION__);
        }
        if (!$isGet && (empty($parameters) || !is_array($parameters) || !count($parameters))) {
            self::__error('error_parameters_count', [$method, '1'], __FUNCTION__);
        } else if (!$isGet && $method === 'port' && !is_numeric($parameters[0])) {
            self::__error('error_parameter_type', [$method, 'number'], __FUNCTION__);
        } else if (!$isGet && $method !== 'port' && (!is_string($parameters[0]) || $parameters[0] === '')) {
            self::__error('error_parameter_type', [$method, 'string'], __FUNCTION__);
        }
        $method = strtolower($method);
        if ($isGet) {
            $getVar = substr($method, 3);
            return $this->{$getVar};
        } else {
            $this->{$method} = $parameters[0];
            return $this;
        }
    }

    public static function __callStatic($method, $parameters): A3DataBaseConnection
    {
        if ($method !== 'new') {
            self::__error('a3database_connection_error_method', [$method], __FUNCTION__);
        }
        return new A3DataBaseConnection();
    }

    public function connect(): A3DataBaseConnection
    {
        if (!$this->host || !is_string($this->host)) {
            self::__error('mysqli_connection_data_error', ['host'], __FUNCTION__);
        }
        if (!$this->dbname || !is_string($this->dbname)) {
            self::__error('mysqli_connection_data_error', ['database'], __FUNCTION__);
        }
        if (!$this->dbuser || !is_string($this->dbuser)) {
            self::__error('mysqli_connection_data_error', ['database user'], __FUNCTION__);
        }
        if (!$this->port || !is_numeric($this->port)) {
            self::__error('error_parameter_type', ['port', 'number'], __FUNCTION__);
        }
        A3Error::errorTrigger([
            'a3Class' => 'A3DataBaseConnection',
            'a3Function' => __FUNCTION__,
            'debugData' => debug_backtrace(),
        ], false);
        $connectionId = 'A3DataBaseConnection_' . md5(strtolower($this->host) . strtolower($this->dbuser) . strtolower($this->dbpass) . strtolower($this->dbname) . strtolower($this->port));
        $this->connection = A3DataBaseConnections::get($connectionId);
        if ($this->connection === false) {
            $this->connection = new mysqli($this->host, $this->dbuser, $this->dbpass, $this->dbname, $this->port);
            $this->connection->query("SET CHARACTER SET '" . A3Settings('mysqli.charset')."'");
            A3DataBaseConnections::add($connectionId, $this->connection);
        }
        return $this;
    }

    private static function __error($text, $replace, $a3Function): void
    {
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => $replace,
            'a3Class' => 'A3DataBaseConnection',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace(),
        ]);
    }

}