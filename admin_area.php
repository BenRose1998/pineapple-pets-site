<?php
require_once('includes/connect.php');
$header = "Admin Area";
// Added header to top of page
include_once('includes/header.php');

// Redirect the user if they are not logged in as staff
if(!isset($_SESSION['staff'])){
  redirect('login.php');
  exit();
}

// Include admin functions file which contains functions required by this page
include_once('includes/admin_functions.php');

?>

<div class="container" id="main">
  <h2>Admin Area</h2>
  <div class="row">
    <ul class="col-md-2 nav flex-column nav-pills">
      <li class="nav-item">
        <a class="nav-link" href="admin_area.php?view=forms">Adoption Forms</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_area.php?view=editForm">Edit Form</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_area.php?view=pets">Pets</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_area.php?view=addPet">Add Pet</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_area.php?view=addTypeBreed">Add Type/Breed</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_area.php?view=users">Users</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_area.php?view=adopters">Adopters</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_area.php?view=settings">Settings</a>
      </li>
    </ul>
    <div class="col-md-10" id="content">
      <?php
        // If a view is requested
        if (isset($_GET['view'])){
          // Calls necessary function depending on which table is requested
          switch ($_GET['view']){
            case 'forms':
              // Call function to display adoption forms
              displayForms($pdo);
              break;
            case 'form':
              // If id has been sent call the view form function
              if(isset($_GET['id'])){
                viewForm($pdo, $_GET['id']);
              }else{
                // If id was not sent display message
                echo 'No form selected';
              }
              break;
            case 'editForm':
              // If id has been sent call the delete question function
              if(isset($_GET['id'])){
                deleteQuestion($pdo, $_GET['id']);
              }
              // If a form was submitted call the update form function
              if(isset($_GET['submit'])){
                updateForm($pdo);
              }
              // Call the edit form function
              editForm($pdo);
              break;
            case 'setFormStatus':
              // Call the set form status function, pass it id and status values
              setFormStatus($pdo, $_GET['id'], $_GET['status']);
              break;
            case 'users':
              // If id has been sent call the make staff function
              if(isset($_GET['id'])){
                makeStaff($pdo, $_GET['id']);
              }
              // Call the display users function
              displayUsers($pdo);
              break;
            case 'adopters':
              displayAdopters($pdo);
              break;
            case 'addPet':
              // Include the add pet page
              include_once('addPet.php');
              break;
            case 'addTypeBreed':
              // Include the add type page
              include_once('addTypeBreed.php');
              break;
            case 'finaliseAdoption':
              // Call the toggle pet function, pass it a pet id
              togglePet($pdo, $_GET['pet']);
              // Call the set form status function, pass it a form id and a status text string
              setFormStatus($pdo, $_GET['form'], 'Finalised');
              break;
            case 'pets':
              // If id has been sent call the toggle pet function
              if(isset($_GET['id'])){
                togglePet($pdo, $_GET['id']);
              }
              // Call the display pets function
              displayPets($pdo);
              break;
            case 'settings':
              // If toggle email value has been sent call the toggle email function
              if(isset($_GET['toggleEmail'])){
                toggleEmail($pdo);
              }
              // Call the show email toggle function
              showEmailToggle($pdo);
              break;
            default:
              // If no other cases are matched
              // Call the display forms function
              displayForms($pdo);
          }
        }else{
          // If GET not set
          // Call the display forms function
          displayForms($pdo);
        }
      ?>
    
    </div>
  </div>
</div>

<?php
// Added footer to bottom of page
include('includes/footer.php');
?>