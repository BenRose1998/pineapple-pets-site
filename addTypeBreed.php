<?php
include_once('includes/header.php');

// Redirect the user if they are not logged in as staff
if (!isset($_SESSION['staff'])) {
  redirect('login.php');
  exit();
}

// Checks if the form has been submitted
if (isset($_POST) && !empty($_POST)) {
  // If type value was submitted do this, if breed was submitted so something else
  if(isset($_POST['type'])){
    // Trim the input and save in variable
    $type = trim($_POST['type']);

    // Check if value is empty, if so print error and exit script
    if(empty($type)){
      echo 'Please enter a type';
      exit();
    }

    // Check if input length exceeds DB length, if so print error and exit script
    if(strlen($type) > 20){
      echo 'Breed text too long';
      exit();
    }

    // Query
    // Pet data is inserted into the database
    $sql = 'INSERT INTO pet_type (pet_type_name) VALUES (?)';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$type]);

    echo 'Added Type';

  } else if(isset($_POST['breed'])){
    // Trim the input and save in variable
    $breed = trim($_POST['breed']);
    
    // Check if value is empty, if so print error and exit script
    if(empty($breed)){
      echo 'Please enter a breed';
      exit();
    }

    // Check if input length exceeds DB length, if so print error and exit script
    if(strlen($breed) > 20){
      echo 'Breed text too long';
      exit();
    }

    // Query
    // Pet data is inserted into the database
    $sql = 'INSERT INTO pet_breed (pet_breed_name) VALUES (?)';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    
    // If successful, print message
    if($stmt->execute([$breed])){
      echo 'Added Breed';
    }
  }
}

?>

<p>Please add one at a time</p>

<form method="post" action="admin_area.php?view=addTypeBreed" enctype="multipart/form-data">
  <div class="row">
    <div class="form-group col-11">
      <label for="name">Pet Type (e.g. Dog)</label>
      <input type="text" class="form-control" id="type" name="type">
    </div>
    <button type="submit" class="col-1">Add</button>
  </div>
</form>

</br>

<form method="post" action="admin_area.php?view=addTypeBreed" enctype="multipart/form-data">
  <div class="row">
    <div class="form-group col-11">
      <label for="name">Pet Type (e.g. Labrador)</label>
      <input type="text" class="form-control" id="breed" name="breed">
    </div>
    <button type="submit" class="col-1">Add</button>
  </div>
</form>