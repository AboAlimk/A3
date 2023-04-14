# A3DataBaseGet

**Table of Contents**

- [asArray](#asArray)
- [all](#all)
- [count](#count)
- [index](#index)
- [first](#first)
- [last](#last)

------------


**Return an associative array of results**

```php
$data; // A3DataBaseGet
```

------------
### asArray
Return results as indexed array

```php
$data->asArray();
/* eample */
$data->asArray()->all();
$data->asArray()->count();
$data->asArray()->index($index);
$data->asArray()->first($length = null);
$data->asArray()->last($length = null);
```
### all
Return an array of results

```php
$data->all();
```
### count
Return the number of results

```php
$data->count();
```
### index
Return item by index

```php
$data->index(int $index);
```
### first
```php
$data->first($length = null);
/* example */
$data->first();		//Return first element
$data->first(10);		//Return first 10 elements
```
### last
```php
$data->last($length = null);
/* example */
$data->last();		//Return last element
$data->last(10);		//Return last 10 elements
```