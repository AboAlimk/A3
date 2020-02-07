<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@A3('base.framework_name')</title>
        <style>
            html, body {
                color: #fff;
                font-family: Tahoma;
                margin: 0;
                padding: 0;
                text-shadow: 0px 0px 20px #015243;
            }
            .bg{
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                background: rgb(3,171,203);
                background: linear-gradient(37deg, rgba(3,171,203,1) 0%, rgba(0,228,168,1) 100%);
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
            .footer{
                text-decoration: none;
                color: #005f51;
                font-size: 20px;
            }
            .footer:hover{
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
                <span@if(A3::getLocal() === 'ar') dir="rtl"@endif >@A3('base.framework_desc')</span>
            </div>
            <a href="https://github.com/AboAlimk/A3" class="footer" target="_blank">@A3('base.documentation')</a>
        </div>
    </body>
</html>
