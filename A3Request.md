# A3Request

You can use A3Request with domain and subdomains, and you should not put subdomains first, A3 Framework will process subdomains then domains.

------------

**Table of Contents**

- [Usage](#Usage)
	- [A3RequestProcess](#A3RequestProcess)
- [name](#name)
- [subDomain](#subDomain)
- [middleProcess](#middleProcess)
- [referer](#referer)
- [where](#where)

------------
### Usage
```php
A3Request::get(string route,A3RequestProcess process);

A3Request::post(string route,A3RequestProcess process);

A3Request::put(string route,A3RequestProcess process);

A3Request::delete(string route,A3RequestProcess process);

A3Request::match(Array methods,string route,A3RequestProcess process);

A3Request::any(string route,A3RequestProcess process);
```
- #### A3RequestProcess
A3RequestProcess classes are located in **A3/A3User/A3RequestProcess**

Process should return String, Number, Array, A3View, A3Redirect or A3Response

```php
function($A3RequestObject){

	return "text";
	//or
	return 123;
	//or
	return ['text 1','text 2','text 3'];
	//or
	return A3View(...);
	//or
	return A3Redirect(...);
	//or
	return A3Response(...);
}
/*
or
"functionName"
or
"class@method"
*/
```
### name
Set A3Request name

```php
A3Request::get(...)->name(string $name);
```
### subDomain
Accept A3RSD_ALL_ALL or string
> A3RSD_ALL:  this will process A3Request if route does not match any subdomain

>string: subdomain

```php
A3Request::get(...)->subDomain();
```
### middleProcess
A3RequestMiddleProcess classes are located in **A3/A3User/A3RequestMiddleProcess**

MiddleProcess should return A3RequestObject, A3Redirect, A3View or A3Response

```php
A3Request::get(...)->middleProcess(Callable $middleProcess);
function($A3RequestObject){
	
	/*
	$A3RequestObject->setError(true);// to return 404 error
	*/
	
	return $A3RequestObject;
	//or
	return A3View(...);
	//or
	return A3Redirect(...);
	//or
	return A3Response(...);
}
/*
or
A3View(...);
or
"functionName"
or
"class@method"
*/
```
### referer
Accept A3RR_DOM , string or array
> A3RR_DOM: allow all requests from same domain and all subdomains

> string: allow requests from specific domain

> array: allow requests from entire array values

```php
A3Request::get(...)->referer(string $name);
```
### where
```php
A3Request::get(...)->where(string $key,string $pattern);
/* example */
A3Request::get("/{id}","process")->where("id","^[a-zA-Z]{2}$");
```
