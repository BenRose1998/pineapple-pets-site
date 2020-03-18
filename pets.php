<?php
require_once('includes/connect.php');
$header = "Pets";
// Added header to top of page
include('includes/header.php');
?>

<div class="container" id="main">

  <?php
  $sql = 'SELECT pet_type_name
            FROM pet_type';

  // Prepare and execute statement
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  // Saves all results in object
  $types = $stmt->fetchAll();

  $array = (array) $types;
  ?>
  <form method="get" action="pets.php">
    <div class="form-group">
      <label for="view">Sort by type</label>
      <div class="row" id="search">
        <select class="col-8 col-md-2 form-control" id="view" name="view">
          <option value="%">All</option>
          <?php
          foreach ($types as $type) {
            echo '<option value="' . $type->pet_type_name . '">' . $type->pet_type_name . '</option>';
          }
          ?>
        </select>
        <button type="submit" class="col-3 col-md-1" id="go">Go</button>
      </div>
    </div>

  </form>

  <?php
  if (isset($_GET['view'])) {
    $sort = $_GET['view'];
  } else {
    $sort = '%';
  }

  // Query
  // Pulls records from pet table and appends data from relevant tables
  $sql = 'SELECT *
    FROM pet
    INNER JOIN pet_type ON pet.pet_type_id = pet_type.pet_type_id
    INNER JOIN pet_breed ON pet.pet_breed_id = pet_breed.pet_breed_id
    WHERE pet_type_name LIKE ?
    AND pet_active = true
    ORDER BY pet.pet_id DESC';

  // Prepare and execute statement
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$sort]);
  // Saves all results in object
  $pets = $stmt->fetchAll();

  if ($pets) {
    echo '<div class="pets">';
    foreach ($pets as $pet) {
  ?>
  <figure class="pet">
    <div class="card">
      <div class="card-body">
        <img class="pet" src='images/<?php echo $pet->pet_image ?>'>
        <div class="pets-description col-md-9">
          <a href='view_pet.php?pet=<?php echo $pet->pet_id ?>' class='no-underline'>
            <h2><?php echo $pet->pet_name ?></h2>
          </a>
          <h3><?php echo $pet->pet_breed_name ?> | <?php echo ($pet->pet_gender == 1 ? 'Male' : 'Female ') ?> |
            <?php echo $pet->pet_age ?> years old </h3>
          <h4><?php echo $pet->pet_description ?></h4>
        </div>
      </div>
    </div>
  </figure>

  <?php
    }
  }

  echo '</div>';

  ?>


</div>


<?php
// Added footer to bottom of page
include('includes/footer.php');
?>