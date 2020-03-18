<?php
$header = "Home Page";
// Added header to top of page
include('includes/header.php');

?>
<div class="">
  <section id="bigimage">
    <div class="centre">
      <h1>Pineapple Pets</h1>
      <a href="pets.php"><button type="button" id="bigimageButton">View Pets</button></a>
    </div>
  </section>
</div>

<div class="container" id="main">
  <div class="row" id="icons">
    <figure class="icon">
      <i class="fas fa-dog fa-7x"></i>
      <h2>Pets</h2>
      <p></p>
      <a href="pets.php">
        <button type="button">View Pets</button>
      </a>
    </figure>
    <figure class="icon">
      <i class="fab fa-facebook fa-7x"></i>
      <h2>Facebook</h2>
      <p></p>
      <a href="https://www.facebook.com/pineapplepetsanctuary">
        <button type="button">Go to page</button>
      </a>
    </figure>
    <figure class="icon">
      <i class="fas fa-sign-in-alt fa-7x"></i>
      <h2>Register</h2>
      <p></p>
      <a href="login.php">
        <button type="button">Register</button>
      </a>
    </figure>
  </div>
  <!-- </div> -->


  <?php
  // Added footer to bottom of page
  include('includes/footer.php');
  ?>