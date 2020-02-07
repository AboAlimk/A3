<?php
/**
 *
 *   A3 Framework
 *   Version: 1.0
 *   Date: 02-2020
 *   Author: Abdulsattar Alkhalaf
 *   AboAlimk@gmail.com
 *
 */
namespace A3App\A3Request;
use A3String;

class A3RequestRoute{
    public static function match($requestRoute, $requestWhere, $requestRouteItems){
        $count = 0;
        $array = [
            'items' => [],
            'result' => true
        ];
        $requestRoute = self::removeRouteSlashes($requestRoute);
        $routeItems = self::getRouteItems($requestRoute);
        if(count($routeItems)!=count($requestRouteItems)){
            return [
                'items' => [],
                'result' => false
            ];
        }
        foreach($routeItems as $key=>$routeItem){
            $fixedRouteItem = self::fixRouteItem($routeItem);
            if($fixedRouteItem){
                $routeItemValue = A3GetPathValue($requestRouteItems,$key);
                $routeItemMatch = A3GetPathValue($requestWhere,$fixedRouteItem);
                if(preg_match('/'.$routeItemMatch.'/',$routeItemValue)){
                    $array['items'][$fixedRouteItem] = $routeItemValue;
                }else{
                    $array['result'] =  false;
                    $array['items'] = [];
                    break;
                }
            }else{
                if(strtolower($requestRouteItems[$count])!==strtolower($routeItem)){
                    $array['result'] =  false;
                    $array['items'] = [];
                    break;
                }
            }
            $count++;
        }
        return $array;
    }
    public static function parse($requestRoute, $replace){
        if(is_array($replace) && count($replace)){
            $processedRequestRoute = self::removeRouteSlashes($requestRoute);
            $requestRouteItems = self::getRouteItems($processedRequestRoute);
            $route = '';
            array_change_key_case($replace,CASE_LOWER);
            array_change_key_case($requestRouteItems,CASE_LOWER);
            foreach($requestRouteItems as $requestRouteItem){
                $fixedRequestRouteItem = self::fixRouteItem($requestRouteItem);
                if($fixedRequestRouteItem){
                    $route .= A3GetPathValue($replace,$fixedRequestRouteItem,'null');
                }else{
                    $route .= $requestRouteItem;
                }
                $route .= '/';
            }
            return '/'.self::removeRouteSlashes($route);
        }
        return $requestRoute;
    }
    private static function removeRouteSlashes($route){
        $route = A3String::removeStart($route,'/');
        $route = A3String::removeEnd($route,'/');
        return $route;
    }
    private static function getRouteItems($route){
        if(!A3String::contains($route,'/')){
            if($route){
                return [$route];
            }
            return [];
        }
        return explode('/' , $route);
    }
    private static function fixRouteItem($routeItem){
        if(A3String::starts($routeItem,'{') && A3String::ends($routeItem,'}')){
            return A3String::dRange($routeItem,1,1);
        }
        return false;
    }
}