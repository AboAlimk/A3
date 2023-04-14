# A3Template

A3Template is simple, fast and powerful templating engine provided with A3 Framework.

------------

**Table of Contents**

- [if, else, if, else](#if-else-if-else)
- [switch](#switch)
- [isset](#isset)
- [empty](#empty)
- [for](#for)
- [foreach](#foreach)
- [continue](#continue)
- [break](#break)
- [php](#php)
- [upper](#upper)
- [lower](#lower)
- [A3](#A3)
- [A3L](#A3L)
- [A3Words](#A3Words)
- [A3Data](#A3Data)
- [A3Date](#A3Date)
- [A3GRS](#A3GRS)
- [A3Settings](#A3Settings)
- [A3Assets](#A3Assets)
- [A3Request](#A3Request)
- [A3RequestFull](#A3RequestFull)
- [A3nl2br](#A3nl2br)
- [A3Root](#A3Root)
- [A3Domain](#A3Domain)
- [echo](#echo)
- [include](#include)
- [extends](#extends)

------------
### if, else if, else
```php
@if(...)

@elseif(...)

@else

@endif
```
### switch
```php
@switch(...)

@case(...)

@default

@endswitch
```
### isset
```php
@isset(...)

@endisset
```
### empty
```php
@empty(...)

@endempty
```
### for
```php
@for(...)

@endfor
```
### foreach
```php
@foreach(...)

@endforeach
```
### continue
```php
@continue
```
### break
```php
@break
```
### php
```php
@php
/* php code */
@endphp
```
### upper
```php
@upper($string)
```
### lower
```php
@lower($string)
```
### A3
```php
@A3($key)
@A3($key,$replace = null)
```
### A3L
```php
@A3L($local,$key)
@A3L($local,$key,$replace = null)
```
### A3Words
```php
@A3Words($string,$wordCount = 10, $end = '...')
```
### A3Data
```php
@A3Data($key)
```
### A3Date
```php
@A3Date($ts ,$format ,$local = null)
```
### A3GRS
```php
@A3GRS($length = 10,$type = null)
```
### A3Settings
```php
@A3Settings($key)
```
### A3Assets
```php
@A3Assets($uri = '',$version = false)
```
### A3Request
```php
@A3Request($name, $replace = null)
```
### A3RequestFull
```php
@A3RequestFull($name, $replace = null)
```
### A3nl2br
```php
@A3nl2br($string)
```
### A3Root
```php
@A3Root
```
### A3Domain
```php
@A3Domain
```
### echo
```php
{{ $var }}			//echo $var
{{{ $var }}}		//echo htmlspecialchars($var)
```
### include
```php
@include('path.to.view')
//or
@include('path.to.view',[ 'key' => 'value'])
```
### extends
base.php

```php
@replace('title')
@replace('body')
```
 index.php
```php
@extends('base')
@section('title','A3 Framework')
@section('body')
	<div>
		<div>text</div>
		@include('path.to.view')
	</div>
@endsection
```