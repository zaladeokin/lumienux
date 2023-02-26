<?php
require_once('config/autoload.php');

//Logout user
if(isset($_SESSION['Admin'])){
    unset($_SESSION['Admin']);
}

if(isset($_POST['password']) && isset($_POST['id'])){
    if($_POST['password'] != "" && $_POST['id'] !=""){
        $admin->create_password($_POST['password'], $_POST['id']);
        return;
    }else{
        $_SESSION['info'] = "<div id='info'>Invalid password, Try again.</div>";
        $admin->query("DELETE FROM admin WHERE email='"._ADMIN_."'");//remove user from admin so that it can reset password again.
        header("Location: index.php");
        return;
    }
}


//Token verification
if(isset($_GET['token']) && isset($_GET['id'])){
    $token= $_GET['token'] != "" ? $_GET['token'] : false;
    $id= $_GET['id'] != "" ? $_GET['id'] : false;
    if($token && $id){
      $ver=  $admin->token_verification($token, $id);
    }
}else{
    $ver= false;
}

?>



<?php
//View

if($ver){
include_once('include/header.php');
?>

<section>
  <h1>Admin Portal</h1>
    <form method="POST">
        <fieldset>
            <legend>
                Create Password.
            </legend>
            <label class="zl-form-info" <?= FormFlashMsg('email_err'); ?>>Password&nbsp;<input type="password" name="password"></label>
            <input type="hidden" name="id" value="<?= $id; ?>">
            <input type="submit" value="Create Password">
        </fieldset>
    </form>
  </section>

<?php   include_once('include/footer.php');

}elseif($token){
    return;//Enable it to display error message from token_verification();
}else{
    $_SESSION['info'] = "<div id='info'>Access Denied.</div>";
    header("Location: index.php");
    return;
}
?>