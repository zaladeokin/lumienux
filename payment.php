<?php
require_once('config/autoload.php');

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

/**
 * Note: state input field has not been included in validation, repopulation and Fluuter 'meta' key
 */

if( isset($_POST['customer_email']) ){
    $total= $_SESSION['total'];
    $desc= $_SESSION['order_desc'];
    $products_id= $_SESSION['products_id'];
    $products_qty= $_SESSION['products_qty'];
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
            'address'=> $_POST['customer_addr'],
            'phone_number'=> $_POST['customer_phone']
        );
        $customization= array(
            'title'=> 'Luminux solar product',
            'logo'=> 'http://localhost/lumienux/img/brand_logo.png',
            'description'=> $desc
        );
    
        $request=[
            'tx_ref' => bin2hex( random_bytes(4) )."-".time(),
            'amount' => $total,
            'currency' => 'NGN',
            'payment_options' => 'card',
            'redirect_url' => "http://localhost/lumienux/processed.php",
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
    $desc= json_decode($_POST['order_desc']);
    $total= 0;
    $order_desc= [];
    availableQty($products_id, $products_qty, $product);
    for($i=0; $i< count($products_id); $i++){
        $item= $product->get_product(intval($products_id[$i]));
        $total += intval($item['price']) * intval($products_qty[$i]);//Sum product price
        $order_desc[$i]= $desc[$i]."(".$products_qty[$i].")";//Create desription for payment
    }
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

//repopulate form for newsletter field
$name= repopulate('customer_name');
$email= repopulate('customer_email');
$phone= repopulate('customer_phone');
$addr= repopulate('customer_addr');

$total= $_SESSION['total'];
?>
<section>
<h1>Payment</h1>
<form method="POST" novalidate>
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
    <label>State &nbsp;&nbsp;<select name="state"></select></label>
    <strong>Delivery fee</strong>&nbsp;<i class="fa-sharp fa-solid fa-right-long"></i>&nbsp;&#8358;<br>
    <strong>Total amount&nbsp;<i class="fa-sharp fa-solid fa-right-long"></i>&nbsp;&#8358;<?= $total; ?></strong><br>
    <input type="submit" value="Make payment">
    </fieldset>
</form>
<br>
</section>
<?php
include_once('include/footer.php');
}
?>