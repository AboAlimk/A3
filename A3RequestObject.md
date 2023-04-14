# A3RequestObject

You can use inside **A3RequestProcess** or **A3RequestMiddleProcess**

------------
**Table of Contents**

- [setError](#setError)
- [setData](#setData)
- [getMethod](#getMethod)
- [getRoute](#getRoute)
- [getRouteItems](#getRouteItems)
- [getName](#getName)
- [getError](#getError)
- [getData](#getData)
- [get](#get)
- [post](#post)
- [put](#put)
- [delete](#delete)
- [files](#files)
- [getUserIp](#getUserIp)
- [getReferer](#getReferer)
- [getAuth](#getAuth)
- [getBaseRoute](#getBaseRoute)
- [isSecure](#isSecure)
- [getUri](#getUri)
- [getQuery](#getQuery)
- [getRequestRouteItems](#getRequestRouteItems)
- [getLink](#getLink)
- [getFullLink](#getFullLink)
- [getFullRequestData](#getFullRequestData)
- [getAll](#getAll)

------------

```php
class requestProcess{
	public function home($A3RequestObject){
		...
	}
}
```

------------

### setError
Return 404 error page when true

```php
$A3RequestObject->setError(bool $error);
```
### setData
Accept string, number or array to attach to A3RequestObject and retrieve it later

```php
$A3RequestObject->setData($data);
```
### getMethod
Return method "get", "post", "put" or "delete"

```php
$A3RequestObject->getMethod();
```
### getRoute
Return route

```php
$A3RequestObject->getRoute();
```
### getRouteItems
Return route item by index or all if leave blank

```php
$A3RequestObject->getRouteItems(int $index = null);
```
### getName
Return name of A3Request

```php
$A3RequestObject->getName( );
```
### getError
Return error status (true or false)

```php
$A3RequestObject->getError( );
```
### getData
Return attached data of A3Request

```php
$A3RequestObject->getData( );
```
### get
Return get method items by name or all if leave blank

> $def:  default value if item not exists

```php
$A3RequestObject->get(string $name = null,$def = false );
```
### post
Return post method items by name or all if leave blank

> $def:  default value if item not exists

```php
$A3RequestObject->post(string $name = null,$def = false );
```
### put
Return put method items by name or all if leave blank

> $def:  default value if item not exists

```php
$A3RequestObject->put(string $name = null,$def = false );
```
### delete
Return delete method items by name or all if leave blank

> $def:  default value if item not exists

```php
$A3RequestObject->delete(string $name = null,$def = false );
```
### files
Return $_FILES by name or all if leave blank

```php
$A3RequestObject->get(string $name = null);
```
### getUserIp
Return $_SERVER['REMOTE_ADDR']

```php
$A3RequestObject->getUserIp();
```
### getReferer
Return $_SERVER['HTTP_REFERER']

```php
$A3RequestObject->getReferer();
```
### getAuth
Return $_SERVER['HTTP_AUTHORIZATION']

```php
$A3RequestObject->getAuth();
```
### getBaseRoute
Return route of A3Request

```php
$A3RequestObject->getBaseRoute();
```
### isSecure
Return true if https, otherwise false

```php
$A3RequestObject->isSecure();
```
### getUri
Return $_SERVER['REQUEST_URI']

```php
$A3RequestObject->getUri();
```
### getQuery
Return $_SERVER['QUERY_STRING']

```php
$A3RequestObject->getQuery();
```
### getRequestRouteItems
Return route items by name or all if leave blank

```php
$A3RequestObject->getRequestRouteItems(string $key = null);
```
### getLink
Return route after replace items

```php
$A3RequestObject->getLink(array $replace = null);
```
### getFullLink
Return full link after replace route items

```php
$A3RequestObject->getFullLink(array $replace = null);
```
### getFullRequestData
Return $_SERVER

```php
$A3RequestObject->getFullRequestData();
```
### getAll
```php
$A3RequestObject->getAll();
```