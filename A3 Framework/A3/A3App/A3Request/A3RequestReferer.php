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

class A3RequestReferer
{

    public static function match($userReferer, $requestReferer): bool
    {
        if (is_null($userReferer) || $userReferer === '') {
            return true;
        }
        $requestReferer = A3RequestURLParse::parse($requestReferer);
        if ($userReferer === A3RR_DOM) {
            $requestReferer = $requestReferer->domain();
            $userReferer = A3RequestURLParse::parse(A3_DOMAIN)->domain();
            return $userReferer && $requestReferer && $userReferer == $requestReferer;
        } else if (is_string($userReferer)) {
            $requestReferer = $requestReferer->host();
            $userReferer = A3RequestURLParse::parse(A3_DOMAIN)->host();
            return $userReferer && $requestReferer && $userReferer == $requestReferer;
        } else if (is_array($userReferer)) {
            $requestReferer = $requestReferer->host();
            $userRefererArray = [];
            foreach ($userReferer as $referer) {
                $referer = A3RequestURLParse::parse($referer)->host();
                $userRefererArray[] = $referer;
                if (!$referer) {
                    return false;
                }
            }
            return $userReferer && $requestReferer && in_array($requestReferer, $userRefererArray);
        }
        return false;
    }

}