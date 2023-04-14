# A3Array

**Table of Contents**

- [A3Array::is](#is)
- [A3Array::count](#count)
- [A3Array::keys](#keys)
- [A3Array::values](#values)
- [A3Array::get](#get)
- [A3Array::set](#set)
- [A3Array::merge](#merge)
- [A3Array::keysCase](#keysCase)
- [A3Array::chunk](#chunk)
- [A3Array::limit](#limit)
- [A3Array::rLimit](#rLimit)
- [A3Array::diff](#diff)
- [A3Array::contains](#contains)
- [A3Array::containsKey](#containsKey)
- [A3Array::random](#random)
- [A3Array::reverse](#reverse)
- [A3Array::sort](#sort)
- [A3Array::unique](#unique)
- [A3Array::first](#first)
- [A3Array::end](#end)
- [A3Array::removeFirst](#removeFirst)
- [A3Array::removeEnd](#removeEnd)
- [A3Array::shuffle](#shuffle)
- [A3Array::addStart](#addStart)
- [A3Array::addEnd](#addEnd)

------------
### is
###### **Bool - Check if item is array**

```php
A3Array::is($item);
```
### count
###### **Int - Return the number of elements in an array**
```php
A3Array::count($array);
```
### keys
###### **Array - Return an array containing the keys**
```php
A3Array::keys($array);
```
### values
###### **Array - Return an array containing the values**
```php
A3Array::values($array);
```
### get
###### **Used in A3GetPathValue()**
```php
A3Array::get( $array , $path , $def = false );
```
### set
###### **Used in A3SetPathValue()**
```php
A3Array::set( $array , $path , $value );
```
### merge
###### **Array - Merge multiple arrays into one array**
```php
A3Array::merge($array1, $array2, $array3, ...);
```
### keysCase
###### **Array - Change all keys in an array to uppercase or lowercase**

$case can ba CASE_LOWER or CASE_UPPER

```php
A3Array::keysCase($array,$case);
```
### chunk
###### **Array - Split an array into chunks**
```php
A3Array::chunk($array,$limit);
```
### limit
###### **Array - Slice an array from start**
```php
A3Array::limit($array,$limit);
```
### rLimit
###### **Array - Slice an array from end**
```php
A3Array::rLimit($array,$limit);
```
### diff
###### **Array - Compare the values of multiple arrays**
```php
A3Array::diff($array1, $array2, $array3, ...);
```
### contains
###### **Bool - Search for value in an array**
```php
A3Array::contains($array,$item);
```
### containsKey
###### **Bool - Search for key in an array**
```php
A3Array::containsKey($array,$key);
```
### random
###### **Return an array of random keys**
```php
A3Array::random($array,$count);
```
### reverse
###### **Array - Return an array in the reverse order**
```php
A3Array::reverse($array);
```
### sort
###### **Array - Sort the elements of an array**
$sort can be
>A3AS_ACS: sort an array in ascending alphabetical order

>A3AS_DESCS: sort an array in descending alphabetical order

>A3AS_KACS: sort an associative array in ascending alphabetical order

>A3AS_KDESCS: sort an associative array in descending alphabetical order

```php
A3Array::sort($array,$sort);
```
### unique
###### **Array - Remove duplicated values from an array**
```php
A3Array::unique($array);
```
### first
###### **Return the first element from an array**
```php
A3Array::first($array);
```
### end
###### **Return the last element from an array**
```php
A3Array::end($array);
```
### removeFirst
###### **Array - Remove the first element from an array**
```php
A3Array::removeFirst($array);
```
### removeEnd
###### **Array - Remove the last element from an array**
```php
A3Array::removeEnd($array);
```
### shuffle
###### **Array - Randomize the order of the elements in the array**
```php
A3Array::shuffle($array);
```
### addStart
###### **Array - Add multiple elements to array at start**
```php
A3Array::addStart($array,$item1,$item2, ...);
```
### addEnd
###### **Array - Add multiple elements to array at end**
```php
A3Array::addEnd($array,$item1,$item2, ...);
```