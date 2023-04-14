# A3Helpers

**Table of Contents**

- [A3GetPathValue](#A3GetPathValue)
- [A3SetPathValue](#A3SetPathValue)
- [A3Assets](#A3Assets)
- [A3Request](#A3Request)
- [A3RequestFull](#A3RequestFull)
- [A3CurrentRequest](#A3CurrentRequest)
- [A3CurrentRequestFull](#A3CurrentRequestFull)
- [A3RequestExists](#A3RequestExists)
- [A3GetBrowserLanguage](#A3GetBrowserLanguage)
- [A3GetLanguagesCodes](#A3GetLanguagesCodes)
- [A3](#A3)
- [_A3](#_A3)
- [A3L](#A3L)
- [_A3L](#_A3L)
- [A3Json](#A3Json)
- [A3JsonX](#A3JsonX)
- [A3GRS](#A3GRS)
- [A3Words](#A3Words)
- [A3Date](#A3Date)
- [A3_ROOT](#A3_ROOT)
- [A3_DOMAIN](#A3_DOMAIN)

------------
### A3GetPathValue
```php
A3GetPathValue($array , $path , $def = false);

/* example */
$date = [
	'item1' => [
		'item2' => [
			'item3' => 'value3'
		]
	]
];
print_r(A3GetPathValue($date , 'item1'));
/* output */
array(
		'item2' => array(
			'item3' => 'value3'
		)
)
echo A3GetPathValue($date , ['item1','item2','item3']);		//value3
echo A3GetPathValue($date , ['item1','item2','item5'],'none');		//none
```
### A3SetPathValue
Set value of key and return new array

```php
A3SetPathValue($array , $path , $value);
```
### A3Assets
Return full link of assets file
Assests files are locateed in public_html

```php
A3Assets($uri = '',$version = false);

/* example */
A3Assest('css/main-style.css');		//https://www.example.com/css/main-style.css
A3Assest('css/main-style.css','1234567890');		// https://www.example.com/css/main-style.css?v=1234567890
```
### A3Request
Return link of A3Request by name and replace its vars

```php
A3Request($name, $replace = null);
```
### A3RequestFull
Return full link of A3Request by name and replace its vars

```php
A3RequestFull($name, $replace = null);
```
### A3CurrentRequest
Return link of curren A3Request and replace its vars

```php
A3CurrentRequest($replace = null);
```
### A3CurrentRequestFull
Return full link of curren A3Request and replace its vars

```php
A3CurrentRequestFull($replace = null);
```
### A3RequestExists
Check if A3Request exists

```php
A3RequestExists($name);
```
### A3GetBrowserLanguage
Return 2 letters for browser language

```php
A3GetBrowserLanguage();
```
### A3GetLanguagesCodes
Return codes of your lnguages

```php
A3GetLanguagesCodes();
```
### A3
Return language value for given key after replacing its vars (use A3::getLocal())

```php
A3($key,$replace = []);
```
### _A3
Print language value for given key after replacing its vars (use A3::getLocal())

```php
_A3($key,$replace = []);
```
### A3L
Return language value for given local and key after replacing its vars

```php
A3L($local,$key,$replace = []);
```
### _A3L
Print language value for given local and key after replacing its vars

```php
_A3L($local,$key,$replace = []);
```
### A3Json
Return JSON encoded string

```php
A3Json($value);	//{'a':'x1','b':'x2','c':'x3'}
```
### A3JsonX
Return JSON encoded string X 

```php
A3JsonX($item1, $item2 , ...$itemN);		//{'x1':'a','x2':'b','x3':'c'}
```
### A3GRS
Generate random string
$type:
> A3SR_LTR: only letters

> A3SR_NUM: only numbers

> null: letters and numbers

```php
A3GRS($length = 10,$type = null);
```
### A3Words
Cut string after number of words

```php
A3Words($txt, $count = 10, $end = '...');
```
### A3Date
Return formated date

If $ts is null $ts will be time()

$local can be 'en' or 'ar'

```php
A3Date($ts ,$format ,$local = null);
/*
	Format
	d: Day of the month, 2 digits with leading zeros
	D: A textual representation of a day
		* en: Monday to Subday
		* ar: الاثنين to الأحد
	j: ay of the month without leading zeros
	m: Numeric representation of a month, with leading zeros
	M: A textual representation of a month
		* en: January to December
		* ar: يناير to ديسمبر
	n: Numeric representation of a month, without leading zeros
	Y: A full numeric representation of a year, 4 digits
	y: A full numeric representation of a year, 4 digits
	a: Ante meridiem and Post meridiem
		* en: AM or PM
		* ar: صباحاً or مساءً
	H: 24-hour format of an hour with leading zeros
	h: 12-hour format of an hour with leading zeros
	i: Minutes with leading zeros
	s: Seconds with leading zeros
*/
```
### A3_ROOT
Return root link of site

```php
echo A3_ROOT;
```
### A3_DOMAIN
Return domain

```php
echo A3_DOMAIN;
```
