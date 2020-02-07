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

class A3RequestMiddleProcess{
    public static function process(A3RequestObject $A3RequestObject, $requestMiddleProcess = null){
        if(is_null($requestMiddleProcess)){
            return $A3RequestObject;
        }else{
            $parametersArray = self::generateParametersArray($A3RequestObject);
            if(is_callable($requestMiddleProcess)){
                return call_user_func_array($requestMiddleProcess,$parametersArray);
            }else if(A3String::is($requestMiddleProcess)){
                $actionArray = A3String::getCallback($requestMiddleProcess);
                if($actionArray !== false){
                    $a3Class = $actionArray[0];
                    $a3Function = $actionArray[1];
                    if(class_exists($a3Class) && method_exists($a3Class,$a3Function)){
                        $a3Class = new $a3Class();
                        return call_user_func_array([$a3Class,$a3Function],$parametersArray);
                    }
                }
            }
        }
        return false;
    }
    private static function generateParametersArray($A3RequestObject){
        $nullArray = array_fill(0,30,null);
        $array = [$A3RequestObject];
        return array_merge($array,$nullArray);
    }
}