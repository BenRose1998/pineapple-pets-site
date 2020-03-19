<?php
require_once('includes/connect.php');

$header = "Form";
// Added header to top of page
include_once('includes/header.php');
include_once('includes/functions.php');

function getAnswers($pdo, $question_id){
  // Query
  // Get answers for a specific question
  $sql = 'SELECT *
  FROM possible_answer
  WHERE question_id = ?';

  // Prepare and execute statement
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$question_id]);
  // Saves result in object
  $answers = $stmt->fetchAll();
  return $answers;
}

$error = null;

if(!isset($_SESSION['user_id'])){
  redirect('login.php');
  exit();
}else{
  // Check if form's user id matches user
  checkFormUser($pdo);

  // Check if category specified, else set it to 1
  if(isset($_GET['c'])){
    $category = $_GET['c'];
  }else{
    $category = 1;
  }

  // Get questions for form by category
  // Query
  // Select the email notification value for this user
  $sql = 'SELECT *
  FROM question
  INNER JOIN category ON question.category_id = category.category_id
  WHERE question.category_id = ?';

  // Prepare and execute statement
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$category]);
  // Saves result in object
  $questions = $stmt->fetchAll();

  // Checks if the form has been submitted
  if (isset($_POST) && !empty($_POST)) {
    // print_r($_POST);
    
    // Store form id in variable
    $form_id = $_POST['form_id'];

    // Remove first element of array (form_id)
    unset($_POST['form_id']);


      // Loop through posted answers
      foreach($_POST as $key => $answer){
        // Set default number of answer to 1
        $no_of_answers = 1;

        // Check if checkbox values have been sent (array of answers)
        if(array_key_exists('check', $answer)){
          // Set number of answers to length of array sent
          $no_of_answers = count($answer['check']);
        }else{
          // Check if answer value is empty (only if it's not a checkbox question)
          if(trim(reset($answer)) == ''){
            $error = 'Information missing';
          }
        }
        
        if(!$error){
          // Loop through no_of_answers
          for($i = 0; $i < $no_of_answers; $i++){
            // Save answer type (e.g. text or check)
            $answer_type = key($answer);
            // Different functionality depending on answer type
            if($answer_type == 'input' || $answer_type == 'text'){
              // Trims white space and stores user input as variable
              $answer_value = trim(reset($answer));
              // Check if answer value length exceeds limit
              if(strlen($answer_value) > 1000){
                // Print error & exit script
                echo 'Answer length exceeded 1000 characters, please try again';
                exit();
              }
              // Set possible answer value to null
              $possible_answer = null;
            }else if($answer_type == 'dropdown'){
              // Trims white space and stores user input as variable
              $possible_answer = trim(reset($answer));
              // Set text answer value to null
              $answer_value = null;
            }else if($answer_type == 'check'){
              // Reindex array to start at 0
              $all_answers = array_combine(range(0, count($answer['check'])-1), array_values($answer['check']));
              // Set value to input in database
              $possible_answer = $all_answers[$i];
              // Set text answer value to null
              $answer_value = null;
            }
            // Query
            // Answer data is inserted into the database
            $sql = 'INSERT INTO answer (form_id, question_id, answer_value, possible_answer_id) VALUES (:form_id, :question_id, :answer_value, :possible_answer_id)';
      
            // Prepare and execute statement
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['form_id' => $form_id, 'question_id' => $key, 'answer_value' => $answer_value, 'possible_answer_id' => $possible_answer]);
          }
        }

      }
    // If there's no error, redirect user to the next form category
    if(!$error){
      redirect('form.php?id=' . $form_id . '&c=' . ($_GET['c'] + 1));
    }
  }
    
  }

?>

<div class="container" id="main">

  <!-- If an error is sent it is displayed -->
  <?php if($error != null): ?>
    <h3 class='error'><?php echo $error; ?></h3>
  <?php endif; ?>
  
  <form method="post" action="form.php?id=<?php echo $_GET['id'] . '&c=' . $category ?>">
    <input type="hidden" name="form_id" value="<?php echo $_GET['id'] ?>">

    <?php
    // Output form questions with inputs
      if($questions){
        echo '<h3>' . $questions[0]->category_name . '</h3>';
        echo '<strong>Please fill in all information</strong>';
        foreach($questions as $question){
          // INPUT
          if($question->question_type == 'input'){
            echo '<div class="form-group">';
            echo '<label>' . $question->question_text . '</label>';
            echo '<input type="text" class="form-control" name="' . $question->question_id . '[' . $question->question_type . ']">';
            echo '</div>';
          // DROPDOWN
          }else if($question->question_type == 'dropdown'){
            echo '<div class="form-group">';
            echo '<label>' . $question->question_text . '</label>';
            echo '<select class="form-control" name="' . $question->question_id . '[' . $question->question_type . ']">';
            $answers = getAnswers($pdo, $question->question_id);
            if($answers){
              foreach($answers as $answer){
                echo '<option value="' . $answer->possible_answer_id . '">' . $answer->possible_answer_value . '</option>';
              }
            }
            echo '</select>';
            echo '</div>';
          // CHECK BOXES
          }else if($question->question_type == 'check'){
            echo '<div class="form-group">';
            echo '<label>' . $question->question_text . '</label>';
            $answers = getAnswers($pdo, $question->question_id);
            if($answers){
              foreach($answers as $answer){
                echo '<div class="form-check">';
                echo '<input class="form-check-input" type="checkbox" name="' . $question->question_id . '[' . $question->question_type . '][' . $answer->possible_answer_id . ']" value="' . $answer->possible_answer_id . '">';
                echo '<label class="form-check-label">' . $answer->possible_answer_value . '</label>';
                echo '</div>';
              }
            }
            echo '</div>';
          // TEXT AREA
          }else if($question->question_type == 'text'){
            echo '<div class="form-group">';
            echo '<label>' . $question->question_text . '</label>';
            echo '<textarea class="form-control" cols="30" rows="10" name="' . $question->question_id . '[' . $question->question_type . ']"></textarea>';
            echo '</div>';
          }
        }
      }else{
        redirect('dashboard.php');
        exit();
      }
    ?>
    <button type="submit" class="">Next Step</button>
    </br>
    </br>
  </form>

</div>

<?php
// Added footer to bottom of page
include('includes/footer.php');
?>