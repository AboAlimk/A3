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
namespace A3App\A3Settings;
use A3String;

class A3Settings{
  private static $A3SettingsArray = [];
  public static function process($key){
      $baseKey = 'base.'.$key;
      $userKey = 'user.'.$key;
      $base = A3GetPathValue(self::$A3SettingsArray, A3String::getPath($baseKey), null);
      $user = A3GetPathValue(self::$A3SettingsArray, A3String::getPath($userKey), null);
      if(is_null($user)){
          return $base;
      }
      return $user;
  }
  public static function loadSettingsFile($name,$file){
      self::$A3SettingsArray[$name] = require $file;
  }
}