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


/**
 * place you requests here
 */



A3Request::get('/',function(){
       return A3View('welcome');
    })->middleProcess(function($a3Request){
        A3::setLocal(A3GetBrowserLanguage());
        return $a3Request;
    });