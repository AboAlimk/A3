# A3Settings

Reads settings from file **A3/A3User/A3Settings.php**

You can add own setings.

------------
### usage
```php
A3Settings($key);
```
will return value of key
### Default settings
```php
[
	'debug' => true,
	'cookie_time' => (86400 * 30),
	'app_local' => 'en',		    //default language
	'time_zone' => 'UTC', 		//default language
	'error_page' => '',				//view file for error 404
	'force_ssl' => false,			//force https
	'www_as_sub' => false,		//use www as subdomain
	'mysqli' => [
		'host' => 'localhost',
		'port' => '3306',
		'database' => '',
		'username' => 'root',
		'password' => '',
		'charset' => 'utf8mb4',
		'collation' => 'utf8mb4_unicode_ci',
	],
]
```