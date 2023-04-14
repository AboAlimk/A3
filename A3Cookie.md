# A3Cookie

### add
```php
A3Cookie::add($name, $value = '', $expires = null, $path = '/', $domain = '',  $secure = false, $httponly = false);
```
### get
Return item by name or all if leave blank

```php
A3Cookie::get($name = null);
```
### delete
Delete item by name

```php
A3Cookie::delete($name);
```