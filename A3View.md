# A3View

Place your views in **A3/A3User/A3Views**
You can use [A3Template](https://github.com/AboAlimk/A3/blob/master/A3Template.md "A3Template") in your views.

------------
### usage
$uri is 'path.to.view'

```php
A3View($uri,$data = []);
```
or
```php
A3View($uri)->with(string $key, $value);
```
### header

```php
A3View(...)->header(string $header);
```
you can use own header or choose from [A3Headers](https://github.com/AboAlimk/A3/blob/master/A3Headers.md "A3Headers")