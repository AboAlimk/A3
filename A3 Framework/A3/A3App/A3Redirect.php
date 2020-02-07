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
use A3;

class A3Redirect{
    private $url = '';
    private $isA3Request = false;
    private $code = 302;
    private $a3Request;
    private $a3RequestReplace;
    public function __construct($url = null , $code = 302){
        $this->url = $url;
        $this->code = $code;
    }
    public function a3Request($name, $replace = null){
        $this->isA3Request = true;
        $this->a3Request = $name;
        $this->a3RequestReplace = $replace;
        return $this;
    }
    public function code($code){
        $this->code = $code;
        return $this;
    }
    public function getCode(){
        return $this->code;
    }
    public function getUrl(){
        if($this->isA3Request){
            return A3RequestFull($this->a3Request, $this->a3RequestReplace);
        }else{
            return $this->parseUrl();
        }
    }
    private function parseUrl(){
        if(substr($this->url,0,1) === '/'){
            return A3_ROOT.$this->url;
        }
        return $this->url;
    }
    public function redirect(){
        header("Location: ".$this->getUrl(),true,$this->getCode());
    }
    public static function redirectToSecure(){
        if(!A3::isSecure()){
            $redirect = new A3App\A3Redirect('https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],301);
            $redirect->redirect();
        }
    }
}