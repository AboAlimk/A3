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

use A3App\A3View\A3View;
use A3String;
use A3Error;

class A3RequestData
{

    private static string $A3CurrentRequest = '';
    private static array $A3CurrentRequestRouteItems = [];
    private static array $A3RequestArray = [];

    public static function addA3Request($data): void
    {
        $name = A3GetPathValue($data, 'name');
        if ($name) {
            if (array_key_exists(strtolower($name), self::$A3RequestArray)) {
                self::__error('a3request_name_exists', $name, A3GetPathValue($data, 'debugData'));
            }
            if (!preg_match("/^[a-zA-Z0-9_-]+$/", $name)) {
                self::__error('a3request_name_not_valid', $name, A3GetPathValue($data, 'debugData'));
            }
        }
        self::$A3RequestArray[strtolower($name)] = $data;
    }

    public static function getObject(): array
    {
        $sortedArray = self::$A3RequestArray;
        usort($sortedArray, function ($a, $b) {
            if ($a['subdomaincount'] == $b['subdomaincount']) {
                return 0;
            }
            return ($a['subdomaincount'] < $b['subdomaincount']) ? 1 : -1;
        });
        return $sortedArray;
    }

    public static function exists($name): bool
    {
        return array_key_exists(strtolower($name), self::$A3RequestArray);
    }

    public static function getA3RequestLink($A3Request, $replace): string
    {
        if ($A3Request instanceof A3RequestObject) {
            $requestRoute = $A3Request->getBaseRoute();
            if (is_array($replace) && count($replace)) {
                return A3RequestRoute::parse($requestRoute, $replace);
            }
            return $requestRoute;
        }
        $A3Request = A3GetPathValue(self::$A3RequestArray, strtolower($A3Request));
        if ($A3Request) {
            $requestRoute = A3GetPathValue($A3Request, 'route', '');
            if ($replace) {
                return A3RequestRoute::parse($requestRoute, $replace);
            }
            return $requestRoute;
        }
        return '';
    }

    public static function getA3RequestFullLink($A3Request, $replace): string
    {
        $link = self::getA3RequestLink($A3Request, $replace);
        return $link ? A3_ROOT . $link : '';
    }

    public static function setCurrentA3Request($name, $items): void
    {
        self::$A3CurrentRequest = $name;
        self::$A3CurrentRequestRouteItems = $items;
    }

    public static function getCurrentA3RequestLink($replace = []): string
    {
        if (self::$A3CurrentRequest === '') {
            return '';
        }
        if (!is_array($replace)) {
            $replace = [];
        }
        $items = self::$A3CurrentRequestRouteItems;
        $replace = array_change_key_case($replace);
        $items = array_change_key_case($items);
        foreach ($replace as $key => $value) {
            $items[$key] = $value;
        }
        return self::getA3RequestLink(self::$A3CurrentRequest, $items);
    }

    public static function getCurrentA3RequestFullLink($replace = []): string
    {
        $link = self::getCurrentA3RequestLink($replace);
        return $link ? A3_ROOT . $link : '';
    }

    public static function executeA3Request(A3RequestObject $A3RequestObject, $requestProcess = null)
    {
        $parametersArray = self::generateParametersArray($A3RequestObject);
        if ($requestProcess instanceof A3View) {
            return $requestProcess;
        } else if (is_callable($requestProcess)) {
            return call_user_func_array($requestProcess, $parametersArray);
        } else if (A3String::is($requestProcess)) {
            $actionArray = A3String::getCallback($requestProcess);
            if ($actionArray !== false) {
                $a3Class = $actionArray[0];
                $a3Function = $actionArray[1];
                if (class_exists($a3Class) && method_exists($a3Class, $a3Function)) {
                    $a3Class = new $a3Class();
                    return call_user_func_array([$a3Class, $a3Function], $parametersArray);
                }
            }
        }
        return false;
    }

    private static function generateParametersArray($A3RequestObject): array
    {
        $nullArray = array_fill(0, 30, null);
        $array = [$A3RequestObject];
        return array_merge($array, $nullArray);
    }

    private static function __error($text, $replace, $debugData): void
    {
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => [$replace],
            'a3Class' => 'A3Request',
            'a3Function' => '__callStatic',
            'debugData' => $debugData,
        ]);
    }

}