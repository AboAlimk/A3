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

namespace A3App;

use A3App\A3View\A3View;

class A3Error
{

    private static bool $customError = false;
    private static string $errorMessage = '';
    private static string $errorFile = '';
    private static string $errorLine = '';
    private static bool $errorSkip = false;

    public static function initError(): void
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        register_shutdown_function([__CLASS__, 'handleError']);
    }

    public static function handleError(): void
    {
        $error = error_get_last();
        if (!is_null($error)) {
            self::parseError($error);
        }
    }

    public static function errorTrigger($data, $fire = true): void
    {
        self::$customError = $fire;
        $text = A3GetPathValue($data, "text", "");
        $replace = A3GetPathValue($data, "replace", []);
        $a3Class = strtolower(self::parseClass(A3GetPathValue($data, "a3Class", "")));
        $a3Function = strtolower(A3GetPathValue($data, "a3Function", ""));
        $debugData = A3GetPathValue($data, "debugData");
        self::$errorSkip = A3GetPathValue($data, "skip");
        $text = self::getErrorData($text);
        if ($replace && is_array($replace)) {
            $text = A3String::replace($text, '%s', $replace);
        }
        self::$errorMessage = $text;
        if ($debugData && is_array($debugData)) {
            foreach ($debugData as $item) {
                $debugClass = strtolower(self::parseClass(A3GetPathValue($item, "class", "")));
                $debugFunction = strtolower(A3GetPathValue($item, "function", ""));
                if ($a3Class == $debugClass && $a3Function == $debugFunction) {
                    self::$errorFile = A3GetPathValue($item, "file");
                    self::$errorLine = A3GetPathValue($item, "line");
                    break;
                }
            }
        }
        if ($fire) {
            trigger_error("A3Error");
            A3__exit__and__close();
        }
    }

    private static function parseClass($class)
    {
        if (is_string($class) && str_contains($class, '\\')) {
            $arr = explode('\\', $class);
            if ($arr && is_array($arr) && count($arr)) {
                $class = end($arr);
            }
        }
        return $class;
    }

    private static function parseError($error): void
    {
        A3__clean__output__buffer();
        A3View::publishHeader(A3VH_E500);
        A3View::publishHeader(A3VH_HTML);
        if (A3Settings("debug") === true) {
            if (self::$customError) {
                $errorPage = self::viewError(self::$errorMessage, self::$errorFile, self::$errorLine);
            } else {
                $error_level = A3GetPathValue($error, 'type');
                $error_message = A3GetPathValue($error, 'message');
                $error_file = A3GetPathValue($error, 'file');
                $error_line = A3GetPathValue($error, 'line');
                $_error_message = strtolower($error_message);
                if (str_contains($_error_message, 'mysqli::__construct():')) {
                    if (str_contains($_error_message, 'php_network_getaddresses')) {
                        $text = self::getErrorData('mysqli_connection_data_error');
                        $text = A3String::replace($text, '%s', 'host');
                        self::viewError($text, self::$errorFile, self::$errorLine);
                    } else if (str_contains($_error_message, 'access denied for user')) {
                        $text = self::getErrorData('mysqli_connection_data_error');
                        $text = A3String::replace($text, '%s', 'database username or password');
                        self::viewError($text, self::$errorFile, self::$errorLine);
                    } else if (str_contains($_error_message, 'unknown database')) {
                        $text = self::getErrorData('mysqli_connection_data_error');
                        $text = A3String::replace($text, '%s', 'database');
                        self::viewError($text, self::$errorFile, self::$errorLine);
                    } else {
                        $text = self::getErrorData('mysqli_connection_data_check');
                        self::viewError($text, self::$errorFile, self::$errorLine);
                    }
                }
                $errorPage = self::viewError($error_message, $error_file, $error_line);
            }
        } else {
            $errorPage = self::getErrorPage();
        }
        echo $errorPage;
        A3__exit__and__close();
    }

    private static function viewError($error_message, $error_file, $error_line): bool|string
    {
        $txt = '';
        if (!self::$errorSkip && is_file($error_file)) {
            if (!is_file($error_file)) {
                return false;
            }
            $file = fopen($error_file, "r");
            $txt = fread($file, filesize($error_file));
            fclose($file);
        }
        $error_message = $error_message ? '<div class="header-msg"><div class="header-msg-in">' . $error_message . '</div></div>' : '';
        $error_desc = !self::$errorSkip && $error_file && $error_line ? '<div class="header-file">' . $error_file . ' <strong>LINE</strong> ' . $error_line . '</div>' : '';
        $error_skip = !self::$errorSkip && $txt ? '<pre class="prettyprint linenums">' . htmlentities($txt) . '</pre>' : '';
        return '<!DOCTYPE html>
            <html lang="en">
               <head>
               <meta charset="utf-8">
               <meta name="viewport" content="width=device-width, initial-scale=1">
               <title>A3Error</title>
               <script>' . self::getErrorJS() . '</script>
               <style>' . self::getErrorCSS() . '</style>
               <style>ol.linenums>li:nth-child(' . $error_line . '){background:#d43030;padding: 2px;}</style>
               <script>addEventListener("load", PR.prettyPrint, false);</script>
            </head>
            <body>
                <div class="cont">
                    <div class="header-cont">
                        <div class="header">A3Error</div>
                            ' . $error_desc . '
                            ' . $error_message . '
                        </div>
                    </div>
                    <div class="body-cont">' . $error_skip . '</div>
                </div>
            </body>
            </html>';
    }

    private static function getErrorData($id)
    {
        $errorText = [
            'header' => 'A3Error',
            'error_happen' => 'Error Happen',
            'undefined_method' => 'Call to undefined method "<i>%s()</i>"',
            'error_parameters_count' => 'Method "<i>%s()</i>" require %s parameters',
            'error_parameter_type' => '<i>%s</i> should be %s',
            'a3request_name_exists' => '<strong>A3Request:</strong> Name is already exists "<i>%s</i>"',
            'a3request_name_not_valid' => '<strong>A3Request:</strong> Name can contain letters, numbers, underscore and dash "%s"',
            'a3request_middle_process_error' => '<strong>A3Request:</strong> MiddleProcess should return A3RequestObject, A3Redirect, A3View or A3Response',
            'a3request_process_error' => '<strong>A3Request:</strong> Process should return String, Number, Array, A3View, A3Redirect or A3Response',
            'a3files_read_error' => 'File not found or permission denied "%s"',
            'a3files_name_error' => 'Name can\'t contain any of "/ \ : * ? " < > |" or spaces',
            'a3database_error_method' => '<strong>A3DataBase:</strong> Call to undefined method "A3DataBase::<i>%s()</i>", use A3DataBase::connect()',
            'a3database_error_connection_type' => '<strong>A3DataBase:</strong> Method "connnect()" accept only instance of A3DataBaseConnection',
            'a3database_error_connect_method' => '<strong>A3DataBase:</strong> Call to undefined method "A3DataBase::connect()-><i>%s()</i>", use statement() or table()',
            'a3database_connection_error_method' => '<strong>A3DataBaseConnection:</strong> Call to undefined method "A3DataBaseConnection::<i>%s()</i>", use A3DataBaseConnection::new()',
            'mysqli_connection_data_error' => '<strong>A3DataBaseConnection:</strong> Invalid %s',
            'mysqli_connection_data_check' => '<strong>A3DataBaseConnection:</strong> Invalid connection parameters',
            'view_not_found' => 'View not found "<i>%s</i>"',
            'a3database_error_name' => '<strong>A3DataBase:</strong> Name can contain letters, numbers and underscore "%s"',
            'a3database_error_column_name_exists' => '<strong>A3DataBase:</strong> Name is already exists "<i>%s</i>"',
            'a3database_error_column_type' => '<strong>A3DataBase:</strong> Column type not valid "<i>%s</i>"',
        ];
        return A3GetPathValue($errorText, $id);
    }

    private static function getErrorPage(): string
    {
        return '
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>Server Error</title>
                <style>
                    html, body {
                        background-color: #353535;
                        color: #fff;
                        font-family: Tahoma,sans-serif;
                        margin: 0;
                        padding:0;
                    }
                    .cont{
                        text-align: center;
                        padding-top:50px;
                    }
                    .header{
                        font-size:100px;
                    }
                    .body{
                        font-size:24px;
                    }
                </style>
            </head>
            <body>
                <div class="cont">
                    <div class="header">OOPS!</div>
                    <div class="body">Error 500: Server Error</div>
                </div>
            </body>
        </html>
        ';
    }

    private static function getErrorJS(): string
    {
        return "
        !function(){
          \"undefined\"!==typeof window&&(window.PR_SHOULD_USE_CONTINUATION=!0);
          (function(){function T(a){function d(e){var a=e.charCodeAt(0);if(92!==a)return a;var c=e.charAt(1);return(a=w[c])?a:\"0\"<=c&&\"7\">=c?parseInt(e.substring(1),8):\"u\"===c||\"x\"===c?parseInt(e.substring(2),16):e.charCodeAt(1)}function f(e){if(32>e)return(16>e?\"\\\\x0\":\"\\\\x\")+e.toString(16);e=String.fromCharCode(e);return\"\\\\\"===e||\"-\"===e||\"]\"===e||\"^\"===e?\"\\\\\"+e:e}function c(e){var c=e.substring(1,e.length-1).match(RegExp(\"\\\\\\\\u[0-9A-Fa-f]{4}|\\\\\\\\x[0-9A-Fa-f]{2}|\\\\\\\\[0-3][0-7]{0,2}|\\\\\\\\[0-7]{1,2}|\\\\\\\\[\\\\s\\\\S]|-|[^-\\\\\\\\]\",\"g\"));
          e=[];var a=\"^\"===c[0],b=[\"[\"];a&&b.push(\"^\");for(var a=a?1:0,g=c.length;a<g;++a){var h=c[a];if(/\\\\[bdsw]/i.test(h))b.push(h);else{var h=d(h),k;a+2<g&&\"-\"===c[a+1]?(k=d(c[a+2]),a+=2):k=h;e.push([h,k]);65>k||122<h||(65>k||90<h||e.push([Math.max(65,h)|32,Math.min(k,90)|32]),97>k||122<h||e.push([Math.max(97,h)&-33,Math.min(k,122)&-33]))}}e.sort(function(e,a){return e[0]-a[0]||a[1]-e[1]});c=[];g=[];for(a=0;a<e.length;++a)h=e[a],h[0]<=g[1]+1?g[1]=Math.max(g[1],h[1]):c.push(g=h);for(a=0;a<c.length;++a)h=
          c[a],b.push(f(h[0])),h[1]>h[0]&&(h[1]+1>h[0]&&b.push(\"-\"),b.push(f(h[1])));b.push(\"]\");return b.join(\"\")}function m(e){for(var a=e.source.match(RegExp(\"(?:\\\\[(?:[^\\\\x5C\\\\x5D]|\\\\\\\\[\\\\s\\\\S])*\\\\]|\\\\\\\\u[A-Fa-f0-9]{4}|\\\\\\\\x[A-Fa-f0-9]{2}|\\\\\\\\[0-9]+|\\\\\\\\[^ux0-9]|\\\\(\\\\?[:!=]|[\\\\(\\\\)\\\\^]|[^\\\\x5B\\\\x5C\\\\(\\\\)\\\\^]+)\",\"g\")),b=a.length,d=[],g=0,h=0;g<b;++g){var k=a[g];\"(\"===k?++h:\"\\\\\"===k.charAt(0)&&(k=+k.substring(1))&&(k<=h?d[k]=-1:a[g]=f(k))}for(g=1;g<d.length;++g)-1===d[g]&&(d[g]=++E);for(h=g=0;g<b;++g)k=a[g],
          \"(\"===k?(++h,d[h]||(a[g]=\"(?:\")):\"\\\\\"===k.charAt(0)&&(k=+k.substring(1))&&k<=h&&(a[g]=\"\\\\\"+d[k]);for(g=0;g<b;++g)\"^\"===a[g]&&\"^\"!==a[g+1]&&(a[g]=\"\");if(e.ignoreCase&&q)for(g=0;g<b;++g)k=a[g],e=k.charAt(0),2<=k.length&&\"[\"===e?a[g]=c(k):\"\\\\\"!==e&&(a[g]=k.replace(/[a-zA-Z]/g,function(a){a=a.charCodeAt(0);return\"[\"+String.fromCharCode(a&-33,a|32)+\"]\"}));return a.join(\"\")}for(var E=0,q=!1,l=!1,n=0,b=a.length;n<b;++n){var p=a[n];if(p.ignoreCase)l=!0;else if(/[a-z]/i.test(p.source.replace(/\\\\u[0-9a-f]{4}|\\\\x[0-9a-f]{2}|\\\\[^ux]/gi,
          \"\"))){q=!0;l=!1;break}}for(var w={b:8,t:9,n:10,v:11,f:12,r:13},r=[],n=0,b=a.length;n<b;++n){p=a[n];if(p.global||p.multiline)throw Error(\"\"+p);r.push(\"(?:\"+m(p)+\")\")}return new RegExp(r.join(\"|\"),l?\"gi\":\"g\")}function U(a,d){function f(a){var b=a.nodeType;if(1==b){if(!c.test(a.className)){for(b=a.firstChild;b;b=b.nextSibling)f(b);b=a.nodeName.toLowerCase();if(\"br\"===b||\"li\"===b)m[l]=\"\\n\",q[l<<1]=E++,q[l++<<1|1]=a}}else if(3==b||4==b)b=a.nodeValue,b.length&&(b=d?b.replace(/\\r\\n?/g,\"\\n\"):b.replace(/[ \\t\\r\\n]+/g,
          \" \"),m[l]=b,q[l<<1]=E,E+=b.length,q[l++<<1|1]=a)}var c=/(?:^|\\s)nocode(?:\\s|$)/,m=[],E=0,q=[],l=0;f(a);return{a:m.join(\"\").replace(/\\n$/,\"\"),c:q}}function J(a,d,f,c,m){f&&(a={h:a,l:1,j:null,m:null,a:f,c:null,i:d,g:null},c(a),m.push.apply(m,a.g))}function V(a){for(var d=void 0,f=a.firstChild;f;f=f.nextSibling)var c=f.nodeType,d=1===c?d?a:f:3===c?W.test(f.nodeValue)?a:d:d;return d===a?void 0:d}function G(a,d){function f(a){for(var l=a.i,n=a.h,b=[l,\"pln\"],p=0,q=a.a.match(m)||[],r={},e=0,t=q.length;e<
          t;++e){var z=q[e],v=r[z],g=void 0,h;if(\"string\"===typeof v)h=!1;else{var k=c[z.charAt(0)];if(k)g=z.match(k[1]),v=k[0];else{for(h=0;h<E;++h)if(k=d[h],g=z.match(k[1])){v=k[0];break}g||(v=\"pln\")}!(h=5<=v.length&&\"lang-\"===v.substring(0,5))||g&&\"string\"===typeof g[1]||(h=!1,v=\"src\");h||(r[z]=v)}k=p;p+=z.length;if(h){h=g[1];var A=z.indexOf(h),C=A+h.length;g[2]&&(C=z.length-g[2].length,A=C-h.length);v=v.substring(5);J(n,l+k,z.substring(0,A),f,b);J(n,l+k+A,h,K(v,h),b);J(n,l+k+C,z.substring(C),f,b)}else b.push(l+
          k,v)}a.g=b}var c={},m;(function(){for(var f=a.concat(d),l=[],n={},b=0,p=f.length;b<p;++b){var w=f[b],r=w[3];if(r)for(var e=r.length;0<=--e;)c[r.charAt(e)]=w;w=w[1];r=\"\"+w;n.hasOwnProperty(r)||(l.push(w),n[r]=null)}l.push(/[\\0-\\uffff]/);m=T(l)})();var E=d.length;return f}function x(a){var d=[],f=[];a.tripleQuotedStrings?d.push([\"str\",/^(?:\\\'\\\'\\\'(?:[^\\\'\\\\]|\\\\[\\s\\S]|\\\'{1,2}(?=[^\\\']))*(?:\\\'\\\'\\\'|$)|\\\"\\\"\\\"(?:[^\\\"\\\\]|\\\\[\\s\\S]|\\\"{1,2}(?=[^\\\"]))*(?:\\\"\\\"\\\"|$)|\\\'(?:[^\\\\\\\']|\\\\[\\s\\S])*(?:\\\'|$)|\\\"(?:[^\\\\\\\"]|\\\\[\\s\\S])*(?:\\\"|$))/,
          null,\"\'\\\"\"]):a.multiLineStrings?d.push([\"str\",/^(?:\\\'(?:[^\\\\\\\']|\\\\[\\s\\S])*(?:\\\'|$)|\\\"(?:[^\\\\\\\"]|\\\\[\\s\\S])*(?:\\\"|$)|\\`(?:[^\\\\\\`]|\\\\[\\s\\S])*(?:\\`|$))/,null,\"\'\\\"`\"]):d.push([\"str\",/^(?:\\\'(?:[^\\\\\\\'\\r\\n]|\\\\.)*(?:\\\'|$)|\\\"(?:[^\\\\\\\"\\r\\n]|\\\\.)*(?:\\\"|$))/,null,\"\\\"\'\"]);a.verbatimStrings&&f.push([\"str\",/^@\\\"(?:[^\\\"]|\\\"\\\")*(?:\\\"|$)/,null]);var c=a.hashComments;c&&(a.cStyleComments?(1<c?d.push([\"com\",/^#(?:##(?:[^#]|#(?!##))*(?:###|$)|.*)/,null,\"#\"]):d.push([\"com\",/^#(?:(?:define|e(?:l|nd)if|else|error|ifn?def|include|line|pragma|undef|warning)\\b|[^\\r\\n]*)/,
          null,\"#\"]),f.push([\"str\",/^<(?:(?:(?:\\.\\.\\/)*|\\/?)(?:[\\w-]+(?:\\/[\\w-]+)+)?[\\w-]+\\.h(?:h|pp|\\+\\+)?|[a-z]\\w*)>/,null])):d.push([\"com\",/^#[^\\r\\n]*/,null,\"#\"]));a.cStyleComments&&(f.push([\"com\",/^\\/\\/[^\\r\\n]*/,null]),f.push([\"com\",/^\\/\\*[\\s\\S]*?(?:\\*\\/|$)/,null]));if(c=a.regexLiterals){var m=(c=1<c?\"\":\"\\n\\r\")?\".\":\"[\\\\S\\\\s]\";f.push([\"lang-regex\",RegExp(\"^(?:^^\\\\.?|[+-]|[!=]=?=?|\\\\#|%=?|&&?=?|\\\\(|\\\\*=?|[+\\\\-]=|->|\\\\/=?|::?|<<?=?|>>?>?=?|,|;|\\\\?|@|\\\\[|~|{|\\\\^\\\\^?=?|\\\\|\\\\|?=?|break|case|continue|delete|do|else|finally|instanceof|return|throw|try|typeof)\\\\s*(\"+
          (\"/(?=[^/*\"+c+\"])(?:[^/\\\\x5B\\\\x5C\"+c+\"]|\\\\x5C\"+m+\"|\\\\x5B(?:[^\\\\x5C\\\\x5D\"+c+\"]|\\\\x5C\"+m+\")*(?:\\\\x5D|$))+/\")+\")\")])}(c=a.types)&&f.push([\"typ\",c]);c=(\"\"+a.keywords).replace(/^ | $/g,\"\");c.length&&f.push([\"kwd\",new RegExp(\"^(?:\"+c.replace(/[\\s,]+/g,\"|\")+\")\\\\b\"),null]);d.push([\"pln\",/^\\s+/,null,\" \\r\\n\\t\\u00a0\"]);c=\"^.[^\\\\s\\\\w.$@\'\\\"`/\\\\\\\\]*\";a.regexLiterals&&(c+=\"(?!s*/)\");f.push([\"lit\",/^@[a-z_$][a-z_$@0-9]*/i,null],[\"typ\",/^(?:[@_]?[A-Z]+[a-z][A-Za-z_$@0-9]*|\\w+_t\\b)/,null],[\"pln\",/^[a-z_$][a-z_$@0-9]*/i,
          null],[\"lit\",/^(?:0x[a-f0-9]+|(?:\\d(?:_\\d+)*\\d*(?:\\.\\d*)?|\\.\\d\\+)(?:e[+\\-]?\\d+)?)[a-z]*/i,null,\"0123456789\"],[\"pln\",/^\\\\[\\s\\S]?/,null],[\"pun\",new RegExp(c),null]);return G(d,f)}function L(a,d,f){function c(a){var b=a.nodeType;if(1==b&&!t.test(a.className))if(\"br\"===a.nodeName.toLowerCase())m(a),a.parentNode&&a.parentNode.removeChild(a);else for(a=a.firstChild;a;a=a.nextSibling)c(a);else if((3==b||4==b)&&f){var e=a.nodeValue,d=e.match(q);d&&(b=e.substring(0,d.index),a.nodeValue=b,(e=e.substring(d.index+
          d[0].length))&&a.parentNode.insertBefore(l.createTextNode(e),a.nextSibling),m(a),b||a.parentNode.removeChild(a))}}function m(a){function c(a,b){var e=b?a.cloneNode(!1):a,k=a.parentNode;if(k){var k=c(k,1),d=a.nextSibling;k.appendChild(e);for(var f=d;f;f=d)d=f.nextSibling,k.appendChild(f)}return e}for(;!a.nextSibling;)if(a=a.parentNode,!a)return;a=c(a.nextSibling,0);for(var e;(e=a.parentNode)&&1===e.nodeType;)a=e;b.push(a)}for(var t=/(?:^|\\s)nocode(?:\\s|$)/,q=/\\r\\n?|\\n/,l=a.ownerDocument,n=l.createElement(\"li\");a.firstChild;)n.appendChild(a.firstChild);
          for(var b=[n],p=0;p<b.length;++p)c(b[p]);d===(d|0)&&b[0].setAttribute(\"value\",d);var w=l.createElement(\"ol\");w.className=\"linenums\";d=Math.max(0,d-1|0)||0;for(var p=0,r=b.length;p<r;++p)n=b[p],n.className=\"L\"+(p+d)%10,n.firstChild||n.appendChild(l.createTextNode(\"\\u00a0\")),w.appendChild(n);a.appendChild(w)}function t(a,d){for(var f=d.length;0<=--f;){var c=d[f];I.hasOwnProperty(c)?D.console&&console.warn(\"cannot override language handler %s\",c):I[c]=a}}function K(a,d){a&&I.hasOwnProperty(a)||(a=/^\\s*</.test(d)?
          \"default-markup\":\"default-code\");return I[a]}function M(a){var d=a.j;try{var f=U(a.h,a.l),c=f.a;a.a=c;a.c=f.c;a.i=0;K(d,c)(a);var m=/\\bMSIE\\s(\\d+)/.exec(navigator.kidstubeYoutubeSearchUserAgent),m=m&&8>=+m[1],d=/\\n/g,t=a.a,q=t.length,f=0,l=a.c,n=l.length,c=0,b=a.g,p=b.length,w=0;b[p]=q;var r,e;for(e=r=0;e<p;)b[e]!==b[e+2]?(b[r++]=b[e++],b[r++]=b[e++]):e+=2;p=r;for(e=r=0;e<p;){for(var x=b[e],z=b[e+1],v=e+2;v+2<=p&&b[v+1]===z;)v+=2;b[r++]=x;b[r++]=z;e=v}b.length=r;var g=a.h;a=\"\";g&&(a=g.style.display,g.style.display=\"none\");
          try{for(;c<n;){var h=l[c+2]||q,k=b[w+2]||q,v=Math.min(h,k),A=l[c+1],C;if(1!==A.nodeType&&(C=t.substring(f,v))){m&&(C=C.replace(d,\"\\r\"));A.nodeValue=C;var N=A.ownerDocument,u=N.createElement(\"span\");u.className=b[w+1];var B=A.parentNode;B.replaceChild(u,A);u.appendChild(A);f<h&&(l[c+1]=A=N.createTextNode(t.substring(v,h)),B.insertBefore(A,u.nextSibling))}f=v;f>=h&&(c+=2);f>=k&&(w+=2)}}finally{g&&(g.style.display=a)}}catch(y){D.console&&console.log(y&&y.stack||y)}}var D=\"undefined\"!==typeof window?
          window:{},B=[\"break,continue,do,else,for,if,return,while\"],F=[[B,\"auto,case,char,const,default,double,enum,extern,float,goto,inline,int,long,register,restrict,short,signed,sizeof,static,struct,switch,typedef,union,unsigned,void,volatile\"],\"catch,class,delete,false,import,new,operator,private,protected,public,this,throw,true,try,typeof\"],H=[F,\"alignas,alignof,align_union,asm,axiom,bool,concept,concept_map,const_cast,constexpr,decltype,delegate,dynamic_cast,explicit,export,friend,generic,late_check,mutable,namespace,noexcept,noreturn,nullptr,property,reinterpret_cast,static_assert,static_cast,template,typeid,typename,using,virtual,where\"],
          O=[F,\"abstract,assert,boolean,byte,extends,finally,final,implements,import,instanceof,interface,null,native,package,strictfp,super,synchronized,throws,transient\"],P=[F,\"abstract,add,alias,as,ascending,async,await,base,bool,by,byte,checked,decimal,delegate,descending,dynamic,event,finally,fixed,foreach,from,get,global,group,implicit,in,interface,internal,into,is,join,let,lock,null,object,out,override,orderby,params,partial,readonly,ref,remove,sbyte,sealed,select,set,stackalloc,string,select,uint,ulong,unchecked,unsafe,ushort,value,var,virtual,where,yield\"],
          F=[F,\"abstract,async,await,constructor,debugger,enum,eval,export,from,function,get,import,implements,instanceof,interface,let,null,of,set,undefined,var,with,yield,Infinity,NaN\"],Q=[B,\"and,as,assert,class,def,del,elif,except,exec,finally,from,global,import,in,is,lambda,nonlocal,not,or,pass,print,raise,try,with,yield,False,True,None\"],R=[B,\"alias,and,begin,case,class,def,defined,elsif,end,ensure,false,in,module,next,nil,not,or,redo,rescue,retry,self,super,then,true,undef,unless,until,when,yield,BEGIN,END\"],
          B=[B,\"case,done,elif,esac,eval,fi,function,in,local,set,then,until\"],S=/^(DIR|FILE|array|vector|(de|priority_)?queue|(forward_)?list|stack|(const_)?(reverse_)?iterator|(unordered_)?(multi)?(set|map)|bitset|u?(int|float)\\d*)\\b/,W=/\\S/,X=x({keywords:[H,P,O,F,\"caller,delete,die,do,dump,elsif,eval,exit,foreach,for,goto,if,import,last,local,my,next,no,our,print,package,redo,require,sub,undef,unless,until,use,wantarray,while,BEGIN,END\",Q,R,B],hashComments:!0,cStyleComments:!0,multiLineStrings:!0,regexLiterals:!0}),
          I={};t(X,[\"default-code\"]);t(G([],[[\"pln\",/^[^<?]+/],[\"dec\",/^<!\\w[^>]*(?:>|$)/],[\"com\",/^<\\!--[\\s\\S]*?(?:-\\->|$)/],[\"lang-\",/^<\\?([\\s\\S]+?)(?:\\?>|$)/],[\"lang-\",/^<%([\\s\\S]+?)(?:%>|$)/],[\"pun\",/^(?:<[%?]|[%?]>)/],[\"lang-\",/^<xmp\\b[^>]*>([\\s\\S]+?)<\\/xmp\\b[^>]*>/i],[\"lang-js\",/^<script\\b[^>]*>([\\s\\S]*?)(<\\/script\\b[^>]*>)/i],[\"lang-css\",/^<style\\b[^>]*>([\\s\\S]*?)(<\\/style\\b[^>]*>)/i],[\"lang-in.tag\",/^(<\\/?[a-z][^<>]*>)/i]]),\"default-markup htm html mxml xhtml xml xsl\".split(\" \"));t(G([[\"pln\",/^[\\s]+/,
          null,\" \\t\\r\\n\"],[\"atv\",/^(?:\\\"[^\\\"]*\\\"?|\\\'[^\\\']*\\\'?)/,null,\"\\\"\'\"]],[[\"tag\",/^^<\\/?[a-z](?:[\\w.:-]*\\w)?|\\/?>$/i],[\"atn\",/^(?!style[\\s=]|on)[a-z](?:[\\w:-]*\\w)?/i],[\"lang-uq.val\",/^=\\s*([^>\\\'\\\"\\s]*(?:[^>\\\'\\\"\\s\\/]|\\/(?=\\s)))/],[\"pun\",/^[=<>\\/]+/],[\"lang-js\",/^on\\w+\\s*=\\s*\\\"([^\\\"]+)\\\"/i],[\"lang-js\",/^on\\w+\\s*=\\s*\\\'([^\\\']+)\\\'/i],[\"lang-js\",/^on\\w+\\s*=\\s*([^\\\"\\\'>\\s]+)/i],[\"lang-css\",/^style\\s*=\\s*\\\"([^\\\"]+)\\\"/i],[\"lang-css\",/^style\\s*=\\s*\\\'([^\\\']+)\\\'/i],[\"lang-css\",/^style\\s*=\\s*([^\\\"\\\'>\\s]+)/i]]),[\"in.tag\"]);
          t(G([],[[\"atv\",/^[\\s\\S]+/]]),[\"uq.val\"]);t(x({keywords:H,hashComments:!0,cStyleComments:!0,types:S}),\"c cc cpp cxx cyc m\".split(\" \"));t(x({keywords:\"null,true,false\"}),[\"json\"]);t(x({keywords:P,hashComments:!0,cStyleComments:!0,verbatimStrings:!0,types:S}),[\"cs\"]);t(x({keywords:O,cStyleComments:!0}),[\"java\"]);t(x({keywords:B,hashComments:!0,multiLineStrings:!0}),[\"bash\",\"bsh\",\"csh\",\"sh\"]);t(x({keywords:Q,hashComments:!0,multiLineStrings:!0,tripleQuotedStrings:!0}),[\"cv\",\"py\",\"python\"]);t(x({keywords:\"caller,delete,die,do,dump,elsif,eval,exit,foreach,for,goto,if,import,last,local,my,next,no,our,print,package,redo,require,sub,undef,unless,until,use,wantarray,while,BEGIN,END\",
          hashComments:!0,multiLineStrings:!0,regexLiterals:2}),[\"perl\",\"pl\",\"pm\"]);t(x({keywords:R,hashComments:!0,multiLineStrings:!0,regexLiterals:!0}),[\"rb\",\"ruby\"]);t(x({keywords:F,cStyleComments:!0,regexLiterals:!0}),[\"javascript\",\"js\",\"ts\",\"typescript\"]);t(x({keywords:\"all,and,by,catch,class,else,extends,false,finally,for,if,in,is,isnt,loop,new,no,not,null,of,off,on,or,return,super,then,throw,true,try,unless,until,when,while,yes\",hashComments:3,cStyleComments:!0,multilineStrings:!0,tripleQuotedStrings:!0,
          regexLiterals:!0}),[\"coffee\"]);t(G([],[[\"str\",/^[\\s\\S]+/]]),[\"regex\"]);var Y=D.PR={createSimpleLexer:G,registerLangHandler:t,sourceDecorator:x,PR_ATTRIB_NAME:\"atn\",PR_ATTRIB_VALUE:\"atv\",PR_COMMENT:\"com\",PR_DECLARATION:\"dec\",PR_KEYWORD:\"kwd\",PR_LITERAL:\"lit\",PR_NOCODE:\"nocode\",PR_PLAIN:\"pln\",PR_PUNCTUATION:\"pun\",PR_SOURCE:\"src\",PR_STRING:\"str\",PR_TAG:\"tag\",PR_TYPE:\"typ\",prettyPrintOne:D.prettyPrintOne=function(a,d,f){f=f||!1;d=d||null;var c=document.createElement(\"div\");c.innerHTML=\"<pre>\"+a+\"</pre>\";
          c=c.firstChild;f&&L(c,f,!0);M({j:d,m:f,h:c,l:1,a:null,i:null,c:null,g:null});return c.innerHTML},prettyPrint:D.prettyPrint=function(a,d){function f(){for(var c=D.PR_SHOULD_USE_CONTINUATION?b.now()+250:Infinity;p<x.length&&b.now()<c;p++){for(var d=x[p],l=g,n=d;n=n.previousSibling;){var m=n.nodeType,u=(7===m||8===m)&&n.nodeValue;if(u?!/^\\??prettify\\b/.test(u):3!==m||/\\S/.test(n.nodeValue))break;if(u){l={};u.replace(/\\b(\\w+)=([\\w:.%+-]+)/g,function(a,b,c){l[b]=c});break}}n=d.className;if((l!==g||r.test(n))&&
          !e.test(n)){m=!1;for(u=d.parentNode;u;u=u.parentNode)if(v.test(u.tagName)&&u.className&&r.test(u.className)){m=!0;break}if(!m){d.className+=\" prettyprinted\";m=l.lang;if(!m){var m=n.match(w),q;!m&&(q=V(d))&&z.test(q.tagName)&&(m=q.className.match(w));m&&(m=m[1])}if(B.test(d.tagName))u=1;else var u=d.currentStyle,y=t.defaultView,u=(u=u?u.whiteSpace:y&&y.getComputedStyle?y.getComputedStyle(d,null).getPropertyValue(\"white-space\"):0)&&\"pre\"===u.substring(0,3);y=l.linenums;(y=\"true\"===y||+y)||(y=(y=n.match(/\\blinenums\\b(?::(\\d+))?/))?
          y[1]&&y[1].length?+y[1]:!0:!1);y&&L(d,y,u);M({j:m,h:d,m:y,l:u,a:null,i:null,c:null,g:null})}}}p<x.length?D.setTimeout(f,250):\"function\"===typeof a&&a()}for(var c=d||document.body,t=c.ownerDocument||document,c=[c.getElementsByTagName(\"pre\"),c.getElementsByTagName(\"code\"),c.getElementsByTagName(\"xmp\")],x=[],q=0;q<c.length;++q)for(var l=0,n=c[q].length;l<n;++l)x.push(c[q][l]);var c=null,b=Date;b.now||(b={now:function(){return+new Date}});var p=0,w=/\\blang(?:uage)?-([\\w.]+)(?!\\S)/,r=/\\bprettyprint\\b/,
          e=/\\bprettyprinted\\b/,B=/pre|xmp/i,z=/^code$/i,v=/^(?:pre|code|xmp)$/i,g={};f()}},H=D.define;\"function\"===typeof H&&H.amd&&H(\"google-code-prettify\",[],function(){return Y})})();}()
        ";
    }

    private static function getErrorCSS(): string
    {
        return '
        body{
            background-color: #333;
            margin: 0;
            padding: 0;
          }
          .cont{
            position: absolute;
            top:0;
            left:0;
            height: 100%;
            width: 100%;
            overflow: hidden;
          }
          .header-cont{
            position: absolute;
            background-color: #333;
            left: 0;
            top: 0;
            right: 0;
            height: 200px;
            overflow: hidden;
          }
          .header{
            font-family: Tahoma;
            padding: 10px 10px;
            color:#fff;
            font-size: 30px;
            height: 40px;
            line-height: 40px;
          }
          .header-msg{
            font-family: Tahoma;
            padding:  10px;
            height: 90px;
            overflow:auto;
            background-color: #5c5b5b;
          }
          .header-msg:before{
            content: "";
            position: absolute;
            bottom: 0;
            left: 10px;
            right: 10px;
            height: 10px;
            background-color: #5c5b5b;
          }
          .header-msg i{
            font-style: normal;
            color:#e88b8b;
          }
          .header-msg-in{
            color:#fff;
            font-size: 12px;
            word-break: break-word;
            line-height: 18px;
          }
          .header-file{
            font-family: Tahoma;
            padding: 8px 10px;
            color: #333;
            background-color:#fff;
            font-size: 12px;
            height: 14px;
            overflow: hidden;
            word-break: break-word;
            line-height: 14px;
          }
          .body-cont{
            position: absolute;
            background-color: #333;
            left: 0;
            top: 200px;
            right: 0;
            bottom: 0;
            overflow: auto;
          }
          pre.prettyprint { display: block; background-color: #333; padding:12px; }
          pre .nocode { background-color: none; color: #000 }
          pre .str { color: #ffa0a0 } /* string  - pink */
          pre .kwd { color: #f0e68c; font-weight: bold }
          pre .com { color: #87ceeb } /* comment - skyblue */
          pre .typ { color: #98fb98 } /* type    - lightgreen */
          pre .lit { color: #cd5c5c } /* literal - darkred */
          pre .pun { color: #fff }    /* punctuation */
          pre .pln { color: #fff }    /* plaintext */
          pre .tag { color: #f0e68c; font-weight: bold } /* html/xml tag    - lightyellow */
          pre .atn { color: #bdb76b; font-weight: bold } /* attribute name  - khaki */
          pre .atv { color: #ffa0a0 } /* attribute value - pink */
          pre .dec { color: #98fb98 } /* decimal         - lightgreen */
          ol.linenums { list-style-type: none;  margin-top: 0; margin-bottom: 0; color: #AEAEAE } /* IE indents via margin-left */
          li.L0,li.L1,li.L2,li.L3,li.L5,li.L6,li.L7,li.L8 { list-style-type: none }
          @media print {
            pre.prettyprint { background-color: none }
            pre .str, code .str { color: #060 }
            pre .kwd, code .kwd { color: #006; font-weight: bold }
            pre .com, code .com { color: #600; font-style: italic }
            pre .typ, code .typ { color: #404; font-weight: bold }
            pre .lit, code .lit { color: #044 }
            pre .pun, code .pun { color: #440 }
            pre .pln, code .pln { color: #000 }
            pre .tag, code .tag { color: #006; font-weight: bold }
            pre .atn, code .atn { color: #404 }
            pre .atv, code .atv { color: #060 }
          }
          ol.linenums > li{
            list-style-type: decimal;
          }';
    }

}