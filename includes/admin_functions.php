<?php

  function displayForms($pdo){
    echo "<div class='container' id='tab_content'>";

    // Query
    // Pulls records from forms table and appends data from relevant tables
    $sql = "SELECT form.*, user.user_first_name, user.user_last_name, pet.pet_name, pet.pet_age
    FROM form
    INNER JOIN pet ON form.pet_id = pet.pet_id
    INNER JOIN user ON form.user_id = user.user_id
    ORDER BY form.form_id DESC";

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Saves all results in object
    $forms = $stmt->fetchAll();

    ?>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th>Name</th>
          <th>Pet</th>
          <th>Pet Age</th>
          <th>Form Status</th>
          <th>Form Created</th>
          <th>View Form</th>
        </tr>
      </thead>
      <tbody>
      <?php
      // Checks if there is at least 1 result from database query
      if($forms) {
        // For each loop to display all filmss
        foreach($forms as $form){
          // Result is displayed using HTML elements
          echo "<tr>";
          echo "<td>". $form->user_first_name . ' ' . $form->user_last_name . "</td>";
          echo "<td>". $form->pet_name . "</td>";
          echo "<td>". $form->pet_age . "</td>";
          echo "<td>". $form->form_status . "</td>";
          echo "<td>". $form->form_created . "</td>";
          echo "<td><a href='admin_area.php?view=form&id=" . $form->form_id . "'><button type='button'>View</button></a></td>";
          echo "</tr>";
        }
      }
      echo "</tbody>";
      echo "</table>";
      echo "</div>";
  }

  function viewForm($pdo, $id){
    // Query
    // Pulls records from forms table and appends data from relevant tables
    $sql = "SELECT *
            FROM answer
            INNER JOIN form ON answer.form_id = form.form_id
            INNER JOIN pet ON form.pet_id = pet.pet_id
            INNER JOIN user ON form.user_id = user.user_id
            INNER JOIN question ON answer.question_id = question.question_id
            INNER JOIN category ON question.category_id = category.category_id
            LEFT JOIN possible_answer ON answer.possible_answer_id = possible_answer.possible_answer_id
            WHERE answer.form_id = ?";

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    // Saves all results in object
    $answers = $stmt->fetchAll();
    
    if($answers){
      echo '<a href="admin_area.php?view=setFormStatus&id=' . $id . '&status=Approved" class="view-form-btn"><button type="button">Approve for house visit</button></a>';
      echo '<a href="admin_area.php?view=setFormStatus&id=' . $id . '&status=Rejected" class="view-form-btn"><button type="button">Reject</button></a>';
      echo '<a href="admin_area.php?view=finaliseAdoption&form=' . $id . '&pet=' . $answers[0]->pet_id . '" class="view-form-btn"><button type="button">Finalise (Hides pet from Pets page)</button></a>';

      // Display form information
      echo '<h3>User Information</h3>';
      echo '<dl class="row">';
      // Status
      echo '<dt class="col-sm-6">Form Status</dt>';
      echo '<dd class="col-sm-6">' . $answers[0]->form_status . '</dd>';
      // Name
      echo '<dt class="col-sm-6">User Name</dt>';
      echo '<dd class="col-sm-6">' . $answers[0]->user_first_name . ' ' . $answers[0]->user_last_name . '</dd>';
      // Rejected before? (If this user has had a form rejected previously)
      echo '<dt class="col-sm-6">Previously rejected?</dt>';
      echo '<dd class="col-sm-6">' . (checkUserRejection($pdo, $answers[0]->user_id) ? '<span style="color:red;font-weight:bold;">Yes</span>' : 'No')  .  '</dd>';
      // Pet Name
      echo '<dt class="col-sm-6">Pet Name</dt>';
      echo '<dd class="col-sm-6">' . $answers[0]->pet_name . '</dd>';
      // Pet Age
      echo '<dt class="col-sm-6">Pet Age</dt>';
      echo '<dd class="col-sm-6">' . $answers[0]->pet_age . '</dd>';

      echo '</dl>';
      
      // Display Answers
      echo '<h3>Answers</h3>';
      echo '<dl class="row">';
      foreach($answers as $answer){
        echo '<dt class="col-sm-6">' . $answer->question_text . '</dt>';
        // If a possible answer value exists display that if not display a text answer value
        if($answer->possible_answer_id != null){
          echo '<dd class="col-sm-6">' . $answer->possible_answer_value . '</dd>';
        }else{
          echo '<dd class="col-sm-6">' . $answer->answer_value . '</dd>';
        }
      }
      echo '</dl>';

    }else{
      echo 'No form found';
    }
    
  }

  function checkUserRejection($pdo, $user_id){
    // Query
    // Find all forms for a user that have been rejected
    $sql = "SELECT *
    FROM form
    WHERE user_id = ?
    AND form_status = 'Rejected'";

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    // Saves all results in object
    $rejections = $stmt->fetchAll();

    return count($rejections);
  }

  function editForm($pdo){
    echo "<div class='container' id='tab_content'>";

    // Query
    // Pulls questions & appends data from relevant tables
    $sql = "SELECT *
    FROM question
    INNER JOIN category ON question.category_id = category.category_id";

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Saves all results in object
    $questions = $stmt->fetchAll();
    ?>

    <form method="post" action="admin_area.php?view=editForm&submit">

    <?php
    if($questions){
      // Store current category id to compare
      $current_category_id = 0;
      foreach($questions as $question){
        // Check if category id is different from the previous question's category
        if($question->category_id != $current_category_id){
          // If so display a category heading
          $current_category_id = $question->category_id;
          echo '<h4 class="$question->category_id">'. $question->category_name . '</h4>';
        }
        ?>
        <hr>
        <div class="question" id="<?php echo $question->question_id ?>">
        <div class="row">
          <div class="form-group col-md-3 input-type">
            <label>Input type: </label>
            <select class="form-control" name="questions[<?php echo $question->question_id ?>][input_type]" value="check">
              <?php
              echo '<option value="input" ' . ($question->question_type == "input" ? "selected" : '') . '>Text</option>';
              echo '<option value="dropdown" ' . ($question->question_type == "dropdown" ? "selected" : '') . '>Dropdown</option>';
              echo '<option value="check" ' . ($question->question_type == "check" ? "selected" : '') . '>Checkboxes</option>';
              echo '<option value="text" ' . ($question->question_type == "text" ? "selected" : '') . '>Big text area</option>';
              ?>
            </select>
          </div>

          <?php

          // Display question
          echo '<div class="form-group col-md-9 question-text">';
          echo '<label>Question Title: (max: 200 characters)</label>';
          echo '<input type="hidden" name="questions[' . $question->question_id . '][question_id]" value="'. $question->question_id .'">';
          echo '<input type="hidden" name="questions[' . $question->question_id . '][category_id]" value="'. $question->category_id .'">';
          echo '<input type="text" class="form-control" name="questions[' . $question->question_id . '][text]" value="' . $question->question_text . '">';
          echo '</div>';
          echo '</div>';
          if($question->question_type == 'dropdown' || $question->question_type == 'check'){
            // Query
            // Find possible answers for question
            $sql = "SELECT possible_answer_id, possible_answer_value
            FROM possible_answer
            WHERE question_id = ?";

            // Prepare and execute statement
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$question->question_id]);
            // Saves all results in object
            $answers = $stmt->fetchAll();

            echo '<div class="answers" id="' . $question->question_id . '">';
            if($answers){
              
              foreach($answers as $answer){
                //echo '<div class="row">';
                  echo '<div class="col-md-3"></div>';
                  echo '<div class="form-group col-md-9 question-text">';
                    echo '<label class="option-input">Option: </label>';
                    echo '<input type="hidden" name="answers[' . $answer->possible_answer_id . '][question_id]" value="' . $question->question_id . '">';
                    echo '<input type="hidden" name="answers[' . $answer->possible_answer_id . '][possible_answer_id]" value="' . $answer->possible_answer_id . '">';
                    echo '<input type="text" class="form-control col-md-9 option-input" name="answers[' . $answer->possible_answer_id . '][answer]" value="' . $answer->possible_answer_value . '">';
                    echo '<a href=""><button type="button" class="btn-block col-md-1 add-option">X</button></a>';
                  echo '</div>';
              }
              echo '</div>';
              echo '<a href=""><button type="button" class="btn-block col-md-9 add-option">Add Option</button></a>';
            }else{
              echo '</div>';
              echo '<a href=""><button type="button" class="btn-block col-md-9 add-option">Add Option</button></a>';
            }
          }
        echo '<a href=""><button type="button" class="btn-block add-question">Add New Question Here</button></a>';
        echo '</div>';
      }
      echo '</br>';
      echo '<button type="submit" class="btn-block">Submit</button>';
      echo '</br>';
      echo '</form>';
      echo '</div>';
    }
  }

  function updateForm($pdo){
    // print_r($_POST['answers']);

    if (isset($_POST) && !empty($_POST)) {

      if($_POST['questions']){
        foreach($_POST['questions'] as $question){
          // Check if question length exceeds limit
          if(strlen($question['text']) > 200){
            // Print error & exit script
            echo 'Question length exceeded 200 characters, please try again';
            exit();
          }

          if(isset($question['question_id'])){
            $question_id = $question['question_id'];
          }else{
            $question_id = null;
          }
          
          $category_id = $question['category_id'];
          $question_text = $question['text'];
          $question_type = $question['input_type'];

          $sql = "INSERT INTO question 
                  VALUES (:question_id, :category_id, :question_text, :question_type)
                  ON DUPLICATE KEY UPDATE question_id=VALUES(question_id), category_id=VALUES(category_id), question_text=VALUES(question_text), question_type=VALUES(question_type);";

          // Prepare and execute statement
          $stmt = $pdo->prepare($sql);
          $stmt->execute(['question_id' => $question_id, 'category_id' => $category_id, 'question_text' => $question_text, 'question_type' => $question_type]);
        }
      }

      if($_POST['answers']){
        foreach($_POST['answers'] as $answer){
          if(isset($answer['possible_answer_id'])){
            $possible_answer_id = $answer['possible_answer_id'];
          }else{
            $possible_answer_id = null;
          }
          
          $question_id = $answer['question_id'];
          $answer = $answer['answer'];

          $sql = "INSERT INTO possible_answer 
                  VALUES (:possible_answer_id, :question_id, :possible_answer_value)
                  ON DUPLICATE KEY UPDATE possible_answer_id=VALUES(possible_answer_id), question_id=VALUES(question_id), possible_answer_value=VALUES(possible_answer_value);";

          // Prepare and execute statement
          $stmt = $pdo->prepare($sql);
          $stmt->execute(['possible_answer_id' => $possible_answer_id, 'question_id' => $question_id, 'possible_answer_value' => $answer]);
        }
      }
      redirect('admin_area.php?view=editForm');
    }
  }

  function setFormStatus($pdo, $id, $status){
    // Query
    // Update form status
    $sql = 'UPDATE form SET form_status = ? WHERE form_id = ?';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status, $id]);
    redirect('admin_area.php?view=form&id=' . $id);
  }

  function displayUsers($pdo){
    echo "<div class='container' id='tab_content'>";

    // Query
    // Pulls records from forms table and appends data from relevant tables
    $sql = "SELECT user.user_id, user_first_name, user_last_name, user_email, user_created, staff_id
    FROM user
    LEFT JOIN staff ON user.user_id = staff.user_id
    ORDER BY user.user_id DESC";

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Saves all results in object
    $users = $stmt->fetchAll();

    ?>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th>Account Type</th>
          <th>Name</th>
          <th>Email</th>
          <th>Account Created</th>
          <th>Make Staff</th>
        </tr>
      </thead>
      <tbody>
      <?php
      // Checks if there is at least 1 result from database query
      if($users) {
        // For each loop to display all filmss
        foreach($users as $user){
          // Result is displayed using HTML elements
          echo "<tr>";
          echo "<td>". (isset($user->staff_id) ? 'Staff' : 'User') . "</td>";
          echo "<td>". $user->user_first_name . ' ' . $user->user_last_name . "</td>";
          echo "<td>". $user->user_email . "</td>";
          echo "<td>". $user->user_created . "</td>";
          if(!isset($user->staff_id)){
            echo "<td><a href='admin_area.php?view=users&id=" . $user->user_id . "'><button type='button'>Make staff</button></a></td>";
          }else{
            echo "<td></td>";
          }
          echo "</tr>";
        }
      }
      echo "</tbody>";
      echo "</table>";
      echo "</div>";
  }

  function makeStaff($pdo, $id){
    // Query
    // Pulls records from forms table and appends data from relevant tables
    $sql = 'INSERT INTO staff (user_id) VALUES (?)';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
  }

  function displayPets($pdo){
    echo "<div class='container' id='tab_content'>";

    // Query
    // Pulls records from pet table and appends data from relevant tables
    $sql = "SELECT *
    FROM pet
    INNER JOIN pet_type ON pet.pet_type_id = pet_type.pet_type_id
    INNER JOIN pet_breed ON pet.pet_breed_id = pet_breed.pet_breed_id
    ORDER BY pet.pet_id DESC";

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Saves all results in object
    $pets = $stmt->fetchAll();

    ?>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th>Pet Name</th>
          <th>Type</th>
          <th>Breed</th>
          <th>Age</th>
          <th>Description</th>
          <th>Display</th>
        </tr>
      </thead>
      <tbody>
      <?php
      // Checks if there is at least 1 result from database query
      if($pets) {
        // For each loop to display all filmss
        foreach($pets as $pet){
          // Result is displayed using HTML elements
          echo "<tr>";
          echo "<td>". $pet->pet_name . "</td>";
          echo "<td>". $pet->pet_type_name . "</td>";
          echo "<td>". $pet->pet_breed_name . "</td>";
          echo "<td>". $pet->pet_age . "</td>";
          echo "<td>". $pet->pet_description . "</td>";
          if($pet->pet_active){
            echo "<td><a href='admin_area.php?view=pets&id=" . $pet->pet_id . "'><button type='button'>X</button></a></td>";
          }else{
            echo '<td></td>';
          }
        }
      }
      echo "</tbody>";
      echo "</table>";
      echo "</div>";
  }

  function removePet($pdo, $id){
    // Query
    // Pulls records from forms table and appends data from relevant tables
    $sql = 'UPDATE pet SET pet_active = false WHERE pet_id = ?';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
  }

  function showEmailToggle($pdo){
    // Query
    // Select the email notification value for this user
    $sql = 'SELECT staff_email_notification
    FROM staff
    WHERE user_id = ?';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    // Saves result in object
    $value = $stmt->fetch();

    echo '<a href="admin_area.php?view=settings&toggleEmail"><button type="button" class="">Turn ' . ($value->staff_email_notification ? 'off' : 'on') . ' my email notifications</button></a>';
  }

  function toggleEmail($pdo){
    // Query
    // Toggles the staff_email_notification value
    $sql = 'UPDATE staff 
    SET staff_email_notification = 1 - staff_email_notification
    WHERE staff_id = ?';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
  }

?>