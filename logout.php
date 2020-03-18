<?php
session_start();
session_destroy();
// Using the PHP method of redirecting user which only works before anything has been outputted to the page
$url = 'index.php';
header("Location: " . $url);
?>
