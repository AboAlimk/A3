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
use A3Error;

class A3File{
    private const A3FILES_DIR = '/../A3User/A3Storage/A3File';
    public static function write($name,$txt = ''){
        $link = self::getA3FilesDir($name);
        if(!self::isValidName(basename($link))){
            self::__error('a3files_name_error',0,__FUNCTION__);
        }
        $dir = dirname($link);
        if(!is_dir($dir)){
            mkdir($dir, 0755 , true);
        }
        return file_put_contents($link,$txt) !== false;
    }
    public static function append($name,$txt = ''){
        $link = self::getA3FilesDir($name);
        if(!is_file($link)){
            self::__error('a3files_read_error',$name,__FUNCTION__);
        }
        return file_put_contents($link,$txt,FILE_APPEND | LOCK_EX) !== false;
    }
    public static function read($name){
        $link = self::getA3FilesDir($name);
        if(!is_file($link)){
            self::__error('a3files_read_error',$name,__FUNCTION__);
        }
        $file = fopen($link, "r");
        $fileSize = filesize($link);
        if($fileSize === 0){
            return '';
        }
        $out = fread($file,$fileSize);
        fclose($file);
        return $out;
    }
    public static function getFullUri($name){
        $link = self::getA3FilesDir($name);
        if(!is_file($link)){
            return '';
        }
        return $link;
    }
    private static function getA3FilesDir($link){
        $baseDir = __DIR__.self::A3FILES_DIR;
        if(!is_dir($baseDir)){
            mkdir($baseDir);
        }
        $link = substr($link,0,1) === '/' ? substr($link,1) : $link;
        $link = substr($link,strlen($link)-1) === '/' ? substr($link,0,strlen($link)-1) : $link;
        return $baseDir.'/'.$link;
    }
    private static function isValidName($name){
        if(strpos($name,"\\") !== false || preg_match('(\\s|\/|\\|\:|\*|\?|\"|\<|\>|\|)',$name)){
            return false;
        }
        return true;
    }
    private static function __error($text,$replace,$a3Function){
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => [$replace],
            'a3Class' => 'A3File',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace(),
        ]);
    }
}