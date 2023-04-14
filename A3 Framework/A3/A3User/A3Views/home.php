<!DOCTYPE html>
<html lang="{{$lng}}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@A3('base.framework_name')</title>
        <style>
            html, body {
                color: #fff;
                font-family: Tahoma, serif;
                margin: 0;
                padding: 0;
            }
            .bg{
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                background: rgb(0 145 173);
            }
            .cont{
                position: relative;
                text-align: center;
                padding-top: 50px;
            }
            .header{
                font-size: 22px;
            }
            .body{
                font-size: 70px;
                padding: 30px;
            }
            .body span{
                position: relative;
                display: block;
                font-size: 18px;
                padding: 10px;
            }
            .documentation{
                text-decoration: none;
                color: #005464;
                font-size: 20px;
            }
            .documentation:hover{
                text-decoration: underline;
            }
            .lng-cont{
                position: relative;
                display: block;
                padding: 30px 0;
            }
            .lng-link{
                display: inline-block;
                padding: 0 10px;
                text-decoration: none;
                color: #fff;
            }
            .lng-link:hover{
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="bg"></div>
        <div class="cont">
            <div class="header">@A3('base.welcome')</div>
            <div class="body">
                @A3('base.framework_name')
                <span @if(A3::getLocal() === 'ar') dir="rtl"@endif>@A3('base.framework_desc')</span>
            </div>
            <a href="@A3Data('base.documentation_link')" class="documentation" target="_blank">@A3('base.documentation')</a>
            <div class="lng-cont">
                <a href="@A3RequestFull('home',['lng' => 'en'])" class="lng-link">@A3('base.en')</a> . <a href="@A3RequestFull('home',['lng' => 'ar'])" class="lng-link">@A3('base.ar')</a>
            </div>
        </div>
    </body>
</html>
