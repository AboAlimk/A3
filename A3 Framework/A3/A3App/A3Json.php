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

class A3Json
{

    public static function json($value): bool|string
    {
        return json_encode($value);
    }

    public static function jsonX(): bool|string
    {
        $arguments = func_get_args();
        $arr = [];
        for ($i = 0; $i < count($arguments); $i++) {
            $arr["x" . ($i + 1)] = $arguments[$i];
        }
        return json_encode($arr);
    }

}