<?php
// MySQL connection information used when database is hosted locally
DEFINE('HOST', 'localhost:3307');
DEFINE('USERNAME', 'root');
DEFINE('PASSWORD', '');
DEFINE('DBNAME', 'pets');

$dsn = 'mysql:host=' . HOST . ';dbname=' . DBNAME;

$opt = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
PDO::ATTR_EMULATE_PREPARES => false ];

$pdo = new PDO($dsn, USERNAME, PASSWORD, $opt);
?>




