<?php
require_once('config/autoload.php');

//Logout user
if(isset($_SESSION['Admin'])){
    unset($_SESSION['Admin']);
}


if(isset($_POST['email']) && isset($_POST['password'])){

    $email= filter_var($_POST['email'], FILTER_SANITIZE_STRING); $pass= $_POST['password'];

    if($email != ""){
        $e_val= true;
    }else{
        $_SESSION['email_err']= 'Invalid email.';
        $e_val= false;
    }

    if($pass != ""){
        $p_val= true;
    }else{
        $_SESSION['pass_err']= 'Enter password.';
        $p_val= false;
    }

    if($e_val && $p_val){
        $admin->Login($email, $pass);
        return;
    }

    header("Location: index.php");
    return;
}


?>



<?php
//View
include_once('include/header.php');
?>

<section>
  <h1>Admin Portal</h1>
    <form method="POST">
        <fieldset>
            <legend>
                Login to proceed.
            </legend>
            <label class="zl-form-info" <?= FormFlashMsg('email_err'); ?>>Email&nbsp;<input type="email" name="email"></label>
            <label class="zl-form-info" <?= FormFlashMsg('pass_err'); ?>>Password&nbsp;<input type="password" name="password"></label>
            <input type="submit" value="Submit">
        </fieldset>
    </form>
  </section>

<?php   include_once('include/footer.php'); ?>