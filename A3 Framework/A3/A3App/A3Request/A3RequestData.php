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
use A3Error;

class A3RequestData{
    private static $A3CurrentRequest;
    private static $A3CurrentRequestRouteItems;
    private static $A3RequestArray = [];
    public static function addA3Request($data){
        $name = A3GetPathValue($data,'name');
        if($name){
            if(array_key_exists(strtolower($name),self::$A3RequestArray)){
                self::__error('a3request_name_exists',$name,A3GetPathValue($data,'debugData'));
            }
            if(!preg_match("/^[a-zA-Z0-9_-]+$/",$name)){
                self::__error('a3request_name_not_valid',$name,A3GetPathValue($data,'debugData'));
            }
        }
        self::$A3RequestArray[strtolower($name)] = $data;
    }
    public static function getObject(){
        $sortedArray = self::$A3RequestArray;
        usort($sortedArray,function($a, $b) {
            if($a['subdomaincount'] == $b['subdomaincount']){
                    return 0;
            }
            return ($a['subdomaincount'] < $b['subdomaincount']) ? 1 : -1;
        });
        return $sortedArray;
    }
    public static function exists($name){
        return array_key_exists(strtolower($name), self::$A3RequestArray);
    }
    public static function getA3RequestLink($A3Request, $replace){
        if($A3Request instanceof A3RequestObject){
            $requestRoute = $A3Request->getBaseRoute();
            if($replace){
                return A3RequestRoute::parse($requestRoute, $replace);
            }
            return $requestRoute;
        }
        $A3Request = A3GetPathValue(self::$A3RequestArray,strtolower($A3Request));
        if($A3Request){
            $requestRoute = A3GetPathValue($A3Request,'route','');
            if($replace){
                return A3RequestRoute::parse($requestRoute, $replace);
            }
            return $requestRoute;
        }
        return '';
    }
    public static function getA3RequestFullLink($A3Request, $replace){
        $link = self::getA3RequestLink($A3Request, $replace);
        return $link?A3_ROOT.$link:'';
    }
    public static function setCurrentA3Request($name,$items){
        self::$A3CurrentRequest = $name;
        self::$A3CurrentRequestRouteItems = $items;
    }
    public static function getCurrentA3RequestLink($replace = []){
        if(!self::$A3CurrentRequest){
            return '';
        }
        if(!is_array($replace)){
            $replace = [];
        }
        $items = self::$A3CurrentRequestRouteItems;
        array_change_key_case($replace,CASE_LOWER);
        array_change_key_case($items,CASE_LOWER);
        foreach($replace as $key=>$value){
            $items[$key] = $value;
        }
        return self::getA3RequestLink(self::$A3CurrentRequest, $items);
    }
    public static function getCurrentA3RequestFullLink($replace = []){
        $link = self::getCurrentA3RequestLink($replace);
        return $link?A3_ROOT.$link:'';
    }
    private static function __error($text,$replace,$debugData){
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => [$replace],
            'a3Class' => 'A3Request',
            'a3Function' => '__callStatic',
            'debugData' => $debugData,
        ]);
    }
}