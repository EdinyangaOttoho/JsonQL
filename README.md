# JsonQL
A simple, lightweight implementation that leverages JSON files as databases. **CRUD** supported. The main reason for this solution is to implement database usage (or data storage) using JSON files as mediums. The library consists of some good queries that could be enacted to create databases, tables, insert data, update fields etc using filters as well.

## Usage
To use this library, include the JsonQL file into your project at the top thus;
```php
<?php
  include("./JsonQL/jsonql.php");
  $jsonql = new JsonQL('./JSONQL/');
?>
```
Creating an instance of the class is shown immediately after inclusion of the Library.

#### Creating Databases
  To create databases, you run the following, you call the createDB($x) method with the argument $x being a string and the name of the database to be created. This creates a new database called students. Below is the code;

```php
<?php
  include("./JsonQL/jsonql.php");
  $jsonql = new JsonQL('./JSONQL/');
  $jsonql->createDB("students");
?>
  
```
The directory parsed to the JsonQL Class above is that which contains the following files and should be noted accordingly;

--- database.json

--- jsonql.php

--- users.php

--- security.php

#### Creating Users
To create a user, you simply call the createUser($x, $y) method from the JsonQL class as thus;
```php
<?php
  $jsonql->createUser('me', 'me123@#');  
?>
```
The argument $x stands for the username while the $y stands for the password of the user.

#### Binding Users to Databases
To bind users to a database, you simply call the bindUser($x, $y) method from the JsonQL class as thus;
```php
<?php
  $jsonql->bindUser("workers", "me");
?>
```
That binds the previously created user, me to the database, workers.

#### Connecting to Databases
To connect to a database which has no user bound to it, then use the default user (root) and send the password as an empty string as arguments $user and $password respectively in the method call from the JsonQL class as shown below;
```php
<?php
  $jsonql->connect($db, $user, $password);
?>
```
Else, use the username and password from the user bound to the given database. $db represents the database you are connecting to.

#### Queries
There are a few queries you can make to the databases. Such as CREATE, SELECT, DELETE and UPDATE (which are the intended for this library).


**SELECT:** To select records from a database, you call the query method, specifying the SQL thus;
```php
<?php
  $jsonql->query($db, "SELECT FROM table_name");
?>
```
This does not end this way, because it returns a Class instance for a Handler. Rather, to apply any filter, you call either the all() method, equals($x) or the like($x, $y) method as shown below;

##### like():
```php
<?php
  $jsonql->query($db, "SELECT FROM table_name")->like($array, $pattern);
?>
```
The $array argument is an array which contains the columns and the values to check in the database, while the $pattern specifies the filter pattern. Either at the start of the string (0), end of the string (-1) or within the string (1) For instance, in the function below;
```php
<?php
  $jsonql->query($db, "SELECT FROM table_name")->like(["name"=>"e", "email"=>"a"], 0);
?>
```
The statement above is equivalent to the following in SQL;
```sql
  SELECT * FROM table_name WHERE name LIKE '%e' OR email LIKE '%a'
```
The list extends till infinity (Could reach 20 indexes of OR);

##### equals():
```php
<?php
  $jsonql->query($db, "SELECT FROM table_name")->equals($array);
?>
```
The equals method is similar to the like, but deals with '=' instead of the LIKE clause, but takes only one argument. In the example below;
```php
<?php
  $jsonql->query($db, "SELECT FROM table_name")->equals(["name"=>"Edinyanga"]);
?>
```
It results to the SQL query below;
```sql
  SELECT * FROM table_name WHERE name = '%e'
```
The array parameter could contain different keys and indexes which are joined by OR just like in the like() method.

##### all():
This is very self explanatory as it fetches all the records and returns an array thus;
```
<?php
  $jsonql->query($db, "SELECT FROM table_name")->all();
?>
```
All the SELECT queries result to arrays which could be accessed with keys which are the column names.


**UPDATE:** To update the records in a table in a database, you call the update() method as shown below;
```
<?php
  $jsonql->update($db, $table, $x, $params);
?>
```
The $x is an array containing the column names as key and the values to update as index. The $params is an array which specifies the delimiter, which is similar to the WHERE statement where the key represents the column name and the value represents the value.


**INSERT:** To insert records into a table in a database, you call the insert() method as shown below;
```
<?php
  $jsonql->insert($db, $table, $params);
?>
```
The $x is an array containing the column names as key and the values to be inserted as index.


**DELETE RECORDS:** To delete records from a table in a database, you call the delete() method as shown below;
```
<?php
  $jsonql->delete($db, $table, $params);
?>
```
The $params is an array which specifies the delimiter, which is similar to the WHERE statement where the key represents the column name and the value represents the value.


**CREATE TABLES:** To create a table, you call the query() method and simply use the SQL table creation statement which has only two data types; number and text as shown below;
```
<?php
  $jsonql->query($db, "CREATE TABLE table_name (id number, name text, email, text, date_created text)");
?>
```
It works that simply.


**DROP TABLES:** To delete a table, you call the query() method thus;
```
<?php
  $jsonql->query($db, "DROP TABLE table_name");
?>
```
In the methods above, $db and $table stand for the databases and tables respectively to be affected.

#### Getting Row Count
To get the row count from queried results, use the num_rows($q) method, not within the JsonQL class;
```php
  $count = num_rows($q);
```
Where $q is the queried data;

## Contributors
### Edinyanga Ottoho
Edinyanga Ottoho is a Full-Stack software developer with over 3 years of experience. Stacks are HTML, CSS, Core PHP, Python/Django, EcmaScript 4/6, React Native/NodeJS. A huge reason why the Library is alive.
You can view my profile via this link (https://www.github.com/EdinyangaOttoho) OR Call +2348117093601 (WhatsApp).

<img src="https://avatars3.githubusercontent.com/u/45470783?s=460&v=4" style="width:300px;height:330px">
