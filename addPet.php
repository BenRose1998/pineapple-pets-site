<?php
include_once('includes/header.php');

// Redirect the user if they are not logged in as staff
if (!isset($_SESSION['staff'])) {
  redirect('login.php');
  exit();
}

function uploadImage($file)
{
  $dir = "images/";
  $file_name = $dir . basename($file['name']);
  $upload = true;
  $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

  // Check if file is an image
  if (!getimagesize($file['tmp_name'])) {
    echo "File is not an image.</br>";
    $uploadOk = false;
  }

  // Check if file already exists
  if (file_exists($file_name)) {
    echo "File already exists.</br>";
    $upload = false;
  }

  // Check file size
  if ($file['size'] > 20971520) {
    echo "File is too big.</br>";
    $upload = false;
  }

  if (!$upload) {
    echo 'File could not be uploaded.</br>';
  } else {
    if (move_uploaded_file($file['tmp_name'], $file_name)) {
      echo 'File uploaded.</br>';
    } else {
      echo 'Error uploading file.</br>';
    }
  }
}

// Checks if the form has been submitted
if (isset($_POST) && !empty($_POST)) {
  if (!isset($_FILES['file'])) {
    $error = "Please upload file";
  } else {
    uploadImage($_FILES['file']);
    // Trims white space and stores user inputs as variables to be used later
    $name = trim($_POST['name']);
    $age = trim($_POST['age']);
    $type = trim($_POST['type']);
    $breed = trim($_POST['breed']);
    $description = trim($_POST['description']);
    $pet_image = $_FILES['file']['name'];
    // Checks if inputs are empty, if so sends an error
    if (empty($name) || empty($age) || empty($type) || empty($breed) || empty($description)) {
      $error = "Please fill in all information";
    } else {
      $created = date('Y-m-d H:i:s');
      // Query
      // Pet data is inserted into the database
      $sql = 'INSERT INTO pet (pet_type_id, pet_breed_id, pet_name, pet_age, pet_image, pet_description) VALUES (:pet_type_id, :pet_breed_id, :pet_name, :pet_age, :pet_image, :pet_description)';

      // Prepare and execute statement
      $stmt = $pdo->prepare($sql);
      $stmt->execute(['pet_type_id' => $type, 'pet_breed_id' => $breed, 'pet_name' => $name, 'pet_age' => $age, 'pet_image' => $pet_image, 'pet_description' => $description]);

      // Redirects user to login page
      redirect('pets.php');
    }
  }
}

?>

<form method="post" action="admin_area.php?view=addPet" enctype="multipart/form-data">
  <div class="form-group">
    <label for="file">Select image to upload (must be under 20Mb & must have a unique name):</label>
    </br>
    <input type="file" class="" name="file" id="file">
  </div>
  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name">
  </div>
  <div class="form-group">
    <label for="age">Age (years)</label>
    <input type="text" class="form-control" id="age" name="age">
  </div>
  <div class="form-group">
    <label for="type">Type</label>
    <select class="form-control" id="type" name="type">
      <?php
      // Query
      // Pulls records from 
      $sql = 'SELECT *
        FROM pet_type';

      // Prepare and execute statement
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      // Saves all results in object
      $types = $stmt->fetchAll();

      if ($types) {
        foreach ($types as $type) {
          echo '<option value="' . $type->pet_type_id . '">' . $type->pet_type_name . '</option>';
        }
      }
      ?>
    </select>
  </div>
  <div class="form-group">
    <label for="breed">Breed</label>
    <select class="form-control" id="breed" name="breed">
      <?php
      // Query
      // Pulls records from 
      $sql = 'SELECT *
        FROM pet_breed';

      // Prepare and execute statement
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      // Saves all results in object
      $breeds = $stmt->fetchAll();

      if ($breeds) {
        foreach ($breeds as $breed) {
          echo '<option value="' . $breed->pet_breed_id . '">' . $breed->pet_breed_name . '</option>';
        }
      }
      ?>
    </select>
  </div>
  <div class="form-group">
    <label for="description">Description (max characters: 1000)</label>
    <textarea class="form-control" id="description" name="description" rows="5"></textarea>
  </div>
  <button type="submit" class="">Add Pet</button>
</form>