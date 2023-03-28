<?php
require_once('config/autoload.php');


//Js API for Adding state
if( isset($_POST['action']) && $_POST['action'] == 'add'){
    header('Content-Type: application/json; charset=utf-8');
    if( $deliveryFee->if_exist($_POST['state']) <= 0 ){
        $res= $deliveryFee->add_state($_POST);
        if($res){
            echo json_encode(array('status' => 'success'));
        }else{
            echo json_encode(array(
                'status' => 'failed',
                'msg' =>""
            ));
        }
    }else{
        echo json_encode(array(
            'status' => 'failed',
            'msg' => "$_POST[state] already added"
        ));
    }
    return;
}

//JS API call for Editing state
if(isset($_POST['action']) && $_POST['action'] == 'edit'){
    header('Content-Type: application/json; charset=utf-8');

    $valid= $deliveryFee->get_state($_POST['id']);
    if(!$valid){//check if state id exist
        echo json_encode(array(
            'status' => 'failed',
            'msg' =>"State code not in existence."
        ));
        return;
    }elseif(strtolower($_POST['state']) != strtolower($valid['state'])){//check if state name was edited and not in existence in Database
        $exist= ( $deliveryFee->if_exist($_POST['state']) > 0 ) ? true : false;
    }else{
        $exist= false;
    }

    if($exist){
        echo json_encode(array(
            'status' => 'failed',
            'msg' =>"State already exist."
        ));
        return;
    }

    $res= $deliveryFee->edit_state($_POST);
    if($res){
        echo json_encode(array(
            'status' => 'success',
            'msg' =>""
        ));
    }else{
         echo json_encode(array(
            'status' => 'failed',
            'msg' =>"Something went wrong, Try again."
        ));
    }
    return;

}

//JS API call for deleting state
if( isset($_POST['action']) && $_POST['action'] == 'delete'){
    header('Content-Type: application/json; charset=utf-8');
    $del= $deliveryFee->delete_state($_POST['id']);
    if($del){
        echo json_encode(array(
            'status' => 'success',
            'msg' =>""
        ));
    }else{
         echo json_encode(array(
            'status' => 'failed',
            'msg' =>"Failed, Try again."
        ));
    }
    return;

}

//JS API call to load all state
if(isset($_GET['load'])){
    header('Content-Type: application/json; charset=utf-8');
    echo $deliveryFee->load_state();
    return;
}
?>



<?php
//View
include_once('include/header.php');

$total_state= $deliveryFee->total_state();
?>
<section>
  <h1>Delivery Fee</h1>
  <form id="add">
    <fieldset style="border: 1px outset #000000;padding:1vh 1vw; border-radius:1vh 1vw;">
        <legend style="border-bottom:1px solid #000000;">Add State</legend>
        <span></span>
        <label>State&nbsp;&nbsp;<input type="text" id="state"></label>
        <label>Light-weighted price&nbsp;(&#8358;)&nbsp;<input type="number" id="light" min="0" placeholder="1000"></label>
        <label>Medium-weighted price&nbsp;(&#8358;)&nbsp;<input type="number" id="medium" min="0" placeholder="1000"></label>
        <label>High-weighted price&nbsp;(&#8358;)&nbsp;<input type="number" id="high" min="0" placeholder="1000"></label>
        <input type="submit" value="add">
    </fieldset>
  </form>
  <div class="table-responsive-sm">
  <?php if((intval($total_state) > 0)){ ?>
            <table class="table table-sm table-striped caption-top text-center">
                <caption>Delivery fee chart</caption>
                <thead class="table-dark">
                    <tr><th scope="col">s/n</th> <th scope="col">State</th> <th scope="col">Light-weight</th> <th scope="col">Medium-weight</th><th scope="col">High-weight</th><th scope="col"></th><th scope="col"></th></tr>
                </thead>
                <tbody>
<?php
    echo "</tbody></table></div></section>";
}else{
    echo "<p>Delivery fee have not been fix for any state.</P></div></section>";
}
?>
</section>

<script src="<?= _DOMAIN_; ?>/js/delFee.js"></script>
<?php   include_once('include/footer.php'); ?>