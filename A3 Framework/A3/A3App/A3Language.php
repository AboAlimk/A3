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

class A3Language
{

    private static array $languages = [];

    public static function getBrowserLanguage(): string
    {
        $lng = A3GetPathValue($_SERVER, 'HTTP_ACCEPT_LANGUAGE', '');
        $lng = A3String::length($lng) >= 2 ? A3String::limit($lng, 2) : '';
        return A3String::lower($lng);
    }

    public static function fetchLanguages(): void
    {
        $langDirs = glob(A3_ROOT_DIR . '/A3User/A3Languages/*');
        foreach ($langDirs as $langDir) {
            if (is_dir($langDir)) {
                $langCode = A3String::lower(basename($langDir));
                self::$languages[$langCode] = [];
                $languageFiles = glob($langDir . '/*.php');
                foreach ($languageFiles as $file) {
                    if ($file && is_file($file) && file_exists($file)) {
                        $name = pathinfo($file, PATHINFO_FILENAME);
                        self::$languages[$langCode][$name] = require $file;
                    }
                }
            }
        }
    }

    public static function getLanguagesCodes(): array
    {
        return array_keys(self::$languages);
    }

    public static function getLanguageTextLocal($key, $replace): string
    {
        return self::getLanguageText(A3::getLocal(), $key, $replace);
    }

    public static function getLanguageTextCustom($local, $key, $replace): string
    {
        return self::getLanguageText($local, $key, $replace);
    }

    public static function getLanguageText($local, $key, $replace): string
    {
        $local = A3String::lower($local);
        $path = A3String::getPath($key);
        $languageObject = A3GetPathValue(self::$languages, [$local], []);
        $text = A3GetPathValue($languageObject, $path, null);
        if (is_null($text)) {
            $local = A3String::lower(A3Settings('app_local'));
            $languageObject = A3GetPathValue(self::$languages, [$local], []);
            $text = A3GetPathValue($languageObject, $path, end($path));
        }
        if (!A3String::is($text)) {
            return $text;
        }
        return A3String::replaceArray($text, $replace);
    }

    public function __call($method, $parameters)
    {
        self::__error([$method], __FUNCTION__);
    }

    public static function __callStatic($method, $parameters)
    {
        self::__error([$method], __FUNCTION__);
    }

    private static function __error($replace, $a3Function): void
    {
        A3Error::errorTrigger([
            'text' => 'undefined_method',
            'replace' => $replace,
            'a3Class' => 'A3Language',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace(),
        ]);
    }

}