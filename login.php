<?php
require_once('includes/connect.php');
$header = "Log in";
// Added header to top of page
include('includes/header.php');

// Set error to null
$error = null;

// Check if the form has been submitted
if (isset($_POST) && !empty($_POST)) {
  // Store user inputs as variables to be used later
  $email = $_POST['email'];
  $password = $_POST['password'];
  // Check if inputs are empty, if so send an error
  if (empty($email) || empty($password)) {
    $error = "Please fill in all information";
  } else {
    // If inputs aren't empty the user's data is pulled from the database

    // Query
    // User's data is pulled from user table and their staff id from employee table if they are in it (LEFT JOIN used)
    $sql = 'SELECT user.*, staff.staff_id
            FROM user
            LEFT JOIN staff ON user.user_id = staff.user_id
            WHERE user_email = ?
            LIMIT 1';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    // Saves result in object
    $user = $stmt->fetch();

    // Only checks for password if a record is found in database
    if ($user) {
      // Inputted password is checked against password stored in database
      if (password_verify($password, $user->user_password)) {
        // If password is correct information on user is stored in the session
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['first_name'] = $user->user_first_name;
        $_SESSION['email'] = $email;
        // If user is a staff member (staff id was left-joined on to data)
        if ($user->staff_id) {
          // Staff stored in session
          $_SESSION['staff'] = true;
          // User is redirected to the admin area page
          redirect('admin_area.php?view=forms');
        } else {
          // If not staff, user is redirected to the home page
          redirect('index.php');
        }
      } else {
        // If the password inputted by user did not match then an error is sent
        $error = "Invalid email or password";
      }
    } else {
      // If no record found with that email then an error is sent
      $error = "Invalid email or password";
    }
  }
}

?>

<div class="container" id="main">
  <!-- If an error is sent it is displayed -->
  <?php if ($error != null) : ?>
    <h3 class='error'><?php echo $error; ?></h3>
  <?php endif; ?>


  <form action="login.php" method="post" class="form-signin text-center">
    <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
    <label for="inputEmail" class="sr-only">Email address</label>
    <input type="email" id="email" name="email" class="form-control" placeholder="Email address" required autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
    <button class="btn-block" type="submit" id="login-button">Login</button>
    <a href="register.php"><button type="button" class="btn-block">Register</button></a>
  </form>
</div>


<?php
// Added footer to bottom of page
include('includes/footer.php');
?>