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

class A3DataBase
{

    public static function connect($connection = null): A3DataBaseRequest
    {
        return new A3DataBaseRequest($connection);
    }

    public function __call($method, $parameters)
    {
        self::__error($method, __FUNCTION__);
    }

    public static function __callStatic($method, $parameters)
    {
        self::__error($method, __FUNCTION__);
    }

    private static function __error($replace, $a3Function): void
    {
        A3Error::errorTrigger([
            'text' => 'a3database_error_method',
            'replace' => [$replace],
            'a3Class' => 'A3DataBase',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace(),
        ]);
    }

}