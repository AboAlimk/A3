# A3File

A3File files are located in **A3/A3User/A3Storage/A3File**

------------

**Table of Contents**

- [A3File::write](#write)
- [A3File::append](#append)
- [A3File::read](#read)
- [A3File::exists](#exists)
- [A3File::delete](#delete)
- [A3File::getFullUri](#getFullUri)
- [A3File::getFullDirUri](#getFullDirUri)
- [A3File::DirExists](#DirExists)
- [A3File::createDir](#createDir)
- [A3File::isUploadedFile](#isUploadedFile)
- [A3File::getUploadedFileExt](#getUploadedFileExt)
- [A3File::getUploadedFileType](#getUploadedFileType)
- [A3File::getUploadedFileSize](#getUploadedFileSize)
- [A3File::getUploadedFileTmpName](#getUploadedFileTmpName)
- [A3File::getUploadedFileName](#getUploadedFileName)
- [A3File::uploadFile](#uploadFile)

------------
### write
###### **Bool - $name is path to file**
```php
A3File::write($name,$txt = '');
```
### append
###### **Bool - $name is path to file**
```php
A3File::append($name,$txt = '');
```
### read
###### **String - $name is path to file**
```php
A3File::read($name);
```
### exists
###### **Bool - $name is path to file**
```php
A3File::exists($name);
```
### delete
###### **Bool - $name is path to file**
```php
A3File::delete($dir, $name = false);
```
### getFullUri
###### **String - $name is path to file**
Return full uri of file (if you want to include in your code)
```php
A3File::getFullUri($name);
```
### getFullDirUri
###### **String - $name is path to dir**
```php
A3File::getFullDirUri($name);
```
### DirExists
###### **Bool - $name is path to dir**
```php
A3File::DirExists($name);
```
### createDir
###### **String - $name is path to dir**
```php
A3File::createDir($name);
```
### isUploadedFile
###### **Bool**
```php
A3File::isUploadedFile($file);
```
### getUploadedFileExt
###### **String**
```php
A3File::getUploadedFileExt($file);
```
### getUploadedFileType
###### **String**
```php
A3File::getUploadedFileType($file);		//string
```
### getUploadedFileSize
###### **Int**
```php
A3File::getUploadedFileSize($file);
```
### getUploadedFileTmpName
###### **String**
```php
A3File::getUploadedFileTmpName($file);
```
### getUploadedFileName
###### **String**
```php
A3File::getUploadedFileName($file);
```
### uploadFile
###### **Bool**
```php
A3File::uploadFile($dir, $file, $nameWithExt);
```