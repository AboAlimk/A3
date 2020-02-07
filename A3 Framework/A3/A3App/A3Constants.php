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
define("A3_ROOT",A3::getRootUrl());
define("A3_DOMAIN",A3::getDomain());

//A3View header
define("A3VH_HTML",'Content-Type: text/html; charset=utf-8');
define("A3VH_JSON",'Content-Type:application/json');
define("A3VH_E404",$_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
define("A3VH_E500",$_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error');
define("A3VH_CSS",'Content-Type: text/css');
define("A3VH_JS",'Content-Type: application/javascript');
define("A3VH_RSS",'Content-Type: application/rss+xml; charset=utf-8');
define("A3VH_JPEG",'Content-Type: image/jpeg');
define("A3VH_PNG",'Content-Type: image/png');
define("A3VH_SVG",'Content-Type: image/svg+xml');
define("A3VH_GIF",'Content-Type: image/gif');
define("A3VH_BMP",'Content-Type: image/bmp');
define("A3VH_ICO",'Content-Type: image/ico');
define("A3VH_PDF",'Content-type: application/pdf');
define("A3VH_TEXT",'Content-Type: text/plain');
define("A3VH_XML",'Content-type: text/xml');
//A3View header

//A3Array sort
define("A3AS_ACS",1);
define("A3AS_DESCS",2);
define("A3AS_KACS",3);
define("A3AS_KDESCS",4);
//A3Array sort

//A3String random
define("A3SR_LTR",1);
define("A3SR_NUM",2);
//A3String random

//A3Request subdomain
define("A3RSD_ALL",1);
//A3Request subdomain

//A3RequestReferer referer
define("A3RR_DOM",1);
//A3RequestReferer referer