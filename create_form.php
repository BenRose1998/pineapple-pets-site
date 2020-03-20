<?php
  require_once('includes/connect.php');

  $header = "";
  // Added header to top of page
  include('includes/header.php');

  // Redirect user if they are not logged in
  if(!isset($_SESSION['user_id'])){
    // Redirect to login page and exit script
    redirect('login.php');
    exit();
  }

  // If a pet id has been sent
  if(isset($_GET['pet'])){
    // Store user's id
    $user_id = $_SESSION['user_id'];
    // Store pet's id
    $pet_id = $_GET['pet'];
    // Store current date in SQL format
    $form_created = date('Y-m-d H:i:s');

    // Query
    // Form data is inserted into the form table - a form is created
    $sql = 'INSERT INTO form (user_id, pet_id, form_created) VALUES (:user_id, :pet_id, :form_created)';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id, 'pet_id' => $pet_id, ':form_created' => $form_created]);

    // The user is redirected to the page that allows them to fill out the form
    // lastInsertID() method is used to get the id of the form that was just created
    redirect('form.php?id=' . $pdo->lastInsertId());
  }


?>