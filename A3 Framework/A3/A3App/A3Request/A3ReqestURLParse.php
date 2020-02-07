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
namespace A3App\A3Request;

class A3ReqestURLParse{
    private $scheme;
    private $host;
    private $isIp = false;
    private $domain;
    private $subDomain;
    private $port;
    private $path;
    private $query;
    private $fragment;
    public function __construct($url){
        $this->parseUrl($url);
    }
    public static function parse($url){
        return new A3ReqestURLParse($url);
    }
    private function parseUrl($url){
        if(substr($url,0,4)!=='http'){
            $url = 'http://'.$url;
        }
        $urlObject = parse_url($url);
        $this->scheme = strtolower(A3GetPathValue($urlObject,'scheme',''));
        $this->host = strtolower(A3GetPathValue($urlObject,'host'));
        $this->isIp = (boolean) ip2long($this->host);
        $this->domain = $this->getDomain($this->host,$this->isIp);
        $this->subDomain = $this->getSubDomain($this->host,$this->isIp);
        $this->port = A3GetPathValue($urlObject,'port','');
        $this->path = strtolower(A3GetPathValue($urlObject,'path',''));
        $this->query = strtolower(A3GetPathValue($urlObject,'query',''));
        $this->fragment = strtolower(A3GetPathValue($urlObject,'fragment',''));
    }
    private function getDomain($host,$isIp){
        if($host){
            if($isIp){
                return $host;
            }
            if(strpos($host,'.') === false){
                return $host;
            }
            $host = explode('.',$host);
            $host = array_slice($host, -2 ,2);
            $host = implode('.',$host);
            return $host;
        }
        return '';
    }
    private function getSubDomain($host,$isIp){
        if($host && !$isIp){
            if(strpos($host,'.') === false){
                return $host;
            }
            $host = explode('.',$host);
            $host = array_slice($host, 0 , -2);
            $host = implode('.',$host);
            return $host;
        }
        return '';
    }
    public function isSecure(){
        return $this->scheme == 'https';
    }
    public function scheme(){
        return $this->scheme;
    }
    public function host(){
        return $this->host;
    }
    public function domain(){
        return $this->domain;
    }
    public function subDomain(){
        return $this->subDomain;
    }
    public function port(){
        return $this->port;
    }
    public function path(){
        return $this->path;
    }
    public function query(){
        return $this->query;
    }
    public function fragment(){
        return $this->fragment;
    }
    public static function match($subDomain,$www){
        $baseUrlParser = new A3ReqestURLParse(A3_DOMAIN);
        $currentSubDomain = $baseUrlParser->subDomain();
        $subDomain = $subDomain ? $subDomain : '';
        $currentSubDomain = $currentSubDomain ? $currentSubDomain : '';
        $subDomain = strtolower($subDomain);
        $currentSubDomain = strtolower($currentSubDomain);
        if(!$www){
            if($currentSubDomain === 'www'){
                $currentSubDomain = '';
            }
            if(substr($currentSubDomain,0,4) === 'www.'){
                $currentSubDomain = substr($currentSubDomain,4);
            }
        }
        return $subDomain === $currentSubDomain; 
    }
}