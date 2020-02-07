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
use A3App\A3DataBase\A3DataBaseConnections;
use A3App\A3Request\A3RequestData;
use A3App\A3Settings\A3Settings;
use A3App\A3View\A3View;
use A3App\A3Response;
use A3App\A3Language;
use A3App\A3Redirect;
use A3App\A3Data;
use A3App\A3Json;

//A3Array
if(!function_exists('A3GetPathValue')){
    function A3GetPathValue($arr , $path , $def = false){
        return A3Array::get($arr , $path , $def);
    }
}
if(!function_exists('A3SetPathValue')){
    function A3SetPathValue($arr , $path , $value){
        return A3Array::set($arr , $path , $value);
    }
}
//A3Array

//A3Settings
if(!function_exists('A3Settings')){
    function A3Settings($key){
        return A3Settings::process($key);
    }
}
//A3Settings

//A3View
if(!function_exists('A3View')){
    function A3View($uri,$data = []){
        return new A3View($uri,$data);
    }
}
if(!function_exists('A3Response')){
    function A3Response($content = '',$headers = []){
        return new A3Response($content,$headers);
    }
}
if(!function_exists('A3Assest')){
    function A3Assest($uri = '',$version = false){
        return A3_ROOT.'/'.$uri.($version?'?v='.$version:'');
    }
}
//A3View

//A3Request
if(!function_exists('A3Request')){
    function A3Request($name, $replace = null){
        return A3RequestData::getA3RequestLink($name, $replace);
    }
}
if(!function_exists('A3RequestFull')){
    function A3RequestFull($name, $replace = null){
        return A3RequestData::getA3RequestFullLink($name, $replace);
    }
}
if(!function_exists('A3CurrentRequest')){
    function A3CurrentRequest($replace = []){
        return A3RequestData::getCurrentA3RequestLink($replace);
    }
}
if(!function_exists('A3CurrentRequestFull')){
    function A3CurrentRequestFull($replace = []){
        return A3RequestData::getCurrentA3RequestFullLink($replace);
    }
}
if(!function_exists('A3RequestExists')){
    function A3RequestExists($name){
        return A3RequestData::exists($name);
    }
}
//A3Request

//A3Data
if(!function_exists('A3Data')){
    function A3Data($key, $value = null){
        return A3Data::process($key, $value);
    }
}
//A3Data

//A3Redirect
if(!function_exists('A3Redirect')){
    function A3Redirect($url = null, $code = 302){
        return new A3Redirect($url, $code);
    }
}
//A3Redirect

//A3Language
if(!function_exists('A3GetBrowserLanguage')){
    function A3GetBrowserLanguage(){
        return A3Language::getBrowserLanguage();
    }
}
if(!function_exists('A3GetLanguagesCodes')){
    function A3GetLanguagesCodes(){
        return A3Language::getLanguagesCodes();
    }
}
if(!function_exists('A3')){
    function A3($key,$replace = []){
        return A3Language::getLanguageTextLocal($key ,$replace);
    }
}
if(!function_exists('_A3')){
    function _A3($key,$replace = []){
        echo A3($key,$replace);
    }
}
if(!function_exists('A3L')){
    function A3L($local,$key,$replace = []){
        return A3Language::getLanguageTextCustom($local,$key ,$replace);
    }
}
if(!function_exists('_A3L')){
    function _A3L($local,$key,$replace = []){
        echo A3L($local,$key,$replace);
    }
}
//A3Language

//A3Json
if(!function_exists('A3Json')){
    function A3Json(){
        return call_user_func_array([A3Json::class,'json'],func_get_args());
    }
}
if(!function_exists('A3JsonX')){
    function A3JsonX(){
        return call_user_func_array([A3Json::class,'jsonX'],func_get_args());
    }
}
//A3Json

//A3String
if(!function_exists('A3GRS')){
    function A3GRS($length = 10,$type = null){
        return A3String::random($length,$type);
    }
}
if(!function_exists('A3Words')){
    function A3Words($txt, $count = 10, $end = '...'){
        return A3String::words($txt, $count, $end);
    }
}
//A3String

//A3Date
if(!function_exists('A3Date')){
    function A3Date($ts ,$format ,$local = null){
        return A3Date::process($ts ,$format ,$local);
    }
}
//A3Date

function A3__clean__output__buffer(){
    ob_clean();
}
function A3__exit__and__close($exit = true){
    A3DataBaseConnections::closeAll();
    if($exit){
        exit;
    }
}