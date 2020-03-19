<?php
require_once('includes/connect.php');
$header = "Home Page";
// Added header to top of page
include('includes/header.php');

function checkEmailExists($pdo, $email)
{
  // Query
  $sql = 'SELECT *
          FROM user
          WHERE user_email = ?';

  // Prepare and execute statement
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$email]);
  $accounts = $stmt->fetchAll();

  if (count($accounts) === 0) {
    return false;
  } else {
    return true;
  }
}

$error = null;

// Checks if the form has been submitted
if (isset($_POST) && !empty($_POST)) {
  // Trims white space and stores user inputs as variables to be used later
  $first_name = trim($_POST['first_name']);
  $last_name = trim($_POST['last_name']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $password2 = trim($_POST['password2']);
  // Checks if inputs are empty, if so sends an error
  if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($password2)) {
    $error = "Please fill in all information";
  } else {
    if (checkEmailExists($pdo, $email)) {
      $error = "Email already exists";
    } else {
      // Checks if passwords don't match and if so sends an error
      if ($password != $password2) {
        $error = "Passwords do not match";
      } else {
        // If inputs aren't empty and passwords match the user's data is inserted into the database
        // Encrypts password
        $password = password_hash($password, PASSWORD_DEFAULT);
        $created = date('Y-m-d H:i:s');
        // Query
        // User's data is inserted into the database
        $sql = 'INSERT INTO user (user_first_name, user_last_name, user_email, user_password, user_created) VALUES (:first_name, :last_name, :email, :password, :created)';

        // Prepare and execute statement
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'password' => $password, 'created' => $created]);

        // Redirects user to login page
        redirect('login.php');
      }
    }
  }
}

?>

<div class="container" id="main">

  <!-- If an error is sent it is displayed -->
  <?php if ($error != null) : ?>
    <h3 class='error'><?php echo $error; ?></h3>
  <?php endif; ?>

  <form class="form-signin" method="post" action="register.php">
    <h1 class="h3 mb-3 font-weight-normal text-center">Register</h1>
    <div class="form-group">
      <label for="first_name">First name</label>
      <input type="text" class="form-control" id="first_name" name="first_name">
    </div>
    <div class="form-group">
      <label for="last_name">Last name</label>
      <input type="text" class="form-control" id="last_name" name="last_name">
    </div>
    <div class="form-group">
      <label for="email">Email address</label>
      <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" class="form-control" id="password" name="password">
    </div>
    <div class="form-group">
      <label for="password2">Repeat Password</label>
      <input type="password" class="form-control" id="password2" name="password2">
    </div>
    <button type="submit" class="">Register</button>
  </form>
</div>


<?php
// Added footer to bottom of page
include('includes/footer.php');
?>