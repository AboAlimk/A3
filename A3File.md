# A3File

A3File files are located in **A3/A3User/A3Storage/A3File**

------------
### write
$name is path to file

```php
A3File::write($name,$txt = '');
```
### append
```php
A3File::append($name,$txt = '');
```
### read
```php
A3File::read($name);
```
### getFullUri
Return full uri of file (if you want to include in your code)

```php
A3File::getFullUri($name);
```