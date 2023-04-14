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

/**
 * place you requests here
 */

A3Request::get('/','requestProcess@home')
    ->middleProcess('requestMiddleProcess@processLanguage')
    ->name('welcome');

A3Request::get('/{lng}','requestProcess@home')
    ->middleProcess('requestMiddleProcess@process')
    ->where('lng','^[a-zA-Z]{2}$')
    ->name('home');