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
namespace A3App\A3View;

class A3ViewExtends{
    private $sections = [];
    public function __construct(){}
    public function sectionStart($name, $data = null){
        if(is_null($data)){
            $data = $name;
            if(ob_get_length() > 0){
                ob_end_clean();
            }
            ob_start();
        }
        $this->sections[$name] = $data;
    }
    public function sectionEnd(){
        $last = array_pop($this->sections);
        $this->sections[$last] = ob_get_clean();
        return $this->sections[$last];
    }
    public function getReplace($name){
        $data = A3GetPathValue($this->sections, $name, '');
        echo $data;
    }
}