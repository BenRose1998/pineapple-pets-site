<?php

// Checks if a user has the right to access a specific form
function checkFormUser($pdo){
  if(isset($_GET['id'])){
  // Check if form's user id matches user
    $form_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Query
    // Selects all form data for a specific form
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
      // Check if user's id is not the same as the form's user (this means they don't have access)
      if($form->user_id != $user_id){
        // Redirects the user to the homepage
        redirect('index.php');
        // Exits the script
        exit();
      }
    }else{
      // No form found
      $error = "No form found";
    }
  }
}

// Sends an email to all staff that have email notifications enabled - notifies them that an adoption form has been submitted
function sendEmail($pdo){
  // Please note: The automatic sending of emails will need to be configured on the server in which the website is deployed.

  // Query
  // Selects all email address' for all staff that have email notifications enabled
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
      // Create subject & message
      $subject = 'Pineapple Pets - Adoption Form Submission';
      $msg = 'An adoption request form has been submitted';
      // Send message & subject
      mail($email->user_email, $subject, $msg);
    }
  }
}

?>