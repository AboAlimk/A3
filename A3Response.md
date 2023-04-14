# A3Response

### usage
```php
A3Response($content = '', $headers = []);
```
### content
Set content of A3Response

```php
A3Response(...)->content($content);
```
### addContent
Add content to A3Response

```php
A3Response(...)->addContent($content);
```
### header
You can use own header or choose from [A3Headers](https://github.com/AboAlimk/A3/blob/master/A3Headers.md "A3Headers")

```php
A3Response(...)->header(sring $header);
```