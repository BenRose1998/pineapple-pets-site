<?php
include_once('includes/header.php');

  // Redirect the user if they are not logged in as staff
  if(!isset($_SESSION['staff'])){
    redirect('login.php');
    exit();
  }





?>