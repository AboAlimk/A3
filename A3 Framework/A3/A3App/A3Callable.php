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

class A3Callable{
    public static function call($callable,$base_var,$vars = null){
        $callableVars = [$base_var];
        if(is_array($vars)){
            $callableVars = array_merge($callableVars,$vars);
        }
        for($i=0; $i<10 ; $i++){
            $callableVars[] = null;
        }
        call_user_func_array($callable,$callableVars);
    }
}