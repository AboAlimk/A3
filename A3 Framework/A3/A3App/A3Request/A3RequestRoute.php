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

use A3String;

class A3RequestRoute
{

    public static function match($requestRoute, $requestWhere, $requestRouteItems): array
    {
        $count = 0;
        $array = [
            'items' => [],
            'result' => true
        ];
        $requestRoute = self::removeRouteSlashes($requestRoute);
        $routeItems = self::getRouteItems($requestRoute);
        if (count($routeItems) != count($requestRouteItems)) {
            return [
                'items' => [],
                'result' => false
            ];
        }
        foreach ($routeItems as $key => $routeItem) {
            $fixedRouteItem = self::fixRouteItem($routeItem);
            if ($fixedRouteItem) {
                $routeItemValue = A3GetPathValue($requestRouteItems, $key);
                $routeItemMatch = A3GetPathValue($requestWhere, $fixedRouteItem);
                if (preg_match('/' . $routeItemMatch . '/', $routeItemValue)) {
                    $array['items'][$fixedRouteItem] = $routeItemValue;
                } else {
                    $array['result'] = false;
                    $array['items'] = [];
                    break;
                }
            } else {
                if (strtolower($requestRouteItems[$count]) !== strtolower($routeItem)) {
                    $array['result'] = false;
                    $array['items'] = [];
                    break;
                }
            }
            $count++;
        }
        return $array;
    }

    public static function parse($requestRoute, $replace): string
    {
        if (is_array($replace) && count($replace)) {
            $processedRequestRoute = self::removeRouteSlashes($requestRoute);
            $requestRouteItems = self::getRouteItems($processedRequestRoute);
            $route = '';
            $replace = array_change_key_case($replace);
            $requestRouteItems = array_change_key_case($requestRouteItems);
            foreach ($requestRouteItems as $requestRouteItem) {
                $fixedRequestRouteItem = self::fixRouteItem($requestRouteItem);
                if ($fixedRequestRouteItem) {
                    $route .= A3GetPathValue($replace, $fixedRequestRouteItem, 'null');
                } else {
                    $route .= $requestRouteItem;
                }
                $route .= '/';
            }
            return '/' . self::removeRouteSlashes($route);
        }
        return $requestRoute;
    }

    private static function removeRouteSlashes($route)
    {
        $route = A3String::removeStart($route, '/');
        return A3String::removeEnd($route, '/');
    }

    private static function getRouteItems($route): array
    {
        if (!A3String::contains($route, '/')) {
            if ($route) {
                return [$route];
            }
            return [];
        }
        return explode('/', $route);
    }

    private static function fixRouteItem($routeItem): string|bool
    {
        if (A3String::starts($routeItem, '{') && A3String::ends($routeItem, '}')) {
            return A3String::dRange($routeItem, 1, 1);
        }
        return false;
    }

}