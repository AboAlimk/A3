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

class A3String{
    public static function is($pattern,$value = null){
        if(is_null($value)){
            return is_string($pattern);
        }
        if($pattern == $value){
            return true;
        }
        $pattern = preg_quote($pattern, '#');
        $pattern = self::replace($pattern ,'\*' ,'.*');
        return self::match('#^'.$pattern.'\z#u', $value) === 1;
    }
    public static function match($pattern,$txt){
        return preg_match($pattern,$txt);
    }
    public static function matches($pattern,$txt,$flags = 0,$offset = 0){
        preg_match($pattern,$txt,$matches,$flags,$offset);
        return $matches;
    }
    public static function replace($txt,$search,$replace){
        if(self::is($txt)){
            if(empty($replace)){
                $replace = '';
            }
            if(empty($search)){
                $search = '';
            }
            if(self::is($search) && self::is($replace)){
                return str_replace($search,$replace,$txt);
            }
            $replaceCount = 1;
            $search = is_array($search) ? $search : [$search];
            $replace = is_array($replace) ? $replace : [$replace];
            if(count($search) < count($replace)){
                $def = count($replace) - count($search);
                for($i=0 ; $i < $def ; $i++){
                    $search[] = end($search);
                }
            }
            if(count($replace) < count($search)){
                $def = count($search) - count($replace);
                for($i=0 ; $i < $def ; $i++){
                    $replace[] = end($replace);
                }
            }
            for($i=0 ; $i < count($search) ; $i++){
                $_search = preg_quote($search[$i]);
                $_replace = $replace[$i];
                $txt = preg_replace('/'.$_search.'/',$_replace,$txt,1);
            }
            return $txt;
        }
        return $txt;
    }
    public static function contains($txt,$search){
        if(self::is($search)){
            return strpos($txt,$search) !== false;
        }else if(is_array($search)){
            foreach($search as $key){
                if(!self::contains($txt,$key)){
                    return false;
                }
            }
            return true;
        }
        return false;
    }
    public static function pos($txt,$search){
        return strpos($txt,$search);
    }
    public static function length($txt){
        return strlen($txt);
    }
    public static function range($txt,$start,$end){
        return substr($txt,$start,$end-$start);
    }
    public static function dRange($txt,$start,$end){
        return substr($txt,$start,self::length($txt)-$end-$start);
    }
    public static function limit($txt,$limit){
        return substr($txt,0,$limit);
    }
    public static function rLimit($txt,$limit){
        return substr($txt,self::length($txt)-$limit);
    }
    public static function afterLimit($txt,$limit){
        return substr($txt,$limit);
    }
    public static function beforeLimit($txt,$limit){
        return substr($txt,0,self::length($txt)-$limit);
    }
    public static function before($txt,$search){
        return self::contains($txt,$search) ? self::limit($txt,self::length($txt)-self::length($search)) : '';
    }
    public static function after($txt,$search){
        return self::contains($txt,$search) ? self::rLimit($txt,self::length($txt)-self::length($search)) : '';
    }
    public static function starts($txt,$search){
        return $search . self::after($txt,$search) == $txt;
    }
    public static function ends($txt,$search){
        return self::before($txt,$search) . $search == $txt;
    }
    public static function addStart($txt,$search){
        return !self::starts($txt,$search) ? $search . $txt : $txt;
    }
    public static function addEnd($txt,$search){
        return !self::ends($txt,$search) ? $txt . $search : $txt;
    }
    public static function removeStart($txt,$search){
        if(self::is($search)){
            return self::starts($txt,$search) ? self::afterLimit($txt,self::length($search)) : $txt;
        }else if(is_array($search)){
            foreach($search as $item){
                if(self::starts($txt,$item)){
                    return self::afterLimit($txt,self::length($item));
                }
            }
        }
        return $txt;
    }
    public static function removeEnd($txt,$search){
        if(self::is($search)){
            return self::ends($txt,$search) ? self::beforeLimit($txt,self::length($search)) : $txt;
        }else if(is_array($search)){
            foreach($search as $item){
                if(self::ends($txt,$item)){
                    return self::beforeLimit($txt,self::length($item));
                }
            }
        }
        return $txt;
    }
    public static function upper($txt){
        return strtoupper($txt);
    }
    public static function lower($txt){
        return strtolower($txt);
    }
    public static function isUpper($txt){
        return ctype_upper(self::replace($txt,' ',''));
    }
    public static function isLower($txt){
        return ctype_lower(self::replace($txt,' ',''));
    }
    public static function title($txt){
        return ucwords($txt);
    }
    public static function words($txt, $count = 10, $end = '...'){
        $matches = self::matches('/^\s*+(?:\S++\s*+){1,'.$count.'}/u', $txt);
        if (empty($matches[0]) || self::length($txt) === self::length($matches[0])){
            return $txt;
        }
        return rtrim($matches[0]) . $end;
    }
    public static function random($length = 10,$type = null){
        if(!is_numeric($length)){
            return '';
        }
        if($type === A3SR_LTR){
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }else if($type === A3SR_NUM){
            $chars = '0123456789';
        }else{
            $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $text = '';
        for($i=0;$i<$length;$i++){
            $text .= $chars[rand(0,strlen($chars)-1)];
        }
        return $text;
    }
    public static function replaceArray($txt,$replace){
        if(is_array($replace) && count($replace)){
            foreach($replace as $key=>$value){
                $matchCase = self::limit($key,1) === ':'?true:false;
                $key = self::limit($key,1) === ':'?self::afterLimit($key,1):$key;
                $replaceVlaue = $value;
                if($matchCase){
                    if(self::isUpper($key)){
                        $replaceVlaue = self::upper($value);
                    }else if(self::isLower($key)){
                        $replaceVlaue = self::lower($value);
                    }else if(self::title($key) === $key){
                        $replaceVlaue = self::title($value);
                    }
                }
                $txt = preg_replace('/:\b'.$key.'\b/i',$replaceVlaue,$txt);
            }
        }
        return $txt;
    }
    public static function getPath($txt){
        return self::contains($txt,'.') ? explode('.', $txt) : [$txt];
    }
    public static function getViewPath($txt){
        return self::contains($txt,'.') ? self::replace($txt,'.','/') : $txt;
    }
    public static function getCallback($txt){
        if(self::contains($txt,'@')){
            $txt = explode('@',$txt);
            if(count($txt) === 2){
                return $txt;
            }
        }
        return false;
    }
}