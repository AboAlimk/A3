# A3String

**Table of Contents**

- [A3String::is](#is)
- [A3String::match](#match)
- [A3String::matches](#matches)
- [A3String::replace](#replace)
- [A3String::contains](#contains)
- [A3String::pos](#pos)
- [A3String::length](#length)
- [A3String::range](#range)
- [A3String::dRange](#dRange)
- [A3String::limit](#limit)
- [A3String::rLimit](#rLimit)
- [A3String::afterLimit](#afterLimit)
- [A3String::beforeLimit](#beforeLimit)
- [A3String::before](#before)
- [A3String::after](#after)
- [A3String::starts](#starts)
- [A3String::ends](#ends)
- [A3String::addStart](#addStart)
- [A3String::addEnd](#addEnd)
- [A3String::removeStart](#removeStart)
- [A3String::removeEnd](#removeEnd)
- [A3String::upper](#upper)
- [A3String::lower](#lower)
- [A3String::isUpper](#isUpper)
- [A3String::isLower](#isLower)
- [A3String::title](#title)
- [A3String::words](#words)
- [A3String::random](#random)
- [A3String::replaceArray](#replaceArray)
- [A3String::camel](#camel)
- [A3String::snake](#snake)
- [A3String::mask](#mask)

------------
### is
###### **Bool**
```php
A3String::is($pattern,$value = null);
/* example */
A3String::is('text');				//true
A3String::is(123);				//false
A3String::is([1,2,3]);			//false
A3String::is('te*', 'text');	//true
```
### match
###### **Bool**

```php
A3String::match($pattern,$txt);
```
### matches
###### **Array**

```php
A3String::matches($pattern,$txt,$flags = 0,$offset = 0);
```
### replace
```php
A3String::replace($txt,$search,$replace);
/* example */
A3String::replace('string 1 2 3',['1','2','3'],'n');		//string n n n
A3String::replace('string 1 2 3',['1','2','3'],['n1','n2','n3']);		//string n1 n2 n3
A3String::replace('string x','x','n');		//string n
```
### contains
###### **Bool**

```php
A3String::contains($txt,$search);
```
### pos
Find the position of the first occurrence of search

```php
A3String::pos($txt,$search);
```
### length
###### **Int**

```php
A3String::length($txt);
```
### range
```php
A3String::range($txt,$start,$end);
/* example */
A3String::range('string',1,4);				//tri
```
### dRange
```php
A3String::dRange($txt,$start,$end);
/* example */
A3String::dRange('string',1,1);				//trin
```
### limit
```php
A3String::limit($txt,$limit);
/* example */
A3String::limit('string',3);				//str
```
### rLimit
```php
A3String::rLimit($txt,$limit);
/* example */
A3String::rLimit('string',3);				//ing
```
### afterLimit
```php
A3String::afterLimit($txt,$limit);
/* example */
A3String::afterLimit('string',2);				//ring
```
### beforeLimit
```php
A3String::beforeLimit($txt,$limit);
/* example */
A3String::beforeLimit('string',2);				//stri
```
### before
```php
A3String::before($txt,$search);
/* example */
A3String::before('string','in');				//str
```
### after
```php
A3String::after($txt,$search);
/* example */
A3String::after('string','tr');				//ing
```
### starts
###### **Bool**
```php
A3String::starts($txt,$search);
/* example */
A3String::starts('string','s');				//true
A3String::starts('string','n');				//false
```
### ends
###### **Bool**
```php
A3String::ends($txt,$search);
/* example */
A3String::ends('string','g');				//true
A3String::ends('string','n');				//false
```
### addStart
###### **String**
```php
A3String::addStart($txt,$search);		//string
/* example */
A3String::addStart('string','a');				//astring
A3String::addStart('string','s');				//string
```
### addEnd
###### **String**
```php
A3String::addEnd($txt,$search);
/* example */
A3String::addEnd('string','a');				//stringa
A3String::addEnd('string','g');				//string
```
### removeStart
```php
A3String::removeStart($txt,$search);
/* example */
A3String::removeStart('string','s');				//tring
A3String::removeStart('string',['s','t','r']);				//tring
A3String::removeStart('string','a');				//string
```
### removeEnd
```php
A3String::removeEnd($txt,$search);
/* example */
A3String::removeEnd('string','g');				//strin
A3String::removeEnd('string',['g','n','i']);				//strin
A3String::removeEnd('string','a');				//string
```
### upper
###### **String**
```php
A3String::upper($txt);
/* example */
A3String::upper('string');				//STRING
```
### lower
###### **String**
```php
A3String::lower($txt);
/* example */
A3String::lower('sTriNg');				//string
```
### isUpper
###### **Bool**
```php
A3String::isUpper($txt);
/* example */
A3String::isUpper('STRING');				//true
A3String::isUpper('string');				//false
A3String::isUpper('sTriNg');				//false
```
### isLower
###### **Bool**
```php
A3String::isLower($txt);
/* example */
A3String::isLower('string');				//true
A3String::isLower('STRING');				//false
A3String::isLower('sTriNg');				//false
```
### title
###### **String**
```php
A3String::title($txt);
/* example */
A3String::title('string1 string2 string3');				//String1 String2 String3
```
### words
###### **String - Used in words()**
```php
A3String::words($txt, $count = 10, $end = '...');
```
### random
###### **Bool - Used in A3GRS()**

```php
A3String::random($length = 10,$type = null);
```
### replaceArray
A3() and A3L() use this method to rplace language vars
When adding : to start of key it will be case sensitive

```php
A3String::replaceArray($txt,$replace);
/* example */
A3String::replaceArray('string :replace',['replace'=>'str']);		//string str
A3String::replaceArray('string :replace',[':replace'=>'str']);		//string str
A3String::replaceArray('string :replace',[':Replace'=>'str']);		//string Str
A3String::replaceArray('string :replace',[':REPLACE'=>'str']);		//string STR
```
### camel

```php
A3String::camel($txt);
/* example */
A3String::camel('string_string');		//stringString
A3String::camel('string-string');		//stringString
```
### snake

```php
A3String::snake($txt,$replace = '_');
/* example */
A3String::snake('stringString');		//string_string
A3String::snake('stringString','-');		//string-string
```
### mask

```php
A3String::mask($txt, $mask, $start, $end = false);
/* example */
A3String::mask('stringstring','*',4);		//stri********
A3String::mask('stringstring','*',4,2);		//string****ng
```