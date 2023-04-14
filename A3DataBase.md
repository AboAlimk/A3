# A3DataBase

**Table of Contents**

- [Usage](#usage)
- [Create Connection](#create-connection)
- [A3DataBaseRequest::statement](#statement)
	- [get](#get)
	- [getResult](#getResult)
- [A3DataBaseRequest::table](#table)
- [A3DataBaseRequest::createTable](#createtable)

------------
### Usage

```php
A3DataBase::connect($connection = null);
```
If $connection is null, will use 'mysqli' data in **A3/A3User/A3Settings.php** to create connection.
### Create Connection
```php
$connection = new A3DataBaseConnection;
// Create connection methods
$connection->host($host);
$connection->port($port);
$connection->dbname($dbname);
$connection->dbuser($dbuser);
$connection->dbpass($dbpass);
```
You can ignore these methods to use 'mysqli' data in **A3/A3User/A3Settings.php**
### statement
```php
A3DataBase::connect()->statement($statement,$replace = null);
/* example */
$statement = A3DataBase::connect()->statement('select ?,? from table',['id','name']);
```
- #### get
This method will return [A3DataBaseGet](https://github.com/AboAlimk/A3/blob/master/A3DataBaseGet.md "A3DataBaseGet")

```php
$statement->get();
```
- #### getResult
This method will return mysqli_result

```php
$statement->getResult();
```
### table
Check [A3DataBaseTable](https://github.com/AboAlimk/A3/blob/master/A3DataBaseTable.md "A3DataBaseTable") for usage

```php
A3DataBase::connect()->table($name);
```
### createTable
```php
A3DataBase::connect()->createTable($name,$callable = null,$vars = null);
```
$vars used to pass vars to callable

```php
$charset = 'utf8mb4';
$collation = 'utf8mb4_unicode_ci';
A3DataBase::connect()->createTable('users',function($data,$charset,$collation){
	//Table
	$data->charset($charset);
	$data->collation($collation);
	$data->autoIncrement($name);
	//Add column
	$data->int($name);
	$data->tinyInt($name);
	$data->smallInt($name);
	$data->mediumInt($name);
	$data->bigInt($name);
	$data->decimal($name);
	$data->float($name);
	$data->double($name);
	$data->boolean($name);
	$data->date($name);
	$data->datetime($name);
	$data->timestap($name);
	$data->time($name);
	$data->char($name);
	$data->varchar($name);
	$data->tinyText($name);
	$data->text($name);
	$data->mediumText();
	$data->longText();
	// Add column methods
	$data->varchar()->length(10);
	$data->varchar()->notNull();
	$data->varchar()->unique();
	$data->varchar()->default('default');
	$data->varchar()->comment('comment');
},[$charset,$collation]);
```