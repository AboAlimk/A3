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

namespace A3App\A3Request;

use A3Error;

class A3Request
{

    public static function __callStatic($method, $parameters)
    {
        $methods = [
            'get' => 2,
            'post' => 2,
            'put' => 2,
            'delete' => 2,
            'match' => 3,
            'any' => 2
        ];
        if (!array_key_exists($method, $methods)) {
            self::__error('undefined_method', ['A3Request::' . $method], __FUNCTION__);
        }
        $methodCount = $methods[$method];
        if (empty($parameters) || !is_array($parameters) || count($parameters) < $methodCount) {
            self::__error('error_parameters_count', ['A3Request::' . $method, $methodCount], __FUNCTION__);
        }
        return new A3RequestRegistrar($method, $parameters, debug_backtrace());
    }

    public function __call($method, $parameters)
    {
        self::__error('undefined_method', ['A3Request::' . $method], __FUNCTION__);
    }

    private static function __error($text, $replace, $a3Function): void
    {
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => $replace,
            'a3Class' => 'A3Request',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace(),
        ]);
    }

}