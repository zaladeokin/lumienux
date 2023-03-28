<?php
require_once('config/autoload.php');

function view_temp($id, $order, $product){
    $get_order= $get_order= $order->get_order($id);

    if($get_order == false){
        echo "Order not exist";
        return;
    }

    $order_btn= ($get_order['status'] == "1") ? '<button style="float:right;" data-id="'.$id.'" data-action="delete">Delete Order</button>' : '<button style="float:right;" data-id="'.$id.'" data-action="complete">Mark as complete</button>';//Set button value to Complete or Delete
    
    $status= ($get_order['status'] == "1") ? "<span style='color:#008000;'>Completed</span>" : "<span style='color:#b09337;'>Pending</span>";

    $get_order= json_decode($get_order['payment_info']);
    $trans_id= $get_order->data->id;
    $tx_ref= $get_order->data->tx_ref;
    $trans_time= date('M j Y g:i:s A',strtotime($get_order->data->created_at));
    $amount= $get_order->data->charged_amount;
    //Card details
    $card_num= $get_order->data->card->first_6digits."******".$get_order->data->card->last_4digits;
    $card_type= $get_order->data->card->type;
    $card_exp= $get_order->data->card->expiry;
    //order Info
    $product_id= json_decode($get_order->data->meta->product_id);
    $qty= json_decode($get_order->data->meta->qty);
    $delivery_fee= $get_order->data->meta->delivery_fee;
    $state= json_decode($get_order->data->meta->state_info)->state;
    //Customer info
    $name= $get_order->data->customer->name;
    $phone= $get_order->data->meta->phone_number;
    $email= $get_order->data->customer->email;
    $address= $get_order->data->meta->address;

    echo <<<_view
        <h1>Order information</h1>
        <p>Ref/$tx_ref | $trans_time | status: $status</p>
        <strong>Customer information</strong>
        <ul>
            <li>Name: $name</li>
            <li>Phone: $phone</li>
            <li>Email: $email</li>
            <li>Address: $address</li>
            <li>State: $state</li>
        </ul>
        <div class="table-responsive-sm">
            <table class="table table-sm table-striped caption-top text-center">
                <caption><strong>Trans_id:</strong>$trans_id</caption>
                <thead class="table-dark">
                    <tr><th scope="col">S/n</th> <th scope="col">Product Name</th> <th scope="col">Price(&#8358;)</th> <th scope="col">Qty</th><th>Amount(&#8358;)</th></tr>
                </thead>
             <tbody>
        _view;
    for($i=0; $i < count($product_id); $i++){
            $sN= $i + 1;
            $item= $product->get_product($product_id[$i]);
            $item_price= $item['price'];
            $item_qty= $qty[$i];
            $tot= intval($item_price) * intval($item_qty);
            echo "<tr><td>$sN</td><td>$item[name]</td><td>$item_price</td><td>$item_qty</td><td>$tot</td></tr>";
    } 

    echo <<<_view
                </tbody>
                <tfoot>
                    <tr><td colspan="4">Delivery fee</td><td>$delivery_fee</td></tr>
                    <tr><td colspan="4">Total</td><td>$amount</td></tr>
                    <tr><td colspan="5">Card Details - $card_num ($card_exp) ($card_type)</td></tr>
                </tfoot>
                </table>
            </div>
            <a href="#$tx_ref"><button>Exist</button></a>$order_btn
        _view;
}

//Js API Call for view
if(isset($_POST['order_id'])){
    view_temp($_POST['order_id'], $order, $product);
    return;
}

//Js API Call for  Complete/Delete
if(isset($_POST['action']) && isset($_POST['active_id'])){
    $id= $_POST['active_id'];
    $action= $_POST['action'];
    header('Content-Type: application/json; charset=utf-8');
    $res= $order->action($id, $action);
    if($res){
        echo $res;
    }else{
        echo json_encode(array('message' => ""));
    }
    return;

}


//give template based on action
if(isset($_GET['action'])){
    $action= $_GET['action'];
}else{
    $action= false;
}

//________________________________________________________________________________

?>


<?php
//View
include_once('include/header.php');
?>
<section id="order_hide"></section>

<?php
//Pending & Completed Orders
if($action == 'pending' || $action == 'completed'){
    $order_status= ($action == 'pending') ? 'Pending' : 'Completed';
    $total_order= ($action == 'pending') ? $order->total_order('0') : $order->total_order('1');
    if(intval($total_order) > 0){ 
        $data= ($action == 'pending') ? $order->load_order('0') : $order->load_order('1');
    }
?>
<section>
  <h1>Order Management</h1>
  <?php if((intval($total_order) > 0)){ ?>
  <div class="table-responsive-sm">
            <table class="table table-sm table-striped caption-top text-center">
                <caption><?= $order_status; ?> Orders</caption>
                <thead class="table-dark">
                    <tr><th scope="col">Reference</th> <th scope="col">Email</th> <th scope="col">Amount</th> <th scope="col"></th></tr>
                </thead>
                <tbody>
<?php while($d= $data->fetch(PDO::FETCH_ASSOC)){ ?>
    <tr id="<?= $d['tx_ref']; ?>"><td><?= $d['tx_ref']; ?></td><td><?= $d['email']; ?></td><td><?= $d['amount']; ?></td><td><a href="#order_info"><button data-id="<?= $d['id']; ?>" class="view_order">View</button></a></td></tr>
<?php 
}
    echo "</tbody></table></section>";
    }else{
        echo "<p>No $order_status order.</P></section>";
    }
} ?>

<?php 
if(!$action){
    //Landing page
    ?>
  <section>
  <h1>Order Management</h1>
    <ul>
        <li><a href="<?= _CURRENT_FILE_; ?>?action=pending">Pending Orders</a></li>
        <li><a href="<?= _CURRENT_FILE_; ?>?action=completed">Completed Orders</a></li>
    <ul>
  </section>
  <?php 
    include_once('config/stat.php');
}
  ?>
  
<script src="<?= _DOMAIN_; ?>/js/info.js"></script>
<?php   include_once('include/footer.php'); ?>