<?php
require_once('includes/connect.php');
$header = "My Account";
// Added header to top of page
include('includes/header.php');

// Redirect the user if they are not logged in
if(!isset($_SESSION['user_id'])){
  redirect('index.php');
  exit();
}

// Retrieves all forms for the user that is logged in
function getForms($pdo){
  // Store the user's id
  $user_id = $_SESSION['user_id'];

  // Query
  // Form data is pulled for the specified user, pet data is joined to each form
  $sql = 'SELECT form.form_id, form.form_created, form.form_status, pet.pet_name
          FROM form
          INNER JOIN pet ON form.pet_id = pet.pet_id
          WHERE user_id = ?';

  // Prepare and execute statement
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$user_id]);
  // Saves result in object
  $forms = $stmt->fetchAll();

  // If any forms were returned from the query a HTML table is rendered containing the form data
  if($forms){
    ?>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th>Pet Name</th>
      <th>Form Creation Date</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach($forms as $form){
      echo "<tr>";
      echo "<td>". $form->pet_name ."</td>";
      echo "<td>". $form->form_created ."</td>";
      echo "<td>". $form->form_status ."</td>";
      echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
  } else{
    // If not forms were returned a message is displayed
    echo '<h3>No adoption forms</h3>';
  }
}
?>

    <div class="container" id="main">
      <h2>My Account (<?php echo $_SESSION['first_name'] ?>)</h2>
      <div class="row">
        <ul class="col-md-2 nav flex-column nav-pills">
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php?view=forms">My Forms</a>
          </li>
        </ul>
        <div class="col-md-10" id="content">
          <?php
        // If a view is requested
        if (isset($_GET['view'])){
          // Calls necessary function depending on which view is requested
          switch ($_GET['view']){
            case 'forms':
              // Call the get forms function
              getForms($pdo);
              break;
            default:
              // If no other cases are matched
              // Call the get forms function
              getForms($pdo);
          }
        }else{
          // If no view was specified
          // Call the get forms function
          getForms($pdo);
        }
      ?>
        </div>
      </div>

      <?php
// Added footer to bottom of page
include('includes/footer.php');
?>