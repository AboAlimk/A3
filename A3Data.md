# A3Data
Your data files are located in **A3/A3User/A3Data**

------------
### Usage
- If $key is string:
> If $value is null will return value of key

> If $value is not null will set value of key

- If $key is array
> will set values of keys

```php
A3Data('users.a3');			//A3 Data

//users.php located in A3/A3User/A3Data
<?php
return [
	'a3' => 'A3 Data',
];
```