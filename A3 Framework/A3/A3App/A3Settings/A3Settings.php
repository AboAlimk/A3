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

namespace A3App\A3Settings;

use A3String;

class A3Settings
{

    private static array $A3SettingsArray = [];

    public static function process($key)
    {
        $base = A3GetPathValue(self::$A3SettingsArray, A3String::getPath('base.' . $key), null);
        $user = A3GetPathValue(self::$A3SettingsArray, A3String::getPath('user.' . $key), null);
        if (is_null($user)) {
            return $base;
        }
        return $user;
    }

    public static function loadSettingsFile($name, $file): void
    {
        self::$A3SettingsArray[$name] = require $file;
    }

}