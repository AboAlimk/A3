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

class A3RequestProcess
{

    public static function process(A3RequestObject $A3RequestObject, $requestProcess = null)
    {
        if (is_null($requestProcess)) {
            return false;
        } else {
            return A3RequestData::executeA3Request($A3RequestObject, $requestProcess);
        }
    }

}