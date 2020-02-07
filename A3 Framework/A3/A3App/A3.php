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
namespace A3App;
use A3App\A3Language;
use A3Cookie;
use A3Error;

class A3{
    private const A3_APP_LOCAL_TXT = 'A3_APP_LOCAL';
    private static $local;
    public static function setLocal($local = null){
        if(is_null($local)){
            self::__error('error_parameters_count',[__FUNCTION__,'1'],__FUNCTION__);
        }
        if(!is_string($local)){
            self::__error('error_parameter_type',['local','string'],__FUNCTION__);
        }
        $local = strtolower($local);
        if(self::isValidLanguage($local)){
            self::$local = $local;
            self::setCookieLocal($local);
        }
    }
    public static function getLocal(){
        if(is_null(self::$local)){
            $cookieLocal = self::getCookieLocal();
            self::$local = ($cookieLocal && self::isValidLanguage($cookieLocal)) ? $cookieLocal : A3Settings('app_local');
        }
        return self::$local;
    }
    public static function isValidLanguage($local){
        return in_array(strtolower($local),A3Language::getLanguagesCodes());
    }
    public static function isSecure(){
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    }
    public static function getDomain(){
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST']; 
    }
    public static function getRootUrl(){
        $dir = str_replace(["\\","/"],"/",A3_PUBLIC_DIR);
        $root = $_SERVER['DOCUMENT_ROOT'];
        $root = str_replace(["\\","/"],"/",$root);
        $dir = str_replace($root,"",$dir);
        return self::getDomain() . $dir;  
    }
    private static function getCookieLocal(){
        return A3Cookie::get(self::A3_APP_LOCAL_TXT);
    }
    private static function setCookieLocal($local){
        A3Cookie::add(self::A3_APP_LOCAL_TXT,$local);
    }
    public function __call($method,$parameters){
        self::__error('undefined_method',[$method],__FUNCTION__);
    }
    public static function __callStatic($method,$parameters){
        self::__error('undefined_method',[$method],__FUNCTION__);
    }
    private static function __error($text,$replace,$a3Function){
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => $replace,
            'a3Class' => 'A3',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace(),
        ]);
    }
}