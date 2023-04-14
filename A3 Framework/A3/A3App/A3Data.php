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

class A3Data
{

    private static array $A3DataArray = [];

    public static function process($key, $value): mixed
    {
        if ($value) {
            self::A3DataSetItem($key, $value);
        } else {
            if (A3String::is($key)) {
                $path = A3String::getPath($key);
                return A3GetPathValue(self::$A3DataArray, $path, null);
            } else if (is_array($key)) {
                foreach ($key as $k => $v) {
                    self::A3DataSetItem($k, $v);
                }
            }
        }
        return false;
    }

    private static function A3DataSetItem($key, $value): void
    {
        self::$A3DataArray = A3SetPathValue(self::$A3DataArray, A3String::getPath($key), $value);
    }

    public static function loadDataFile($name, $file): void
    {
        self::$A3DataArray[$name] = require $file;
    }
}