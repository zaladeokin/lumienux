<?php
require_once('config/autoload.php');


//JS API for estimating Delivery Fee
if(isset($_POST['action']) && $_POST['action'] == 'evaluate'){
    header('Content-Type: application/json; charset=utf-8');
    $est= $deliveryFee->calculate_charges(explode(",", $_POST['id']), explode(",", $_POST['qty']), $_POST['state'], $product);
    echo $est;
    return;
}

function availableQty($id, $qty, $db){
    $products_id= $id;// use json_decode in if( isset($_POST['customer_email']) )
    $products_qty= $qty;// use json_decode in if( isset($_POST['customer_email']) )
    for($i=0; $i< count($products_id); $i++){
        $item= $db->get_product(intval($products_id[$i]));
        //verify if quantity is not greater than available stock
        $stock_avail_qty= intval($item['stock']) - intval($item['sold_product']);
        if(intval($products_qty[$i]) > $stock_avail_qty){
            $_SESSION['info'] = "<div id='info'>$item[name] is limited, $stock_avail_qty remaining.</div>";
            header("Location: cart.php");
            die();
        }
    }
}

//Get reCaptcha response
$token= isset($_POST['v-token']) ? $_POST['v-token'] : false;
$score=false;
//verify reCaptcha response
if($token != false){
    $reCaptcha= reCaptchaVerify(_V3_SECRET_KEY_, $token);
    $reCapVal= $reCaptcha->success;
    if($reCapVal){
        $score= $reCaptcha->score;
    }
}

if($score !== false && $score <= 0.8) $_SESSION['info'] = "<div id='info'>Human verification failed, Try again.</div>";

if( isset($_POST['customer_email']) && $score > 0.8){
    $desc= $_SESSION['order_desc'];
    $products_id= $_SESSION['products_id'];
    $products_qty= $_SESSION['products_qty'];
    $products_price= $_SESSION['products_price'];
    $products_name= $_SESSION['products_name'];
    //Calculate delivery fee
    $del_fee= $deliveryFee->calculate_charges($_SESSION['products_id'], $_SESSION['products_qty'], $_POST['state'], $product);
    $total= intval($_SESSION['total']) + $del_fee;
    $valid= $order->validate($_POST);//Validate customer credientials for payment
    if($valid){
        $customer= array(
            'email'=>   $_POST['customer_email'],
            'phone_number'=> $_POST['customer_phone'],
            'name'=>    $_POST['customer_name']
        );
        $meta= array(
            'product_id'=> json_encode($products_id),
            'qty'=> json_encode($products_qty),
            'product_price'=> json_encode($products_price),
            'product_name'=> json_encode($products_name),
            'address'=> $_POST['customer_addr'],
            'delivery_fee' => $del_fee,
            'state_info' => json_encode($deliveryFee->get_state($_POST['state'])),
            'phone_number'=> $_POST['customer_phone']
        );
        $customization= array(
            'title'=> 'Luminux solar product',
            'logo'=> _DOMAIN_.'/img/brand_logo.png',
            'description'=> $desc
        );
    
        $request=[
            'tx_ref' => bin2hex( random_bytes(4) )."-".time(),
            'amount' => $total,
            'currency' => 'NGN',
            'payment_options' => 'card',
            'redirect_url' => _DOMAIN_."/processed.php",
            'meta' => $meta,
            'customer' => $customer,
            'customizations' => $customization
        ];
        //Make POST request to Flutterwave for payment link
        $FLU= post('https://api.flutterwave.com/v3/payments', $request, array(
            'Authorization: Bearer '._FLW_SECRET_KEY_,
            'Content-Type: application/json',
            'cache-control: no-cache'
        ));
        $response= json_decode($FLU);
        if($response->status == 'success'){
            availableQty($products_id, $products_qty, $product);//Check if quantity is available
            header("Location: ".$response->data->link);
            return;
        }else{
            echo "<div id='info'>Can't proceed with payment</div>";
        }
    }
    header("Location: payment.php");
    return;
}elseif( isset($_POST['products_id']) ){
    $products_id= $_SESSION['products_id']= json_decode($_POST['products_id']);
    $products_qty= $_SESSION['products_qty']= json_decode($_POST['products_qty']);
    $products_price= [];
    $products_name= [];
    $desc= json_decode($_POST['order_desc']);
    $total= 0;
    $order_desc= [];
    availableQty($products_id, $products_qty, $product);
    for($i=0; $i< count($products_id); $i++){
        $item= $product->get_product(intval($products_id[$i]));
        $products_price[]= $item['price'];
        $products_name[]= $item['name'];
        $total += intval($item['price']) * intval($products_qty[$i]);//Sum product price
        $order_desc[$i]= $desc[$i]."(".$products_qty[$i].")";//Create desription for payment
    }
    $_SESSION['products_price']= $products_price;
    $_SESSION['products_name']= $products_name;
    $_SESSION['order_desc']= implode(", ", $order_desc);
    $_SESSION['total']= $total;

    header("Location: payment.php");
    return;
}


?>

<?php
//View
if(isset($_SESSION['products_id'])){
include_once('include/header.php');

//Load available state..
$states= json_decode($deliveryFee->load_state());

//repopulate form for newsletter field
$name= repopulate('customer_name');
$email= repopulate('customer_email');
$phone= repopulate('customer_phone');
$addr= repopulate('customer_addr');
$state_id= repopulate('state');

function sst($id, $st){
    $st= ($st == "") ? '0' : $st;
    $select= ($id == $st) ? 'selected' : '';
    return $select;
  }

$total= $_SESSION['total'];
?>
<section>
<h1>Payment</h1>
<form method="POST">
    <input type="hidden" id="v-token" name="v-token">
    <fieldset>
        <legend style="font-size: 0.8em; color:red;">
        <ul>
            <li>Proceeding with the payment means you agreed to our <a href="">terms and conditions</a></li>
            <li>Your order will be deliver to the address that you will provide below.</li>
            <li>You will recieve your order within 5 working days.</li>
        </ul>
        </legend>
    <label>Name&nbsp;:&nbsp;<input type="text" name="customer_name" value="<?= $name; ?>"></label>
    <label>Email&nbsp;:&nbsp;<input type="email" name="customer_email" value="<?= $email; ?>"></label>
    <label>Phone&nbsp;:&nbsp;<input type="tel" name="customer_phone" placeholder="0xxxxxxxxxx" value="<?= $phone; ?>"></label>
    <label>Address&nbsp;:&nbsp;<input type="text" name="customer_addr" value="<?= $addr; ?>"></label>
    <label>State: &nbsp;&nbsp;<select name="state" id="state">
        <option value="0" <?= sst('0', $state_id) ?>>Select state</option>
        <?php 
        foreach($states as $state){
            echo "<option value='$state->id' ".sst($state->id, $state_id).">$state->state</option>";
        }
        ?>
    </select></label>
    <strong>Amount</strong>&nbsp;<i class="fa-sharp fa-solid fa-right-long"></i>&nbsp;&#8358;<span id="cost"><?= $total; ?></span><br>
    <strong>Delivery fee</strong>&nbsp;<i class="fa-sharp fa-solid fa-right-long"></i>&nbsp;&#8358;<span id="delivery_fee">0</span><br>
    <strong>Total</strong>&nbsp;<i class="fa-sharp fa-solid fa-right-long"></i>&nbsp;&#8358;<span id="tot_charges"><?= $total; ?></span><br>
    <input type="hidden" id="products_id" value="<?= implode(",", $_SESSION['products_id']); ?>"><input type="hidden" id="products_qty" value="<?= implode(",", $_SESSION['products_qty']); ?>">
    <input type="submit" id="submit" value="Make payment">
    </fieldset>
</form>
<br>
</section>
<script src="js/cal_del.js"></script>
<?php
include_once('include/footer.php');
}
?>