<?php

function checkFormUser($pdo){
  if(isset($_GET['id'])){
  // Check if form's user id matches user
    $form_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Query
    // 
    $sql = 'SELECT *
            FROM form
            WHERE form_id = ?
            LIMIT 1';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$form_id]);
    // Saves result in object
    $form = $stmt->fetch();

    if($form){
      // Check if user's id is the same as the form's user
      if($form->user_id != $user_id){
        redirect('index.php');
        exit();
      }
    }else{
      // No form found
      $error = "No form found";
    }
  }
}


function sendEmail($pdo){
  // Query
  // 
  $sql = 'SELECT user_email
          FROM staff
          INNER JOIN user ON staff.user_id = user.user_id
          WHERE staff_email_notification = 1';

  // Prepare and execute statement
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  // Saves result in object
  $emails = $stmt->fetchAll();

  // Checks if there is at least 1 result from database query
  if($emails) {
    // For each loop to display all staff
    foreach($emails as $email){
      $msg = 'This is a test message';
      mail($email->user_email, "Test Subject", $msg);
      echo 'Email sent to ' . $email->user_email . ' saying: ' . $msg;
    }
  }
}


?>