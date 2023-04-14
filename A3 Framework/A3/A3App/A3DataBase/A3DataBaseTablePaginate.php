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

class A3DataBaseTablePaginate
{

    private A3DataBaseConnection $connection;
    private string $tableName;
    private int $itemsPerPage;
    private int $currentPage;
    private int $startOffset;
    private mixed $select = '';
    private mixed $where = '';
    private mixed $orderBy = '';

    public function __construct($connection, $table_name, $items_per_page, $current_page, $start_offset)
    {
        $this->connection = $connection;
        $this->tableName = $table_name;
        $this->itemsPerPage = $items_per_page;
        $this->currentPage = $current_page;
        $this->startOffset = $start_offset;
    }

    public function select(): A3DataBaseTablePaginate
    {
        $select = func_get_args();
        if (func_num_args() === 0) {
            $select = '*';
        } else if (func_num_args() === 1 && is_array(func_get_arg(0))) {
            $select = func_get_arg(0);
        }
        $this->select = $select;
        return $this;
    }

    public function where($where = null, $vars = null): A3DataBaseTablePaginate
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

    public function orderBy($id = null, $ascending = true): A3DataBaseTablePaginate
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

    public function getInfo(): bool|array
    {
        $totalCount = A3DataBaseQuery::countQuery($this->connection, $this->queryBuilderInfo());
        if ($totalCount) {
            $total = ceil($totalCount / $this->itemsPerPage);
            $current = $this->currentPage;
            $first = 1;
            $last = $total;
            $prev = $current === $first ? null : $current - 1;
            $next = $current === $last ? null : $current + 1;
            $hasPrev = $current > $first;
            $hasNext = $current < $last;
            return [
                'totalItems' => $totalCount,
                'total' => $total,
                'current' => $current,
                'first' => $first,
                'last' => $last,
                'prev' => $prev,
                'next' => $next,
                'hasPrev' => $hasPrev,
                'hasNext' => $hasNext
            ];
        }
        return false;
    }

    public function get(): A3DataBaseGet
    {
        return new A3DataBaseGet(A3DataBaseQuery::query($this->connection, $this->queryBuilder()));
    }

    private function queryBuilder(): string
    {
        $select = A3DataBaseQueryParser::select($this->select);
        $from = " FROM " . $this->tableName;
        $orderBy = A3DataBaseQueryParser::orderBy($this->orderBy);
        $limit = A3DataBaseQueryParser::limit($this->itemsPerPage, $this->startOffset);
        return $select . $from . $this->where . $orderBy . $limit;
    }

    private function queryBuilderInfo(): string
    {
        $select = "SELECT COUNT(*) FROM $this->tableName";
        $orderBy = A3DataBaseQueryParser::orderBy($this->orderBy);
        return $select . $this->where . $orderBy;
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
            'a3Class' => 'A3DataBaseTablePaginate',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }

}