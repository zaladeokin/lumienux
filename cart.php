<?php
require_once('config/autoload.php');

?>




<?php
//View
include_once('include/header.php');
?>
<section>
        <h1>Cart</h1>
<?php 
if($cart != false && count($cart) > 0){
    for($i=0;$i < count($cart);$i++){
        $item_count= $i +1;
        $item= $product->get_product($cart[$i]);
        $i_name= htmlentities($item['name']);
        $i_price= htmlentities($item['price']);
        $i_stock= htmlentities($item['stock']);
        $input_disable= (intval($i_stock) > 0) ? "" : "disabled= true";
        $qty= ($i_stock > 0) ? 1 : 0;
        $stock_stat= ($i_stock > 0) ? "$i_stock stocks available" : "Out of stock";
        echo <<<_cart
            <div class="cart" id="cart_$item_count">
                <img src="img/product/$item[img]" alt="$i_name">
                <div>
                    <span>$i_name</span>
                    <span>Price</strong>&nbsp;:&nbsp;&#8358;$i_price</span>
                    <span>$stock_stat</span>
                </div>
                <div>
                    <strong>Quantities&nbsp;<input type="number" id="p_qty_$item_count" min="1" max="$i_stock" value="$qty" $input_disable></strong><button>Remove</button>
                    <input type="hidden" id="p_id_$item_count" value="$item[id]">
                    <input type="hidden" id="p_price_$item_count" value="$i_price">
                </div>
            </div>
        _cart;
    }
?>
        <div id="cart_summary" class="table-responsive-sm">
            <table class="table table-sm table-striped caption-top">
                <caption>Order Summary</caption>
                <thead class="table-dark">
                    <tr><th scope="col">s/n</th> <th scope="col">Product name</th> <th scope="col">Qty</th> <th scope="col">price/Qty(&#8358;)</th> <th scope="col">Amount(&#8358;)</th></tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr><td colspan="4">Total</td><td id="total"></td></tr>
                </tfoot>
            </table>
            <form action="#">
                <input type="hidden" name="products_id">
                <input type="hidden" name="products_qty">
            <input type="submit" value="Continue to payment">
            </form>
        </div>
<?php
}else{
    echo '<div id="cart_summary" class="table-responsive-sm"><div>Your Cart is empty.<br><br><a href="product.php">Click here to view our products</a></div></div>';
}
?>
    </section>
<?php
include_once('include/footer.php');
?>