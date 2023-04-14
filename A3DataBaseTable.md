# A3DataBaseTable

**Table of Contents**

- [select](#select)
	- [orderBy](#orderBy)
	- [limit](#limit)
	- [groupBy](#groupBy)
	- [where](#where)
	- [exists](#exists)
	- [get](#get)
- [insert](#insert)
- [paginate](#paginate)
	- [select](#select-1)
	- [where](#where-1)
	- [orderBy](#orderBy-1)
	- [get](#get-1)
	- [getInfo](#getInfo)
- [delete](#delete)
	- [where](#where-2)
	- [do](#do)
- [update](#update)
	- [where](#where-3)
	- [do](#do-1)
- [truncate](#truncate)
- [drop](#drop)
- [rename](#rename)
- [createColumn](#createcolumn)
- [column](#column)
	- [drop](#drop-1)
	- [rename](#rename-1)

------------


```php
$dbTable = A3DataBase::connect()->table($name);
```

------------
### select
If no keys provided will select all '*'

```php
$select = $dbTable->select($key1,$key2,$key3, ...);
```
- #### orderby
```php
$select->orderBy($key, $ascending = true);
/* example */
$select->orderBy('id');
$select->orderBy('id', false);
$select->orderBy([	['id'],['name',false]	]);
```
- #### limit
```php
$select->limit($limit,$offset = null);
/* example */
$select->limit(10);
$select->limit(10, 10);
```
- #### groupBy
```php
$select->groupBy($key1,$key2,$key3, ...);
```
- #### where
Check [A3DataBaseWhere](https://github.com/AboAlimk/A3/blob/master/A3DataBaseWhere.md "A3DataBaseWhere") for usage

```php
$select->where($where,$vars = null);
```
- #### exists
Check if row exists

```php
$select->exists();
```
- #### get
This method will return [A3DataBaseGet](https://github.com/AboAlimk/A3/blob/master/A3DataBaseGet.md "A3DataBaseGet")

```php
$select->get();
```

------------
### insert
> Return **true** or **false** if $getLastId is false

> Retuen  **last iserted id** if $getLastId is true

```php
$dbTable->insert($insert,$getLastId = false);
/* example */
$dbTable->insert(['name' => 'user1']);
$dbTable->insert([['name' => 'user1'],['name' => 'user2', 'phone' => '123456789']]);
```
------------
### paginate
```php
$dbTablePaginate = $dbTable->paginate($perPage = 10,$currentPage = 1);
```
- #### select
If no keys provided will select all '*

```php
$dbTablePaginate->select($key1,$key2,$key3, ...);
```
- #### where
Check [A3DataBaseWhere](https://github.com/AboAlimk/A3/blob/master/A3DataBaseWhere.md "A3DataBaseWhere") for usage

```php
$dbTablePaginate->where($where,$vars = null);
```
- #### orderBy
```php
$dbTablePaginate->orderBy($key, $ascending = true);
/* example */
$dbTablePaginate->orderBy('id');
$dbTablePaginate->orderBy('id', false);
$dbTablePaginate->orderBy([	['id'],['name',false]	]);
```
- #### get
This method will return [A3DataBaseGet](https://github.com/AboAlimk/A3/blob/master/A3DataBaseGet.md "A3DataBaseGet")

```php
$dbTablePaginate->get();
```
- #### getInfo
```php
$dbTablePaginate->getInfo();
/*
This will return
[
	'totalItems' => $totalItems,
	'total' => $total,
	'current' => $current,
	'first' => $first,
	'last' => $last,
	'prev' => $prev,
	'next' => $next,
	'hasPrev' => $hasPrev,
	'hasNext' => $hasNext
]
*/
```

------------
### delete
> Return **true** or **false**

```php
$dbTable->delete();
```
- #### where
Check [A3DataBaseWhere](https://github.com/AboAlimk/A3/blob/master/A3DataBaseWhere.md "A3DataBaseWhere") for usage

```php
$dbTable->delete()->where($where,$vars = null);
```
- #### do
Will run the delete query

```php
$dbTable->delete()->where($where,$vars = null)->do();
```

------------
### update
> Return **true** or **false**

```php
$dbTable->update($update);
/* example */
$dbTable->update(['name' => 'new value']);
```
- #### where
Check [A3DataBaseWhere](https://github.com/AboAlimk/A3/blob/master/A3DataBaseWhere.md "A3DataBaseWhere") for usage

```php
$dbTable->update()->where($where,$vars = null);
```
- #### do
Will run the update query

```php
$dbTable->update($update)->where($where,$vars = null)->do();
```

------------
### truncate
Truncate table
> Return **true** or **false**

```php
$dbTable->truncate();
```

------------
### drop
Drop table
> Return **true** or **false**

```php
$dbTable->drop();
```

------------
### rename
Rename table
> Return **true** or **false**

```php
$dbTable->rename($newName);
```

------------
### createColumn
Create new column
> Return **true** or **false**

```php
$dbTable->createColumn($name,$type,$callable,$vars = null);
```
$vars used to pass vars to callable
```php
/*
$type can be int, tinyInt, smallInt, mediumInt, bigInt, decimal, float, double, boolean, date, datetime, timestap, time, char, varchar, tinyText, text, mediumText or longText
*/
$dbTable->createColumn($name,$type,function($columnData,$var1,$var2){
	// Create column methods
	$columnData->varchar()->length(10);
	$columnData->varchar()->notNull();
	$columnData->varchar()->unique();
	$columnData->varchar()->default('default');
	$columnData->varchar()->comment('comment');
},[$var1,$var2]);
```

------------
### column
```php
$column = $dbTable->drop($name);
```

- #### drop
Drop column
> Return **true** or **false**

```php
$column->drop();
```
- #### rename
Rename column
> Return **true** or **false**

```php
$column->rename($newName);
```