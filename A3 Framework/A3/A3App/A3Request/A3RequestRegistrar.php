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
use A3Error;

class A3RequestRegistrar{
    private $methods = ["get","post","put","delete"];
    private $requestId = '';
    private $requestMethod;
    private $requestRoute;
    private $requestProcess;
    private $requestName;
    private $requestSubDomain;
    private $requestMiddleProcess;
    private $requestReferer;
    private $requestWhere = [];
    private $requestDebugData = [];
    public function __construct($method, $parameters , $debugData = []){
        $this->requestId = 'A3Request_'.A3GRS(15);
        if($method === 'match'){
            $this->requestMethod = $parameters[0];
            $this->requestRoute = $parameters[1];
            $this->requestProcess = $parameters[2];
        }else{
            if($method === 'any'){
                $this->requestMethod = $this->methods;
            }else{
                $this->requestMethod = $method;
            }
            $this->requestRoute = $parameters[0];
            $this->requestProcess = $parameters[1];
        }
        if(!is_array($this->requestMethod)){
            $this->requestMethod = [$this->requestMethod];
        }
        $this->requestDebugData = $debugData;
    }
    public function __call($method, $parameters){
        $methods = [
            'name' => 1,
            'subDomain' => 1,
            'middleProcess' => 1,
            'referer' => 1,
            'where' => 2
        ];
        if(!array_key_exists($method,$methods)){
            self::__error('undefined_method',['A3Request::'.$method],$this->requestDebugData);
        }
        $methodCount = $methods[$method];
        if(empty($parameters) || !is_array($parameters) || count($parameters)<$methodCount){
            self::__error('error_parameters_count',['A3Request::'.$method,$methodCount],$this->requestDebugData);
        }
        if($method==='name'){
            if(!is_string($parameters[0]) || $parameters[0] === ''){
                self::__error('error_parameter_type',[$method,'string'],$this->requestDebugData);
            }
            if($parameters[0]){
                $this->requestName = $parameters[0];
            }
        }else if($method==='subDomain'){
            if($parameters[0] !== A3RSD_ALL && (!is_string($parameters[0]) || $parameters[0] === '')){
                self::__error('error_parameter_type',[$method,'string or A3RSD_ALL'],$this->requestDebugData);
            }
            $this->requestSubDomain  = $parameters[0];
        }else if($method==='middleProcess'){
            $this->requestMiddleProcess  = $parameters[0];
        }else if($method==='referer'){
            $this->requestReferer  = $parameters[0];
        }else if($method==='where'){
            if(!is_string($parameters[0]) || $parameters[0] === ''){
                self::__error('error_parameter_type',['Where key','string'],$this->requestDebugData);
            }
            if(!is_string($parameters[1]) || $parameters[1] === ''){
                self::__error('error_parameter_type',['Where value','string'],$this->requestDebugData);
            }
            $this->requestWhere[$parameters[0]] = $parameters[1];
        }
        return $this;
    }
    public function __destruct(){
        $haveName = is_null($this->requestName) ? false : true;
        $this->requestName = is_null($this->requestName) ? $this->requestId.spl_object_hash($this) : $this->requestName;
        $subDomainCount = 0;
        if($this->requestSubDomain && is_string($this->requestSubDomain)){
            $subDomainCount = substr_count($this->requestSubDomain,'.') + 1;
        }
        $subDomainAll = $this->requestSubDomain === A3RSD_ALL ? true : false;
        A3RequestData::addA3Request([
            'method' => $this->requestMethod,
            'route' => $this->requestRoute,
            'process' => $this->requestProcess,
            'where' => $this->requestWhere,
            'name' => $this->requestName,
            'havename' => $haveName,
            'subdomain' => $this->requestSubDomain,
            'subdomaincount' => $subDomainCount,
            'subdomainall' => $subDomainAll,
            'middleprocess' => $this->requestMiddleProcess,
            'referer' => $this->requestReferer,
            'debugData' => $this->requestDebugData
        ]);
    }
    private static function __error($text,$replace,$debugData){
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => $replace,
            'a3Class' => 'A3Request',
            'a3Function' => '__callStatic',
            'debugData' => $debugData,
        ]);
    }
}