<?php
include_once('includes/header.php');

// Redirect the user if they are not logged in as staff
if (!isset($_SESSION['staff'])) {
  redirect('login.php');
  exit();
}

function uploadImage($file)
{
  // Set directory
  $dir = "images/";
  // Find file extension by seperating filename on the .
  $extension = explode(".", $_FILES["file"]["name"]);
  // Creates file name (images/user_id + timestamp + .extension)
  $file_name = $_SESSION['user_id'] . microtime() . '.' . $extension[1];
  // Add directory to file name
  $file_dir = $dir . $file_name;
  // Default whether file should be uploaded to true
  $upload = true;

  // Check if file is an image
  if (!getimagesize($file['tmp_name'])) {
    echo "File is not an image.</br>";
    $upload = false;
  }

  // Check if file already exists
  if (file_exists($file_name)) {
    echo "File already exists.</br>";
    $upload = false;
  }

  // Check if file size is greated than 20mb
  if ($file['size'] > 20971520) {
    echo "File is too big.</br>";
    $upload = false;
  }

  // If upload has been set to false
  if (!$upload) {
    // Print error
    echo 'File could not be uploaded.</br>';
  } else {
    // If upload is true upload file
    if (move_uploaded_file($file['tmp_name'], $file_dir)) {
      // If successful print message
      echo 'File uploaded.</br>';
      // Return file name
      return $file_name;
    } else {
      // If unsuccessful print error
      echo 'Error uploading file.</br>';
    }
  }
}

// Checks if the form has been submitted
if (isset($_POST) && !empty($_POST)) {
  // If no file was uploaded
  if (!isset($_FILES['file'])) {
    // Print error
    $error = "Please upload file";
  } else {
    // Check if description length exceeds limit
    if(strlen($_POST['description']) > 1000){
      // Print error & exit script
      echo 'Description length exceeded 1000 characters, please try again';
      exit();
    }

    // Upload the image file & recieve the image file name
    $file_name = uploadImage($_FILES['file']);

    // Trims white space and stores user inputs as variables to be used later
    $name = trim($_POST['name']);
    $gender = trim($_POST['gender']);
    $age = trim($_POST['age']);
    $type = trim($_POST['type']);
    $breed = trim($_POST['breed']);
    $description = trim($_POST['description']);
    $pet_image =  $file_name;

    // Checks if inputs are empty, if so sends an error
    if (empty($name) || empty($age) || empty($type) || empty($breed) || empty($description)) {
      $error = "Please fill in all information";
    } else {
      // Store current date & time in SQL format
      $created = date('Y-m-d H:i:s');

      // Query
      // Pet data is inserted into the database
      $sql = 'INSERT INTO pet (pet_type_id, pet_breed_id, pet_name, pet_age, pet_gender, pet_image, pet_description) VALUES (:pet_type_id, :pet_breed_id, :pet_name, :pet_age, :pet_gender, :pet_image, :pet_description)';

      // Prepare and execute statement
      $stmt = $pdo->prepare($sql);
      $stmt->execute(['pet_type_id' => $type, 'pet_breed_id' => $breed, 'pet_name' => $name, 'pet_age' => $age, 'pet_gender' => $gender, 'pet_image' => $pet_image, 'pet_description' => $description]);

      // Redirects user to pets page
      redirect('pets.php');
    }
  }
}
?>

<form method="post" action="admin_area.php?view=addPet" enctype="multipart/form-data">
  <h4>Please fill in all information (including uploading an image)</h4>
  <div class="form-group">
    <label for="file">Select image to upload (must be under 20Mb):</label>
    </br>
    <input type="file" class="" name="file" id="file">
  </div>
  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name">
  </div>
  <div class="form-group">
    <label for="breed">Gender</label>
    <select class="form-control" id="gender" name="gender">
      <option value="0">Male</option>
      <option value="1">Female</option>
    </select>
  </div>
  <div class="form-group">
    <label for="age">Age (years)</label>
    <input type="text" class="form-control" id="age" name="age">
  </div>
  <div class="form-group">
    <label for="type">Type</label> <a href="admin_area.php?view=addTypeBreed">(Add type)</a>
    <select class="form-control" id="type" name="type">
      <?php
      // Query
      // Pulls all pet type data
      $sql = 'SELECT *
              FROM pet_type';

      // Prepare and execute statement
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      // Saves all results in object
      $types = $stmt->fetchAll();

      // If any types were found
      if ($types) {
        // Loop through each type
        foreach ($types as $type) {
          // Create an option element with a value of the pet type id and text of the pet type name (e.g. Dog)
          echo '<option value="' . $type->pet_type_id . '">' . $type->pet_type_name . '</option>';
        }
      }
      ?>
    </select>
  </div>
  <div class="form-group">
    <label for="breed">Breed</label> <a href="admin_area.php?view=addTypeBreed">(Add breed)</a>
    <select class="form-control" id="breed" name="breed">
      <?php
      // Query
      // Pulls all pet breed data 
      $sql = 'SELECT *
              FROM pet_breed';

      // Prepare and execute statement
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      // Saves all results in object
      $breeds = $stmt->fetchAll();

      // If any breeds were found
      if ($breeds) {
        // Loop through all breeds
        foreach ($breeds as $breed) {
          // Create an option element with a value of the pet breed id and text of the pet breed name (e.g. Labrador)
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