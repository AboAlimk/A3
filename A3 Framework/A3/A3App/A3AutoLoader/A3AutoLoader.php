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

class A3AutoLoader
{

    private const CONSTANTS_FILE_URI = '/A3App/A3Constants.php';
    private const HELPERS_FILE_URI = '/A3App/A3Helpers.php';
    private const REQUEST_FILE_URI = '/A3User/A3Request.php';
    private static array $includedClasses = [];
    private static array $classes = [];

    public static function load(): void
    {
        ob_start();
        self::$classes = require __DIR__ . '/A3AutoLoaderClasses.php';
        spl_autoload_register([__CLASS__, 'loadClasses']);
        self::createAliases();
        self::loadConstantsFile();
        self::loadHelpersFile();
        self::loadErrorFile();
        self::loadDirs();
        self::loadLanguages();
        self::checkSecure();
        self::setDefaultTimeZone();
        self::loadA3RequestsFile();
        self::processData();
    }

    private static function loadClasses($name): void
    {
        $classLoadStatus = self::checkClassLoadStatus($name);
        if ($classLoadStatus) {
            if (array_key_exists($name, self::$classes)) {
                self::loadClassFile(A3GetPathValue(self::$classes, [$name, 'uri']));
            } else {
                self::loadClassFile(self::parseNameSpace($name));
            }
        }
    }

    private static function checkClassLoadStatus($class): bool
    {
        if (!array_key_exists($class, self::$includedClasses)) {
            self::$includedClasses[$class] = true;
            return true;
        }
        return false;
    }

    private static function loadClassFile($file): void
    {
        $file = self::checkFile($file);
        if ($file) {
            require $file;
        }
    }

    private static function checkFile($file): bool|string
    {
        $file = A3_ROOT_DIR . $file;
        if ($file && is_file($file) && file_exists($file)) {
            return $file;
        }
        return false;
    }

    private static function parseNameSpace($nameSpace): string
    {
        return '/' . str_replace('\\', '/', $nameSpace) . '.php';
    }

    private static function checkSecure(): void
    {
        if (A3Settings('force_ssl')) {
            A3App\A3Redirect::redirectToSecure();
        }
    }

    private static function createAliases(): void
    {
        foreach (self::$classes as $key => $value) {
            $class = $value['class'];
            if ($key && $class) {
                if (is_string($key)) {
                    class_alias($class, $key);
                }
            }
        }
    }

    private static function loadConstantsFile(): void
    {
        $file = self::checkFile(self::CONSTANTS_FILE_URI);
        if ($file) {
            require $file;
        }
    }

    private static function loadHelpersFile(): void
    {
        $file = self::checkFile(self::HELPERS_FILE_URI);
        if ($file) {
            require $file;
        }
    }

    private static function loadErrorFile(): void
    {
        A3Error::initError();
    }

    private static function loadDirs(): void
    {
        $dirs = require __DIR__ . '/A3AutoLoaderDir.php';
        foreach ($dirs as $dir) {
            $dirLoc = A3GetPathValue($dir, 'dir');
            $dirFiles = A3GetPathValue($dir, 'files');
            $dirFn = A3GetPathValue($dir, 'function');
            $files = false;
            if ($dirLoc) {
                $files = self::processDirFiles($dirLoc, glob(A3_ROOT_DIR . $dirLoc . '*.php'));
            }
            if ($dirFiles) {
                $files = $dirFiles;
            }
            if ($files) {
                foreach ($files as $name => $file) {
                    $file = self::checkFile($file);
                    if ($file) {
                        if ($dirFn && is_callable($dirFn)) {
                            if (is_string($name)) {
                                $dirFn($name, $file);
                            } else {
                                $dirFn($file);
                            }
                        } else {
                            require $file;
                        }
                    }
                }
            }
        }
    }

    private static function processDirFiles($dir, $files): array
    {
        $array = [];
        foreach ($files as $file) {
            if (is_file($file)) {
                $name = pathinfo($file, PATHINFO_FILENAME);
                $array[$name] = $dir . $name . '.php';
            }
        }
        return $array;
    }

    private static function loadLanguages(): void
    {
        A3App\A3Language::fetchLanguages();
    }

    private static function setDefaultTimeZone(): void
    {
        date_default_timezone_set(A3Settings('time_zone'));
    }

    private static function loadA3RequestsFile(): void
    {
        $file = self::checkFile(self::REQUEST_FILE_URI);
        if ($file) {
            require $file;
        }
    }

    private static function processData(): void
    {
        A3App\A3Processor::process();
    }

}
A3AutoLoader::load();