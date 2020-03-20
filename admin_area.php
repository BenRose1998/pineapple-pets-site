<?php
require_once('includes/connect.php');
$header = "My Account";
// Added header to top of page
include_once('includes/header.php');

// Redirect the user if they are not logged in as staff
if(!isset($_SESSION['staff'])){
  redirect('login.php');
  exit();
}

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
        <a class="nav-link" href="admin_area.php?view=users">Users</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_area.php?view=settings">Settings</a>
      </li>
    </ul>
    <div class="col-md-10" id="content">
      <?php
        // If table is requested
        if (isset($_GET['view'])){
          // Calls necessary function depending on which table is requested
          switch ($_GET['view']){
            case 'forms':
              displayForms($pdo);
              break;
            case 'form':
              if(isset($_GET['id'])){
                viewForm($pdo, $_GET['id']);
              }else{
                echo 'No form selected';
              }
              break;
            case 'editForm':
              if(isset($_GET['submit'])){
                updateForm($pdo);
              }
              editForm($pdo);
              break;
            case 'setFormStatus':
              setFormStatus($pdo, $_GET['id'], $_GET['status']);
              break;
            case 'users':
              if(isset($_GET['id'])){
                makeStaff($pdo, $_GET['id']);
              }
              displayUsers($pdo);
              break;
            case 'addPet':
              include_once('addPet.php');
              break;
            case 'finaliseAdoption':
              togglePet($pdo, $_GET['pet']);
              setFormStatus($pdo, $_GET['form'], 'Finalised');
              break;
            case 'pets':
              if(isset($_GET['id'])){
                togglePet($pdo, $_GET['id']);
              }
              displayPets($pdo);
              break;
            case 'settings':
              if(isset($_GET['toggleEmail'])){
                toggleEmail($pdo);
              }
              showEmailToggle($pdo);
              break;
            default:
              displayForms($pdo);
          }
        }else{
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