<?php
$header = "Contact";
// Added header to top of page
include('includes/header.php');
?>

<div class="container" id="main">
  <h1>Contact us</h1>
  <h5>For the fastest reply, please contact us through our facebook page.</h5>

  <div class="row" id="icons">
    <figure class="icon">
      <i class="fas fa-phone fa-7x" aria-labelledby="phone-title" alt="Phone icon"></i>
      <h2 id="phone-title">Phone</h2>
      <p>07799883853</p>
    </figure>
    <figure class="icon">
      <i class="fab fa-facebook fa-7x" aria-labelledby="facebook-title" alt="Facebook icon"></i>
      <h2 id="facebook-title">Facebook</h2>
      <p></p>
      <a href="https://www.facebook.com/pineapplepetsanctuary">
        <button type="button">Go to our page</button>
      </a>
    </figure>
    <figure class="icon">
      <i class="fas fas fa-envelope fa-7x" aria-labelledby="email-title" alt="Email icon"></i>
      <h2 id="email-title">Email</h2>
      <p>pineapplepetsanctuary@gmail.com</p>
    </figure>
  </div>
</div>


<?php
// Added footer to bottom of page
include('includes/footer.php');
?>