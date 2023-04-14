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

namespace A3App\A3View;

class A3Error404
{

    public static function showError404(): void
    {
        A3__clean__output__buffer();
        $errorPage = A3Settings('error_page');
        if (!$errorPage || !A3View::isValidView($errorPage)) {
            A3View::publishHeader(A3VH_E404);
            echo self::getErrorPage();
        } else {
            $errorView = new A3View($errorPage, []);
            $errorView->header(A3VH_E404)->renderView();
        }
        A3__exit__and__close();
    }

    private static function getErrorPage(): string
    {
        return '
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>Not Found</title>
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
                    <div class="body">Error 404: Not Found</div>
                </div>
            </body>
        </html>
        ';
    }

}