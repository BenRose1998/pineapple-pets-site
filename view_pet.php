<?php
require_once('includes/connect.php');

$header = "View Pet";
// Added header to top of page
include_once('includes/header.php');

$error = null;

?>

<div class="container" id="main">

  <?php
  // If an error is sent it is displayed 
  if($error != null){
    echo '<h3 class="error">' . $error . '</h3>';
  }

// If pet id has been sent
if(isset($_GET['pet'])){
  // Query
  // Pulls pet information from pet table and pet_breed table for the specified pet
  $sql = "SELECT *
          FROM pet
          INNER JOIN pet_breed ON pet.pet_breed_id = pet_breed.pet_breed_id
          WHERE pet.pet_id = ?
          LIMIT 1";

  // Prepare and execute statement
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$_GET['pet']]);
  // Saves all results in object
  $pet = $stmt->fetch();

  // If a pet was found under specified id - data is rendered as HTML elements on page
  if($pet){
    ?>

  <div class="view-pet">
    <h1 id="pet-name"><?php echo $pet->pet_name ?></h1>
    <img src="images/<?php echo $pet->pet_image ?>" aria-labelledby="pet-name" alt="<?php echo $pet->pet_name ?>">
    <div class="pet-description">
      <h2><?php echo $pet->pet_breed_name ?> | <?php echo ($pet->pet_gender == 0 ? 'Male' : 'Female ') ?> |
        <?php echo $pet->pet_age ?> years old </h2>
      <p><?php echo $pet->pet_description ?></p>
      <a href='create_form.php?pet=<?php echo $pet->pet_id?>'><button type='button'>Fill in adoption request
          form</button></a>
    </div>
  </div>

  <?php
  }else{
    // If not pet returned, error is set
    $error = 'Pet not found';
  }

}else{
  // If not pet id was sent, error is set
  $error = 'No pet selected';
}

?>
</div>

<?php
// Added footer to bottom of page
include('includes/footer.php');
?>