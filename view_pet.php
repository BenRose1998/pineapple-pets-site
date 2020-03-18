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

if(isset($_GET['pet'])){
  // Query
  // Pulls questions & appends data from relevant tables
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

  if($pet){
    ?>

    <div class="view-pet">
    <h1><?php echo $pet->pet_name ?></h1>
    <img src="images/<?php echo $pet->pet_image ?>" alt="">
      <div class="pet-description">
        <h2><?php echo $pet->pet_breed_name ?> | <?php echo ($pet->pet_gender == 1 ? 'Male' : 'Female ') ?> | <?php echo $pet->pet_age ?> years old </h2>
        <h3><?php echo $pet->pet_description ?></h3>
        <a href='create_form.php?pet=<?php echo $pet->pet_id?>'><button type='button'>Fill in adoption request form</button></a>
      </div>
    </div>

    <?php
  }else{
    $error = 'Pet not found';
  }

}else{
  $error = 'No pet selected';
}

?>




  
</div>


<?php
// Added footer to bottom of page
include('includes/footer.php');
?>