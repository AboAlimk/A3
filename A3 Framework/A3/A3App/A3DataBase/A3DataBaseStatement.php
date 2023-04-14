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

use A3String;
use A3Error;

class A3DataBaseStatement
{

    private A3DataBaseConnection $connection;
    private string $statement;

    public function __construct($connection, $statement, $replace = null)
    {
        $this->connection = $connection;
        $this->statement = is_null($replace) ? $statement : A3String::replace($statement, '?', $replace);
    }

    public function get(): A3DataBaseGet
    {
        return new A3DataBaseGet(A3DataBaseQuery::query($this->connection, $this->statement));
    }

    public function getResult(): bool|array
    {
        return A3DataBaseQuery::query($this->connection, $this->statement);
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
            'a3Class' => 'A3DataBaseStatement',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace()
        ]);
    }

}