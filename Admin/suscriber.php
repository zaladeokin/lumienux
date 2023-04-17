<?php
require_once('config/autoload.php');

//JS API CALL to change suscriber settings
if(isset($_POST['key']) && isset($_POST['value'])){

  header('Content-Type: application/json; charset=utf-8');
  
  $status= $suscriber->set_suscriber_config($_POST['key'], $_POST['value']);
  if($status){
    echo json_encode(array('status' => 'success'));
  }else{
    echo json_encode(array('status' => 'failed'));
  }

  return;

}

//JS API Call to view all suscriber
if(isset($_GET['view']) && $_GET['view'] == 'all'){
  
  header('Content-Type: application/json; charset=utf-8');

  $resp= $suscriber->load_suscribers();
  echo $resp;
  return;
}


//Send custom message to Suscriber
if(isset($_POST['message'])){
  $suscriber->send_custom_mail($_POST['message']);
  return;
}

?>


<?php
//View
include_once('include/header.php');
$upload_config= ($suscriber->get_suscriber_config(1) !== false && $suscriber->get_suscriber_config(1) != 0) ? "Disable" : "Enable";
$edit_config= ($suscriber->get_suscriber_config(2) !== false && $suscriber->get_suscriber_config(2) != 0) ? "Disable" : "Enable";
?>

<section>
  <h1>Admin Panel</h1>

  <Fieldset id="settings">
    <label><strong>Send message to suscriber when new product is posted</strong>&nbsp;&nbsp; <input type="button" data-type="1" value="<?= $upload_config; ?>"></label>
    <label><strong>Send message to suscriber when new product is edited</strong>&nbsp;&nbsp; <input type="button"  data-type="2" value="<?= $edit_config; ?>"></label>
</Fieldset><br><br>

  <form method="POST">
    <fieldset style="border: 1px outset #000000;padding:1vh 1vw; border-radius:1vh 1vw;">
        <legend style="border-bottom:1px solid #000000;">Send mail to suscribers</legend>
        <span></span>
        <label>Content&nbsp;&nbsp;<textarea name="message" placeholder="Type content here."></textarea></label>
        <input type="submit" value="Send">
    </fieldset>
  </form>
  <button style="display:block;width:fit-content;margin:5vh auto;">View all suscriber</button>
</section>
<section style="display:none; border-radius=unset; margin:5vh 0" id="suscribers"></section>
<script src="<?= _DOMAIN_; ?>/js/suscriber.js"></script>

<?php include_once('include/footer.php'); ?>