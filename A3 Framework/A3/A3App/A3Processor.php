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

use A3App\A3Request\A3RequestData;
use A3App\A3Request\A3RequestObject;
use A3App\A3Request\A3RequestRoute;
use A3App\A3Request\A3RequestReferer;
use A3App\A3Request\A3RequestMiddleProcess;
use A3App\A3Request\A3RequestProcess;
use A3App\A3Request\A3RequestURLParse;
use A3App\A3View\A3Error404;
use A3App\A3View\A3View;

class A3Processor
{

    private static bool $started = false;
    private static bool $isError = true;

    public static function process(): void
    {
        if (!self::$started) {
            self::$started = true;
            self::processA3RequestObject();
        }
    }

    private static function processA3RequestObject(): void
    {
        $A3RequestArray = A3RequestData::getObject();
        foreach ($A3RequestArray as $A3Request) {
            if (self::$isError) {
                self::processA3Request($A3Request);
            }
        }
        if (self::$isError) {
            self::showError404();
        }
    }

    private static function processA3Request($A3Request): void
    {
        $requestMethod = array_map('strtoupper', A3GetPathValue($A3Request, 'method', []));
        $requestRoute = A3GetPathValue($A3Request, 'route', '');
        $requestProcess = A3GetPathValue($A3Request, 'process');
        $requestWhere = A3GetPathValue($A3Request, 'where');
        $requestName = A3GetPathValue($A3Request, 'name', '');
        $requestHaveName = A3GetPathValue($A3Request, 'havename');
        $requestSubDomain = A3GetPathValue($A3Request, 'subdomain', '');
        $requestSubDomainAll = A3GetPathValue($A3Request, 'subdomainall');
        $requestMiddleProcess = A3GetPathValue($A3Request, 'middleprocess');
        $requestReferer = A3GetPathValue($A3Request, 'referer');
        $requestDebugData = A3GetPathValue($A3Request, 'debugData');
        $requestObject = new A3RequestObject($requestHaveName ? $requestName : '', $requestRoute, $requestMethod);
        $checkRequestMethodMatch = in_array($requestObject->getMethod(), $requestMethod);
        $requestSubDomainMatch = $requestSubDomainAll === true || A3RequestURLParse::match($requestSubDomain, A3Settings('www_as_sub'));
        if ($checkRequestMethodMatch && $requestSubDomainMatch) {
            $requestObjectRouteItems = A3RequestRoute::match($requestRoute, $requestWhere, $requestObject->getRouteItems());
            if ($requestObjectRouteItems['result']) {
                $requestRefererMatch = A3RequestReferer::match($requestReferer, $requestObject->getReferer());
                if ($requestRefererMatch) {
                    $requestObject->setRequestRouteItems($requestObjectRouteItems['items']);
                    $requestObject = A3RequestMiddleProcess::process($requestObject, $requestMiddleProcess);
                    if (($requestObject instanceof A3RequestObject || $requestObject instanceof A3Redirect || $requestObject instanceof A3View || $requestObject instanceof A3Response)) {
                        if (($requestObject instanceof A3RequestObject && $requestObject->getError() === false) || $requestObject instanceof A3Redirect || $requestObject instanceof A3View || $requestObject instanceof A3Response) {
                            self::$isError = false;
                            if ($requestObject instanceof A3RequestObject) {
                                $requestObject = A3RequestProcess::process($requestObject, $requestProcess);
                                if ($requestObject && (is_string($requestObject) || is_numeric($requestObject) || is_array($requestObject) || $requestObject instanceof A3View || $requestObject instanceof A3Redirect || $requestObject instanceof A3Response)) {
                                    A3RequestData::setCurrentA3Request($requestName, $requestObjectRouteItems['items']);
                                    if (is_string($requestObject) || is_numeric($requestObject) || is_array($requestObject)) {
                                        print_r($requestObject);
                                    } else {
                                        self::render($requestObject);
                                    }
                                } else {
                                    self::__error('a3request_process_error', $requestDebugData);
                                }
                            } else {
                                self::render($requestObject);
                            }
                        }
                    } else {
                        self::__error('a3request_middle_process_error', $requestDebugData);
                    }
                }
            }
        }
    }

    private static function render($requestObject): void
    {
        A3__clean__output__buffer();
        ob_start();
        if ($requestObject instanceof A3Redirect) {
            A3__exit__and__close(false);
            $requestObject->redirect();
        } else if ($requestObject instanceof A3Response) {
            $requestObject->render();
        } else {
            $requestObject->renderView();
        }
        A3__exit__and__close();
    }

    private static function showError404(): void
    {
        A3Error404::showError404();
    }

    private static function __error($text, $debugData): void
    {
        A3Error::errorTrigger([
            'text' => $text,
            'a3Class' => 'A3Request',
            'a3Function' => '__callStatic',
            'debugData' => $debugData,
        ]);
    }

}