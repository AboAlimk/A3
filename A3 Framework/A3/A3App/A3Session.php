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

class A3Session
{

    private static bool $loaded = false;

    public static function start(): void
    {
        if (!self::$loaded) {
            session_start();
            self::$loaded = true;
        }
    }

    public static function add($name = null, $value = ''): void
    {
        self::start();
        if (is_null($name)) {
            self::__error('error_parameters_count', [__FUNCTION__, '1 or 2'], __FUNCTION__);
        }
        if (!is_string($name) || $name === '') {
            self::__error('error_parameter_type', ['session name', 'string'], __FUNCTION__);
        }
        if (!is_null($value) && !is_string($value)) {
            self::__error('error_parameter_type', ['session value', 'string'], __FUNCTION__);
        }
        $_SESSION[$name] = $value;
    }

    public static function get($name = null): array|string
    {
        self::start();
        if ($name && is_string($name) && $name !== '') {
            return A3GetPathValue($_SESSION, $name, '');
        }
        return $_SESSION;
    }

    public static function delete($name = null): void
    {
        self::start();
        if (is_null($name)) {
            self::__error('error_parameters_count', [__FUNCTION__, '1'], __FUNCTION__);
        }
        if (!is_string($name) || $name === '') {
            self::__error('error_parameter_type', ['session name', 'string'], __FUNCTION__);
        }
        unset($_SESSION[$name]);
    }

    public static function destroy(): void
    {
        self::start();
        session_unset();
        session_destroy();
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
            'a3Class' => 'A3Session',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace(),
        ]);
    }

}