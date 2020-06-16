<?php
require_once('includes/connect.php');
$header = "Pets";
// Added header to top of page
include('includes/header.php');
?>

<div class="container" id="main">
  <?php
  // Query
  // Pulls all pet type values from the pet type table
  $sql = 'SELECT pet_type_name
            FROM pet_type';

  // Prepare and execute statement
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  // Saves all results in object
  $types = $stmt->fetchAll();
  ?>
  <form method="get" action="pets.php">
    <div class="form-group">
      <label for="view">Sort by type</label>
      <div class="row" id="search">
        <select class="col-8 col-md-2 form-control" id="view" name="view">
          <option value="%">All</option>
          <?php
          // Loop through all types
          foreach ($types as $type) {
            // Create an option element for each pet type (e.g. Dog)
            echo '<option value="' . $type->pet_type_name . '">' . $type->pet_type_name . '</option>';
          }
          ?>
        </select>
        <button type="submit" class="col-3 col-md-1" id="go">Go</button>
      </div>
    </div>

  </form>

  <?php
  // If a view has been requested
  if (isset($_GET['view'])) {
    // Set sort to the requested view
    $sort = $_GET['view'];
  } else {
    // Else set it to wildcard (Pets of any type will be displayed)
    $sort = '%';
  }

  // Query
  // Pulls records from pet table and appends data from relevant tables for only active pets
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

  // If any pets were returned from query
  if ($pets) {
    echo '<div class="pets">';
    // Loop through all pets - create a card composed of each pets information
    foreach ($pets as $pet) {
  ?>
  <figure class="pet">
    <div class="card">
      <div class="card-body">
        <img class="pet" src='images/<?php echo $pet->pet_image ?>' aria-labelledby="<?php echo $pet->pet_id ?>" alt="<?php echo $pet->pet_name ?>">
        <div class="pets-description col-md-9">
          <a href='view_pet.php?pet=<?php echo $pet->pet_id ?>' class='no-underline'>
            <h2 id="<?php echo $pet->pet_id ?>"><?php echo $pet->pet_name ?></h2>
          </a>
          <h3><?php echo $pet->pet_breed_name ?> | <?php echo ($pet->pet_gender == 0 ? 'Male' : 'Female ') ?> |
            <?php echo $pet->pet_age ?> years old </h3>
          <p><?php echo $pet->pet_description ?></p>
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