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

return [
    [
        'dir' => '/A3User/A3Data/',
        'function' => function ($name, $file) {
            A3App\A3Data::loadDataFile($name, $file);
        }
    ],
    [
        'files' => [
            '/A3App/A3Date/A3DateData.php',
        ],
        'function' => function ($file) {
            A3App\A3Date\A3Date::loadDataFile($file);
        }
    ],
    [
        'files' => [
            'base' => '/A3App/A3Settings/A3SettingsData.php',
            'user' => '/A3User/A3Settings.php',
        ],
        'function' => function ($name, $file) {
            A3App\A3Settings\A3Settings::loadSettingsFile($name, $file);
        }
    ],
    [
        'dir' => '/A3User/A3RequestMiddleProcess/',
    ],
    [
        'dir' => '/A3User/A3RequestProcess/',
    ],
];