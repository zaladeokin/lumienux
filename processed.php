<?php
require_once('config/autoload.php');

function summary($order){
    $id = isset($_GET['tx_ref']) ? $_GET['tx_ref'] : false;

    if($id === false) return;

    $content= <<<_item
                <section class="product">
                    <h2 style="color:#008000;">Order Completed</h2>
                    <div><strong>Transaction ID:</strong> $id</div>
                    <p>You will recieve your order within 5 working days.</p>
                    <p>You order the following: </p>
                    <ul>
            _item;

    foreach($order as $i){
        $content .= "<li>$i</li>";
    }

    $domain= _DOMAIN_;
    $content .= <<<_item
                        </ul>
                        <p>Thanks for your patronage.</p>
                        <p> <strong>You might need our engineer for installing your new product</strong>, <a href="$domain/service.php">click here to hire an engineer</a></p>
                        <a href="$domain/product.php"><button>View more product</button></a><br><br>
                    </section>
                _item;
    return $content;
}

$success= false;

if(isset($_GET['status']) && isset($_SESSION['products_id'])){//$_SESSION['products_id'] help to prevent repeated action whenpage is reloaded because session would have been destroy on successful action.
    if( $_GET['status'] == 'successful' ){
        $trans_id= $_GET['transaction_id'];
        //Make GET request to Flutterwave for payment verification link
        $FLU= get("https://api.flutterwave.com/v3/transactions/$trans_id/verify", array(
            'Authorization: Bearer '._FLW_SECRET_KEY_,
            'Content-Type: application/json',
            'cache-control: no-cache'
        ));
        $response= json_decode($FLU);
        if($response->data->status == 'successful' && $_GET['tx_ref'] == $response->data->tx_ref){
            $amt_paid= $response->data->charged_amount;
            $products_id= json_decode($response->data->meta->product_id);
            $products_qty= json_decode($response->data->meta->qty);
            $products_price= json_decode($response->data->meta->product_price);
            $expected_amount= 0;
            for($i=0; $i< count($products_id); $i++){
                $expected_amount += intval($products_price[$i]) * intval($products_qty[$i]);//Sum product price
            }

            if($amt_paid >= $expected_amount && $response->data->currency == 'NGN'){
                //use $_SESSION['order_desc'] to send mail()
                $item= explode(", ", $_SESSION['order_desc']);
                //Input into Database
                $order->approve($response, $product, summary($item));
                //unset session
                session_destroy();
                //clear cart cookie
                setcookie('cart', "", time() - (60*60*24), "/");
                $success= true; //Display success message
            }else{//illegal payment (Fraud action)
                $_SESSION['info'] = "<div id='info'>Order can't be completed.</div>";
                header("Location: cart.php");
                return;
            }

        }else{
            $_SESSION['info'] = "<div id='info'>Transaction not successful.</div>";
            header("Location: cart.php");
            return;
        }

    }elseif( $_GET['status'] == 'cancelled' ){
        $_SESSION['info'] = "<div id='info'>Transaction cancelled.</div>";
        header("Location: cart.php");
        return;
    }
}else{
    header("Location: cart.php");
    return;
}

?>



<?php
//View
if($success){
    include_once('include/header.php');
    echo summary($item);
    include_once('include/footer.php');
}
?>