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

class requestProcess{

    public function home($request){
        return A3View('home',['lng' => $request->getRequestRouteItems('lng')]);
    }

}