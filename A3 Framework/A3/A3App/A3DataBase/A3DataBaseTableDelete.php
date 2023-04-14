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

class A3DataBaseTableDelete
{

    private A3DataBaseConnection $connection;
    private string $tableName;
    private mixed $where = false;

    public function __construct($connection, $table_name)
    {
        $this->connection = $connection;
        $this->tableName = $table_name;
    }

    public function where($where = null, $vars = null): A3DataBaseTableDelete
    {
        if (is_null($where)) {
            self::__error('error_parameters_count', [__FUNCTION__, '1'], __FUNCTION__);
        }
        if ((!is_string($where) || $where === '') && !is_callable($where)) {
            self::__error('error_parameter_type', ['where', 'string or callable'], __FUNCTION__);
        }
        $this->where = A3DataBaseQueryParser::where($where, $vars);
        return $this;
    }

    public function do(): bool|array
    {
        if ($this->where !== false) {
            return A3DataBaseQuery::query($this->connection, "DELETE from `$this->tableName` $this->where");
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
            'a3Class' => 'A3DataBaseTableDelete',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }

}