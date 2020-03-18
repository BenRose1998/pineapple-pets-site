<?php
// Only starts a session if there isn't already an active session
if(session_status() != PHP_SESSION_ACTIVE){
  session_start();
}

// Function that can be used to redirect user using Javascript rather than manipulating the header
// Changes header location is not possible after outputting anything so javascript needs to be used
function redirect($url){ ?>
  <script type='text/javascript'>
  window.location.href = '<?php echo $url; ?>';
  </script>
<?php } ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Pineapple Pets | <?php echo $header ?></title>
  <!-- Bootstrap 4 - available at https://getbootstrap.com/ -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <!-- Font Awesome - available at https://fontawesome.com/ -->
  <script src="https://kit.fontawesome.com/f2a72a8b1b.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light navbar-custom">
  <div class="container" id="nav-content">
    <a class="navbar-brand" href="index.php">Pineapple Pet Sanctuary</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link nav-white" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-white" href="pets.php">Pets</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-white" href="about.php">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-white" href="contact.php">Contact</a>
        </li>
      </ul>
      <ul class="navbar-nav navbar-right">
        <?php
          if(isset($_SESSION['user_id'])){
            echo '<li class="nav-item">';
            echo  '<a class="nav-link nav-white" href="dashboard.php">My Account</a>';
            echo '</li>';

            if(isset($_SESSION['staff'])){
              echo '<li class="nav-item">';
              echo  '<a class="nav-link nav-white" href="admin_area.php?view=forms">Admin Area</a>';
              echo '</li>';
            }

            echo '<li class="nav-item">';
            echo  '<a class="nav-link nav-white" href="logout.php">Logout (' . $_SESSION['first_name'] . ')</a>';
            echo '</li>';

          } else{
            echo '<li class="nav-item">';
            echo  '<a class="nav-link nav-white" href="login.php">Login/Register</a>';
            echo '</li>';
          }
        ?>
      </ul>
    </div>
  </div>
  </div>
</nav>
