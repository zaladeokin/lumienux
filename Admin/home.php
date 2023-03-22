<?php
require_once('config/autoload.php');

?>





<?php
//View
include_once('include/header.php');
?>

<section>
  <h1>Admin Panel</h1>
  <ul>
    <li><a href="product.php">Manage Product</a></li>
    <li><a href="order.php">Orders</a></li>
    <li><a href="#">Newsletter Suscriber</a></li>
  </ul>
  </section>

<?php   
include_once('config/stat.php');
include_once('include/footer.php'); ?>