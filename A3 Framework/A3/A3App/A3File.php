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

class A3File
{

    private const A3FILES_DIR = '/../A3User/A3Storage/A3File';

    public static function write($name, $txt = ''): bool
    {
        $link = self::getA3FilesDir($name);
        if (!self::isValidName(basename($link))) {
            self::__error('a3files_name_error', 0, __FUNCTION__);
        }
        $dir = dirname($link);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return file_put_contents($link, $txt) !== false;
    }

    public static function append($name, $txt = ''): bool
    {
        $link = self::getA3FilesDir($name);
        if (!is_file($link)) {
            self::__error('a3files_read_error', $name, __FUNCTION__);
        }
        return file_put_contents($link, $txt, FILE_APPEND | LOCK_EX) !== false;
    }

    public static function read($name): string
    {
        $link = self::getA3FilesDir($name);
        if (!self::exists($name)) {
            self::__error('a3files_read_error', $name, __FUNCTION__);
        }
        $file = fopen($link, "r");
        $fileSize = filesize($link);
        if ($fileSize === 0) {
            return '';
        }
        $out = fread($file, $fileSize);
        fclose($file);
        return $out;
    }

    public static function exists($name): bool
    {
        $link = self::getA3FilesDir($name);
        if (!is_file($link)) {
            return false;
        }
        return true;
    }

    public static function delete($dir, $name = false): bool
    {
        $link = self::getA3FilesDir($dir);
        if ($name === false) {
            if (!is_file($link)) {
                return false;
            }
            return unlink($link);
        }
        if (!is_dir($link)) {
            return false;
        }
        $link .= '/' . $name;
        if (!is_file($link)) {
            return false;
        }
        return unlink($link);
    }

    public static function getFullUri($name): string
    {
        $link = self::getA3FilesDir($name);
        if (!is_file($link)) {
            return '';
        }
        return $link;
    }

    public static function getFullDirUri($name): string
    {
        $link = self::getA3FilesDir($name);
        if (!is_dir($link)) {
            return '';
        }
        return $link;
    }

    public static function DirExists($name): bool
    {
        $link = self::getA3FilesDir($name);
        if (!is_dir($link)) {
            return false;
        }
        return true;
    }

    public static function createDir($name): string
    {
        $link = self::getA3FilesDir($name);
        if (!is_dir($link)) {
            mkdir($link);
        }
        return $link;
    }

    public static function isUploadedFile($file): bool
    {
        if (!$file || !is_array($file) || !file_exists($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false;
        }
        return true;
    }

    public static function getUploadedFileExt($file): string
    {
        return pathinfo($file['name'], PATHINFO_EXTENSION);
    }

    public static function getUploadedFileType($file): string
    {
        return $file['type'];
    }

    public static function getUploadedFileSize($file): int
    {
        return $file['size'];
    }

    public static function getUploadedFileTmpName($file): string
    {
        return $file['tmp_name'];
    }

    public static function getUploadedFileName($file): string
    {
        return $file['name'];
    }

    public static function uploadFile($dir, $file, $nameWithExt): bool
    {
        if (!self::isUploadedFile($file)) {
            return false;
        }
        $dir = self::createDir($dir);
        return move_uploaded_file($file['tmp_name'], $dir . '/' . $nameWithExt);
    }

    private static function getA3FilesDir($link): string
    {
        $baseDir = __DIR__ . self::A3FILES_DIR;
        if (!is_dir($baseDir)) {
            mkdir($baseDir);
        }
        $link = str_starts_with($link, '/') ? substr($link, 1) : $link;
        $link = str_ends_with($link, '/') ? substr($link, 0, strlen($link) - 1) : $link;
        return $baseDir . '/' . $link;
    }

    private static function isValidName($name): bool
    {
        if (str_contains($name, "\\") || preg_match('(\\s|/|\\\|:|#|\*|\?|\'|\"|<|>|\|)', $name)) {
            return false;
        }
        return true;
    }

    private static function __error($text, $replace, $a3Function): void
    {
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => [$replace],
            'a3Class' => 'A3File',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace(),
        ]);
    }

}