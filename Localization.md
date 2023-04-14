# Localization

Localization directories are located in **A3/A3User/A3Languages**

To add english language add folder 'en' and place your english language files inside.

------------

### setLocal
Set local of app
If local is not valid will use 'app_local' in **A3/A3User/A3Settings.php**
```php
A3::setLocal();
```
### getLocal
###### **String - Return app local**
```php
A3::getLocal()
```