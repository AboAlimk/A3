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

define("A3_ROOT", A3::getRootUrl());
define("A3_DOMAIN", A3::getDomain());

//A3View header
const A3VH_HTML = 'Content-Type: text/html; charset=utf-8';
const A3VH_JSON = 'Content-Type:application/json';
define("A3VH_E404", $_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
define("A3VH_E500", $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
const A3VH_CSS = 'Content-Type: text/css';
const A3VH_JS = 'Content-Type: application/javascript';
const A3VH_RSS = 'Content-Type: application/rss+xml; charset=utf-8';
const A3VH_JPEG = 'Content-Type: image/jpeg';
const A3VH_PNG = 'Content-Type: image/png';
const A3VH_SVG = 'Content-Type: image/svg+xml';
const A3VH_GIF = 'Content-Type: image/gif';
const A3VH_BMP = 'Content-Type: image/bmp';
const A3VH_ICO = 'Content-Type: image/ico';
const A3VH_PDF = 'Content-type: application/pdf';
const A3VH_TEXT = 'Content-Type: text/plain';
const A3VH_XML = 'Content-type: text/xml';
//A3View header

//A3Array sort
const A3AS_ACS = 1;
const A3AS_DESCS = 2;
const A3AS_KACS = 3;
const A3AS_KDESCS = 4;
//A3Array sort

//A3String random
const A3SR_LTR = 1;
const A3SR_NUM = 2;
//A3String random

//A3Request subdomain
const A3RSD_ALL = 1;
//A3Request subdomain

//A3RequestReferer referer
const A3RR_DOM = 1;
//A3RequestReferer referer