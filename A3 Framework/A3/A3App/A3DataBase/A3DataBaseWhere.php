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

class A3DataBaseWhere
{

    private array $whereObject = [];

    public function where($key, $comparer = null, $value = null): A3DataBaseWhere
    {
        $this->addWhere("and", $key, $comparer, $value);
        return $this;
    }

    public function orWhere($key, $comparer = null, $value = null): A3DataBaseWhere
    {
        $this->addWhere("or", $key, $comparer, $value);
        return $this;
    }

    private function addWhere($and_or, $key, $comparer, $value = null): void
    {
        if (is_callable($key)) {
            $where = new A3DataBaseWhere();
            A3Callable::call($key, $where, is_array($comparer) ? $comparer : null);
            $where = [$and_or, $where->getWhere()];
        } else {
            if (is_null($comparer)) {
                return;
            }
            if (is_null($value)) {
                $value = $comparer;
                $comparer = "=";
            }
            $comparer = trim($comparer);
            $where = "$key $comparer '$value'";
            $where = [$and_or, $where];
        }
        $this->whereObject[] = $where;
    }

    public function between($key, $min, $max): A3DataBaseWhere
    {
        $this->whereObject[] = ["and", "$key BETWEEN '$min' AND '$max'"];
        return $this;
    }

    public function orBetween($key, $min, $max): A3DataBaseWhere
    {
        $this->whereObject[] = ["or", "$key BETWEEN '$min' AND '$max'"];
        return $this;
    }

    public function notBetween($key, $min, $max): A3DataBaseWhere
    {
        $this->whereObject[] = ["and", "$key NOT BETWEEN '$min' AND '$max'"];
        return $this;
    }

    public function orNotBetween($key, $min, $max): A3DataBaseWhere
    {
        $this->whereObject[] = ["or", $key . "$key NOT BETWEEN '$min' AND '$max'"];
        return $this;
    }

    public function in($key, $arr): A3DataBaseWhere
    {
        if (is_array($arr) && count($arr)) {
            $arr = implode(',',$this->fixArray($arr));
            $this->whereObject[] = ["and", "$key IN ($arr)"];
        }
        return $this;
    }

    public function orIn($key, $arr): A3DataBaseWhere
    {
        if (is_array($arr) && count($arr)) {
            $arr = implode(',',$this->fixArray($arr));
            $this->whereObject[] = ["or", "$key IN ($arr)"];
        }
        return $this;
    }

    public function notIn($key, $arr): A3DataBaseWhere
    {
        if (is_array($arr) && count($arr)) {
            $arr = implode(',',$this->fixArray($arr));
            $this->whereObject[] = ["and", $key . "$key NOT IN ($arr)"];
        }
        return $this;
    }

    public function orNotIn($key, $arr): A3DataBaseWhere
    {
        if (is_array($arr) && count($arr)) {
            $arr = implode(',',$this->fixArray($arr));
            $this->whereObject[] = ["or", $key . "$key NOT IN ($arr)"];
        }
        return $this;
    }

    private function fixArray($arr): array
    {
        return array_map(function ($value) {
            return "'" . $value . "'";
        }, $arr);
    }

    public function getWhere(): string
    {
        $out = '';
        foreach ($this->whereObject as $key => $where) {
            if ($key === 0) {
                $where_1 = $where[1];
                $out .= "($where_1)";
            } else {
                $where_0 = strtoupper($where[0]);
                $where_1 = $where[1];
                $out .= " $where_0 ($where_1)";
            }
        }
        return $out;
    }


    public function __call($method, $parameters)
    {
        self::__error($method, __FUNCTION__);
    }

    private static function __error($replace, $a3Function)
    {
        A3Error::errorTrigger([
            'text' => 'a3database_error_table_where_method',
            'replace' => [$replace],
            'a3Class' => 'A3DataBaseWhere',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }

}