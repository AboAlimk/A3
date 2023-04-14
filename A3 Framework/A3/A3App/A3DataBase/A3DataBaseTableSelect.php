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

class A3DataBaseTableSelect
{

    private A3DataBaseConnection $connection;
    private string $tableName;
    private mixed $select;
    private mixed $where = '';
    private mixed $orderBy = '';
    private mixed $limit = null;
    private mixed $offset = null;
    private mixed $groupBy = '';

    public function __construct($connection, $table_name, $select)
    {
        $this->connection = $connection;
        $this->tableName = $table_name;
        $this->select = $select;
    }

    public function orderBy($id = null, $ascending = true): A3DataBaseTableSelect
    {
        if (is_null($id)) {
            self::__error('error_parameters_count', [__FUNCTION__, '1 or 2'], __FUNCTION__);
        }
        if ((!is_string($id) || $id === '') && (!is_array($id) || !count($id))) {
            self::__error('error_parameter_type', ['order id', 'string or array'], __FUNCTION__);
        }
        if (is_string($id)) {
            $this->orderBy = [$id, $ascending];
        } else if (is_array($id)) {
            $this->orderBy = $id;
        }
        return $this;
    }

    public function limit($limit = null, $offset = null): A3DataBaseTableSelect
    {
        if (is_null($limit)) {
            self::__error('error_parameters_count', [__FUNCTION__, '1 or 2'], __FUNCTION__);
        }
        if (!is_numeric($limit)) {
            self::__error('error_parameter_type', ['limit', 'number'], __FUNCTION__);
        }
        if (!is_null($offset) && !is_numeric($offset)) {
            self::__error('error_parameter_type', ['limit offset', 'number'], __FUNCTION__);
        }
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    public function groupBy(): A3DataBaseTableSelect
    {
        $this->groupBy = func_get_args();
        return $this;
    }

    public function where($where = null, $vars = null): A3DataBaseTableSelect
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

    public function exists(): bool
    {
        return A3DataBaseQuery::existsQuery($this->connection, $this->queryBuilder(true));
    }

    public function get(): A3DataBaseGet
    {
        return new A3DataBaseGet(A3DataBaseQuery::query($this->connection, $this->queryBuilder()));
    }

    private function queryBuilder($exists = false): string
    {
        $select = $exists ? "SELECT COUNT(*)" : A3DataBaseQueryParser::select($this->select);
        $from = " FROM `$this->tableName`";
        $groupBy = A3DataBaseQueryParser::groupBy($this->groupBy);
        $orderBy = A3DataBaseQueryParser::orderBy($this->orderBy);
        $limit = A3DataBaseQueryParser::limit($this->limit, $this->offset);
        return $select . $from . $this->where . $groupBy . $orderBy. $limit;
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
            'a3Class' => 'A3DataBaseTableSelect',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }

}