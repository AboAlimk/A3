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

namespace A3App;

class A3Cookie
{

    public static function add($name = null, $value = '', $expires = null, $path = '/', $domain = '', $secure = false, $httponly = false): void
    {
        if (is_null($expires)) {
            $expires = time() + A3Settings('cookie_time');
        }
        if (time() > $expires) {
            $expires = time() + $expires;
        }
        if (is_null($name)) {
            self::__error('error_parameters_count', [__FUNCTION__, 'at least 1'], __FUNCTION__);
        }
        if (!is_string($name) || $name === '') {
            self::__error('error_parameter_type', ['cookie name', 'string'], __FUNCTION__);
        }
        if (!is_null($value) && !is_string($value)) {
            self::__error('error_parameter_type', ['cookie value', 'string'], __FUNCTION__);
        }
        if (!is_null($expires) && !is_numeric($expires)) {
            self::__error('error_parameter_type', ['cookie expires', 'number'], __FUNCTION__);
        }
        if (!is_null($path) && !is_string($path)) {
            self::__error('error_parameter_type', ['cookie path', 'string'], __FUNCTION__);
        }
        if (!is_null($domain) && !is_string($domain)) {
            self::__error('error_parameter_type', ['cookie domain', 'string'], __FUNCTION__);
        }
        setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
    }

    public static function get($name = null): mixed
    {
        if ($name && is_string($name)) {
            return A3GetPathValue($_COOKIE, $name, '');
        }
        return $_COOKIE;
    }

    public static function delete($name = null): void
    {
        if (is_null($name)) {
            self::__error('error_parameters_count', [__FUNCTION__, '1'], __FUNCTION__);
        }
        if (!is_string($name) || $name === '') {
            self::__error('error_parameter_type', ['cookie name', 'string'], __FUNCTION__);
        }
        setcookie($name, '', time() - 3600);
        setcookie($name, '', time() - 3600, '/');
    }

    public function __call($method, $parameters)
    {
        self::__error('undefined_method', [$method], __FUNCTION__);
    }

    public static function __callStatic($method, $parameters)
    {
        self::__error('undefined_method', [$method], __FUNCTION__);
    }

    private static function __error($text, $replace, $a3Function): void
    {
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => $replace,
            'a3Class' => 'A3Cookie',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace(),
        ]);
    }

}