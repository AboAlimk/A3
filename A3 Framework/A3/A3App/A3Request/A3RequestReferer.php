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
use A3App\A3Request\A3ReqestURLParse;

class A3RequestReferer{
    public static function match($userReferer,$requestReferer){
        if(is_null($userReferer)){
            return true;
        }
        $requestReferer = A3ReqestURLParse::parse($requestReferer);
        if($userReferer === A3RR_DOM){
            $requestReferer = $requestReferer->domain();
            $userReferer = A3ReqestURLParse::parse(A3_DOMAIN)->domain();
            return $userReferer && $requestReferer && $userReferer == $requestReferer;
        }else if(is_string($userReferer)){
            $requestReferer = $requestReferer->host();
            $userReferer = A3ReqestURLParse::parse(A3_DOMAIN)->host();
            return $userReferer && $requestReferer && $userReferer == $requestReferer;
        }else if(is_array($userReferer)){
            $requestReferer = $requestReferer->host();
            $userRefererArray = [];
            foreach($userReferer as $referer){
                $referer = A3ReqestURLParse::parse($referer)->host();
                $userRefererArray[] = $referer;
                if(!$referer){
                    return false;
                }
            }
            return $userReferer && $requestReferer && in_array($requestReferer,$userRefererArray);
        }
        return false;
    }
}