# htaccess
htaccess file is located in **public_html/.htaccess**

------------
### Handle Authorization Header
active
```php
RewriteCond %{HTTP:Authorization} .
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
```
# Don not uncomment all
### Force www to non-www without security
```php
RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
RewriteRule ^(.*)$ http://%1/$1 [R=301,L]
```
### Force www to non-www with security
```php
RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
RewriteRule ^(.*)$ https://%1/$1 [R=301,L]
```
### Force non-www to www without security
```php
RewriteCond %{HTTP_HOST} !^www\. [NC]
RewriteRule ^ http://www.%{HTTP_HOST}%{REQUEST_URI} [R=301,L]
```
### Force non-www to www with security
```php
RewriteCond %{HTTP_HOST} !^www\. [NC]
RewriteRule ^ https://www.%{HTTP_HOST}%{REQUEST_URI} [R=301,L]
```
### Force https
```php
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```
### Force https:// and non-www
```php
RewriteCond %{HTTP_HOST} ^(www\.)(.+) [OR]
RewriteCond %{HTTPS} off
RewriteCond %{HTTP_HOST} ^(www\.)?(.+)
RewriteRule ^(.*)$ https://%2%{REQUEST_URI} [L,R=301]
```