# users-manager

A simple and raw PHP class to manage user login, logout and account creation.

If you don't have already setup the database table you can use this example snippet

```SQL
CREATE TABLE `testdb`.`users` ( `id` INT(10) NOT NULL AUTO_INCREMENT , `email` VARCHAR(50) NOT NULL , `username` VARCHAR(20) NOT NULL , `password` VARCHAR(20) NOT NULL , `session_code` VARCHAR(100) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
```
**Account creation**

This method will not manage the user input validation/sanitization, it will only check the database to verify if an email address or an username was already taken from someone, so don't forget to sanitize or validate your input first. The password will be hashed using the php's built in ```password_hash()``` function. 

```php
$obj = new User($db);

$obj->createUser($email,$username,$password);
```
**Login**

This method will try to setup the ```$_SESSION``` variable by assigning to it the user id, the username and a random generated session code who it will be stored inside the users table and removed every time an user will do the logout.

```php
$obj->loginUser($username,$password);
```
**Logout**

```php
$obj->logoutUser($_SESSION['id']); 
```  
