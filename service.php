<?php
require_once('config/autoload.php');
?>


<?php
//View
include_once('include/header.php');
?>
  <section>
  <h1>Services</h1>
  <p>The following services are offered by our engineers:</p>
  <ul>
    <li>Solar  & Inverter installation</li>
    <li>Maintenenance</li>
    <li>Troubleshooting</li>
  </ul>
  <a href="mailto:<?= _ADMIN_; ?>"><button>Hire an Engineer now</button></a><br><br>
    
  </section>

  <?php
  include_once('include/footer.php');
  ?>