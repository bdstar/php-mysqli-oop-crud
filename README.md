# Complete CRUD operation by Object-oriented programming(OOP) PHP library and MySQLi database

Here, I have presented the complete Create-Read-Update-Delete(CRUD) operations by Object-oriented programming(OOP) PHP library. In the view page, you will see the entire data as the Bootstrap datatable view and that includes a search box, pagination, number of entries per page, and sortable column.  In add/edit data page, a bootstrap javascript client-side form validation and PHP method for server-side validation has been integrated. To delete data, I have used sweet alert to confirm the delete data. This whole system we are developed using PHP and MySQL database.

## Create a Database and Database Table

First, you have to log in to PHPMyAdmin. Next, click on the Database tab to create a new database. Enter your database name and click on create database button. Or you can use the following SQL query

```sql
CREATE DATABASE testDB;
```

Once you create a database, you need to create a user table.

```sql
CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

Add 'id' as Primary key and 'slug' as Unique key,
```sql
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tag_slug` (`slug`);
```

'id' column will be auto increment, 
```sql
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
```

## PHP Library for CRUD(database.php)
First, you need to set Database Host, username, password and name for database connection 
```php
public $hostname = "localhost";
public $username = "root";
public $passowrd = "";
public $database = "testDB";
```

Now create an object for this class,
```php
$obj = new Database();
```

### Insert Method
```php
//Table Name
$tablename = 'tag';
//Associative array for insert function
$InsColumnVal = array("name"=>'testname',"description"=>'this is a text');
//Call insert function to insert record
$obj->insert($tablename, $InsColumnVal);
```

### Update Method
```php
//Table Name
$tablename = 'tag';
//Associative array to set query for update function
$set = array("name"=>'updatename',"description"=>'this is update text');
//Associative array to condition query for update function
$condition = array("id"=>1);
//call update function
$obj->update($tablename, $set,$condition);
```

### Delete Method
```php
//Table Name
$tablename = 'tag';
//Associative array for delete function
$DelColumnVal = array("id"=>1);
//Call Delete function
$obj->delete($tablename, $DelColumnVal);
```
Each Insert, Update and Delete method returns an array that has two elements, one is the message and the other is return type true or false. 

### Read Method
If you pass a blank array to the $select variable then all of the column data will be selected. It will return an array for the result. 

```php
//Table Name
$tablename = 'tag';
//Select column name
$select = array("name","description","updated_at");
// Fetch data from the table
$show = $obj->fetch($tablename, $select);
// Show data from table
echo "<pre>";
print_r($show);
echo "</pre>";
```

### Field validation method
```php
$fieldname = 'Name';
$fieldvalue = $_POST["name"];
$fieldlengh = 100;
$obj->validation($fieldname, $fieldvalue, $fieldlengh);
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## Credits
1. [Bootstrap 4](https://getbootstrap.com/docs/4.0/getting-started/introduction/)  - Frontend framwork
2. [Bootstrap DataTable](https://datatables.net/examples/styling/bootstrap4) - Table Data View
3. [Font Awesome v4.7](https://fontawesome.com/v4.7/) - Front Icon
4. [Toster](https://codeseven.github.io/toastr/) - Notification purposes
5. [Sweet Alart](https://sweetalert2.github.io/) - Alart System

## License
[MIT](https://choosealicense.com/licenses/mit/)