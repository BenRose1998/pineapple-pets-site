<?php
  require_once('includes/connect.php');

  $header = "";
  // Added header to top of page
  include('includes/header.php');

  if(!isset($_SESSION['user_id'])){
    redirect('login.php');
    exit();
  }

  if(isset($_GET['pet'])){
    $user_id = $_SESSION['user_id'];
    $pet_id = $_GET['pet'];
    // SQL DATETIME format: YYYY-MM-DD hh:mm:ss
    $form_created = date('Y-m-d H:i:s');

    // Query
    // Form is created
    $sql = 'INSERT INTO form (user_id, pet_id, form_created) VALUES (:user_id, :pet_id, :form_created)';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id, 'pet_id' => $pet_id, ':form_created' => $form_created]);

    redirect('form.php?id=' . $pdo->lastInsertId());
  }


?>