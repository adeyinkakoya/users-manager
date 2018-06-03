<?php

$host = 'localhost';
$dbname = 'testdb';
$username = 'root';
$password = 'root';

$db = new PDO('mysql:host='.$host.';dbname='.$dbname.';',$username,$password);

?>
