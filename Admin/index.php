<?php
require_once('config/autoload.php');


?>



<?php
//View
include_once('include/header.php');
?>

<section>
  <h1>Admin Portal</h1>
    <form>
        <fieldset>
            <legend>
                Login to proceed.
            </legend>
            <label>Email&nbsp;<input type="email"></label>
            <label>Email&nbsp;<input type="password"></label>
            <input type="submit" value="Submit"
        </fieldset>
    </form>
  </section>

<?php   include_once('include/footer.php'); ?>