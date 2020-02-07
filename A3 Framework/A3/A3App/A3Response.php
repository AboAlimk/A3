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

class A3Response{
    private $content;
    private $headers = [];
    public function __construct($content = '', $headers = []){
        $this->content = $content;
        if(!is_array($headers)){
            $headers = [$headers];
        }
        $this->headers = $headers;
    }
    public function header($header){
        if(is_string($header)){
            $this->headers[] = $header;
        }
        return $this;
    }
    public function content($content){
        $this->content = $content;
        return $this;
    }
    public function addContent($content){
        $this->content .= $content;
        return $this;
    }
    public function render(){
        $this->content = $this->content ? $this->content : '';
        if(count($this->headers)){
            foreach($this->headers as $header){
                header($header);
            }
        }
        print_r($this->content);
    }
}