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

namespace A3App;

class A3String
{

    public static function pos($txt, $search): int|false
    {
        return strpos($txt, $search);
    }

    public static function range($txt, $start, $end): string
    {
        return mb_substr($txt, $start, $end - $start);
    }

    public static function dRange($txt, $start, $end): string
    {
        return mb_substr($txt, $start, self::length($txt) - $end - $start);
    }

    public static function length($txt): int
    {
        return mb_strlen($txt);
    }

    public static function addStart($txt, $search): string
    {
        return !self::starts($txt, $search) ? $search . $txt : $txt;
    }

    public static function starts($txt, $search): bool
    {
        return $search . self::after($txt, $search) == $txt;
    }

    public static function after($txt, $search): string
    {
        return self::contains($txt, $search) ? self::rLimit($txt, self::length($txt) - self::length($search)) : '';
    }

    public static function contains($txt, $search): bool
    {
        if (self::is($search)) {
            return str_contains($txt, $search);
        } else if (is_array($search)) {
            foreach ($search as $key) {
                if (!self::contains($txt, $key)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public static function is($pattern, $value = null): bool
    {
        if (is_null($value)) {
            return is_string($pattern);
        }
        if ($pattern == $value) {
            return true;
        }
        $pattern = preg_quote($pattern, '#');
        $pattern = self::replace($pattern, '\*', '.*');
        return self::match('#^' . $pattern . '\z#u', $value) === 1;
    }

    public static function replace($txt, $search, $replace)
    {
        if (self::is($txt)) {
            if (empty($replace)) {
                $replace = '';
            }
            if (empty($search)) {
                $search = '';
            }
            if (self::is($search) && self::is($replace)) {
                return str_replace($search, $replace, $txt);
            }
            $search = is_array($search) ? $search : [$search];
            $replace = is_array($replace) ? $replace : [$replace];
            if (count($search) < count($replace)) {
                $def = count($replace) - count($search);
                for ($i = 0; $i < $def; $i++) {
                    $search[] = end($search);
                }
            }
            if (count($replace) < count($search)) {
                $def = count($search) - count($replace);
                for ($i = 0; $i < $def; $i++) {
                    $replace[] = end($replace);
                }
            }
            for ($i = 0; $i < count($search); $i++) {
                $_search = preg_quote($search[$i]);
                $_replace = $replace[$i];
                $txt = preg_replace('/' . $_search . '/', $_replace, $txt, 1);
            }
            return $txt;
        }
        return $txt;
    }

    public static function match($pattern, $txt): int|false
    {
        return preg_match($pattern, $txt);
    }

    public static function rLimit($txt, $limit): string
    {
        return mb_substr($txt, self::length($txt) - $limit);
    }

    public static function addEnd($txt, $search): string
    {
        return !self::ends($txt, $search) ? $txt . $search : $txt;
    }

    public static function ends($txt, $search): bool
    {
        return self::before($txt, $search) . $search == $txt;
    }

    public static function before($txt, $search): string
    {
        return self::contains($txt, $search) ? self::limit($txt, self::length($txt) - self::length($search)) : '';
    }

    public static function limit($txt, $limit): string
    {
        return mb_substr($txt, 0, $limit);
    }

    public static function removeStart($txt, $search)
    {
        if (self::is($search)) {
            return self::starts($txt, $search) ? self::afterLimit($txt, self::length($search)) : $txt;
        } else if (is_array($search)) {
            foreach ($search as $item) {
                if (self::starts($txt, $item)) {
                    return self::afterLimit($txt, self::length($item));
                }
            }
        }
        return $txt;
    }

    public static function afterLimit($txt, $limit): string
    {
        return mb_substr($txt, $limit);
    }

    public static function removeEnd($txt, $search)
    {
        if (self::is($search)) {
            return self::ends($txt, $search) ? self::beforeLimit($txt, self::length($search)) : $txt;
        } else if (is_array($search)) {
            foreach ($search as $item) {
                if (self::ends($txt, $item)) {
                    return self::beforeLimit($txt, self::length($item));
                }
            }
        }
        return $txt;
    }

    public static function beforeLimit($txt, $limit): string
    {
        return mb_substr($txt, 0, self::length($txt) - $limit);
    }

    public static function words($txt, $count = 10, $end = '...'): string
    {
        $txt = strip_tags($txt);
        $words = explode(' ', strip_tags($txt));
        $str = trim(implode(' ', array_slice($words, 0, $count)));
        if (mb_strlen($str) < mb_strlen($txt)) {
            $str .= $end;
        }
        return $str;
    }

    public static function matches($pattern, $txt, $flags = 0, $offset = 0)
    {
        preg_match($pattern, $txt, $matches, $flags, $offset);
        return $matches;
    }

    public static function random($length = 10, $type = null): string
    {
        if (!is_numeric($length)) {
            return '';
        }
        if ($type === A3SR_LTR) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        } else if ($type === A3SR_NUM) {
            $chars = '0123456789';
        } else {
            $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $text = '';
        for ($i = 0; $i < $length; $i++) {
            $text .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $text;
    }

    public static function replaceArray($txt, $replace): string
    {
        if (is_array($replace) && count($replace)) {
            foreach ($replace as $key => $value) {
                $matchCase = self::limit($key, 1) === ':';
                $key = self::limit($key, 1) === ':' ? self::afterLimit($key, 1) : $key;
                $replaceValue = $value ?: '';
                if ($matchCase) {
                    if (self::isUpper($key)) {
                        $replaceValue = self::upper($value);
                    } else if (self::isLower($key)) {
                        $replaceValue = self::lower($value);
                    } else if (self::title($key) === $key) {
                        $replaceValue = self::title($value);
                    }
                }
                $txt = preg_replace('/:\b' . $key . '\b/i', $replaceValue, $txt);
            }
        }
        return $txt;
    }

    public static function isUpper($txt): bool
    {
        return ctype_upper(self::replace($txt, ' ', ''));
    }

    public static function upper($txt): string
    {
        return strtoupper($txt);
    }

    public static function isLower($txt): bool
    {
        return ctype_lower(self::replace($txt, ' ', ''));
    }

    public static function lower($txt): string
    {
        return strtolower($txt);
    }

    public static function title($txt): string
    {
        return ucwords($txt);
    }

    public static function getPath($txt): array
    {
        return self::contains($txt, '.') ? explode('.', $txt) : [$txt];
    }

    public static function getViewPath($txt)
    {
        return self::contains($txt, '.') ? self::replace($txt, '.', '/') : $txt;
    }

    public static function getCallback($txt): array|bool
    {
        if (self::contains($txt, '@')) {
            $txt = explode('@', $txt);
            if (count($txt) === 2) {
                return $txt;
            }
        }
        return false;
    }

    public static function camel($txt): string
    {
        if(!str_contains($txt, '-') && !str_contains($txt, '_')){
            return $txt;
        }
        $txt = implode('',array_map(function($i){
            return ucfirst($i);
        },explode('-',$txt)));
        return implode('',array_map(function($i){
            return ucfirst($i);
        },explode('_',$txt)));
    }

    public static function snake($txt,$replace = '_'): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $txt, $matches);
        $out = $matches[0];
        return implode($replace, array_map(function($i){
            return $i == strtoupper($i) ? strtolower($i) : lcfirst($i);
        },$out));
    }

    public static function mask($txt, $mask, $start, $end = false): string
    {
        $strLength = mb_strlen($txt);
        if(!$strLength || $start >= $strLength){
            return $txt;
        }
        if(!$mask){
            $mask = '*';
        }
        if($end === false){
            $startIndex = $strLength - $start;
            return mb_substr($txt,0, $start).str_repeat($mask, $startIndex);
        }else{
            $startIndex = $strLength - $start - $end;
            $endIndex = $start + $startIndex;
            return mb_substr($txt,0, $startIndex).str_repeat($mask, $start).mb_substr($txt, $endIndex);
        }
    }

}