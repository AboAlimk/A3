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

class requestMiddleProcess{
    public function processLanguage($request){
        return A3Redirect('/'.A3::getLocal());
    }
    public function process($request){
        $lng = $request->getRequestRouteItems('lng');
        if(!A3::isValidLanguage($lng)){
            return A3Redirect()->a3Request($request,['lng'=>A3::getLocal()]);
        }
        if($lng){
            A3::setLocal($lng);
        }
        return $request;
    }
}