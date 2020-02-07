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
class A3AutoLoader{
    private const CONSTANTS_FILE_URI = '/A3App/A3Constants.php';
    private const HELPERS_FILE_URI = '/A3App/A3Helpers.php';
    private const REQUEST_FILE_URI = '/A3User/A3Request.php';
    private static $includedClasses = [];
    private static $classes = [];
    public static function load(){
        ob_start();
        self::$classes = require __DIR__.'/A3AutoLoaderClasses.php';
        spl_autoload_register([__CLASS__,'loadClasses']);
        self::createAliases();
        self::loadConstantsFile();
        self::loadHelpersFile();
        self::loadErrorFile();
        self::loadDirs();
        self::loadLanguages();
        self::checkSecure();
        self::setDefaultTimeZone();
        self::loadA3RequestsFile();
        self::processData();
    }
    private static function loadClasses($name){
        $classLoadStatus = self::checkClassLoadStatus($name);
        if($classLoadStatus){
            if(array_key_exists($name,self::$classes)){
                self::loadClassFile(A3GetPathValue(self::$classes,[$name,'uri']));
            }else{
                self::loadClassFile(self::parseNameSpace($name));
            }
        }
    }
    private static function checkClassLoadStatus($class){
        if(!array_key_exists($class,self::$includedClasses)){
            self::$includedClasses[$class] = true;
            return true;
        }
        return false;
    }
    private static function loadClassFile($file){
        $file = self::checkFile($file);
        if($file){
            require $file;
        }
    }
    private static function checkFile($file){
        $file = A3_ROOT_DIR.$file;
        if($file&&is_file($file)&&file_exists($file)){
            return $file;
        }
        return false;
    }
    private static function parseNameSpace($nameSpace){
        return '/'.str_replace('\\','/',$nameSpace).'.php';
    }
    private static function checkSecure(){
        if(A3Settings('force_ssl')){
            A3App\A3Redirect::redirectToSecure();
        }
    }
    private static function createAliases(){
        foreach(self::$classes as $key=>$value){
            $alias = $value['alias'];
            $class = $value['class'];
            if($alias && $class){
                if(is_string($alias)){
                    class_alias($class,$alias);
                } 
            }
        }
    }
    private static function loadConstantsFile(){
        $file = self::checkFile(self::CONSTANTS_FILE_URI);
        if($file){
            require $file;
        }
    }
    private static function loadHelpersFile(){
        $file = self::checkFile(self::HELPERS_FILE_URI);
        if($file){
            require $file;
        }
    }
    private static function loadErrorFile(){
        A3Error::initError();
    }
    private static function loadDirs(){
        $dirs = require __DIR__.'/A3AutoLoaderDir.php';
        foreach($dirs as $dir){
            $dirLoc = A3GetPathValue($dir,'dir');
            $dirFiles = A3GetPathValue($dir,'files');
            $dirFn = A3GetPathValue($dir,'function');
            $files = false;
            if($dirLoc){
                $files = self::processDirFiles($dirLoc,glob(A3_ROOT_DIR.$dirLoc.'*.php'));
            }
            if($dirFiles){
                $files = $dirFiles;
            }
            if($files){
                foreach($files as $name=>$file){
                    $file = self::checkFile($file);
                    if($file){
                        if($dirFn && is_callable($dirFn)){
                            if(is_string($name)){
                                $dirFn($name,$file);
                            }else{
                                $dirFn($file);
                            }
                        }else{
                            require $file;
                        }
                    }
                }
            }
        }
    }
    private static function processDirFiles($dir,$files){
        $array = [];
        foreach($files as $file){
            if(is_file($file)){
                $name = pathinfo($file, PATHINFO_FILENAME);
                $array[$name] = $dir.$name.'.php';
            }
        }
        return $array;
    }
    private static function loadLanguages(){
        A3App\A3Language::fetchLanguages();
    }
    private static function setDefaultTimeZone(){
        date_default_timezone_set(A3Settings('time_zone'));
    }
    private static function loadA3RequestsFile(){
        $file = self::checkFile(self::REQUEST_FILE_URI);
        if($file){
            require $file;
        }
    }
    private static function processData(){
        A3App\A3Processor::process();
    }
}
A3AutoLoader::load();