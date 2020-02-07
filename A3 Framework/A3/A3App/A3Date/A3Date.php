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
namespace A3App\A3Date;

class A3Date{
    private static $A3DataArray = [];
    public static function process( $ts ,$format ,$local = null ){
        if(!$ts){
            $ts = time();
        }
        if(is_null($local) || !is_string($local)){
            $local = 'en';
        }
        $local = strtolower($local);
        if(!array_key_exists($local,self::$A3DataArray)){
            $local = 'en';
        }
        $timeObject = self::timeObject($ts,$local);
        $out = '';
        if(is_string($format)){
            for($i=0 ; $i< strlen($format) ; $i++){
                $item = $format[$i];
                if(array_key_exists($item,$timeObject)){
                    $out .= $timeObject[$item];
                }else{
                    $out .= $item;
                }
            }
        }
        return $out;
    }
    private static function timeObject($ts ,$local){
        return [
            'd' => date('d',$ts),
            'D' => self::getLocalText(date('D',$ts) ,$local),
            'j' => date('j',$ts),
            'm' => date('m',$ts),
            'M' => self::getLocalText(date('M',$ts) ,$local),
            'n' => date('n',$ts),
            'Y' => date('Y',$ts),
            'y' => date('y',$ts),
            'a' => self::getLocalText(date('a',$ts) ,$local),
            'H' => date('H',$ts),
            'h' => date('h',$ts),
            'i' => date('i',$ts),
            's' => date('s',$ts)
        ];
    }
    private static function getLocalText($txt ,$local){
        $txt = strtolower($txt);
        return A3GetPathValue(self::$A3DataArray,[$local,$txt],'');
    }
    public static function loadDataFile($file){
        self::$A3DataArray = require $file;
    }
}
