<?php
require_once('config/autoload.php');

//Get product_id
if(isset($_GET['id'])){
    $id= intval($_GET['id']);
    $details= ($id > 0) ? $product->get_product($id) : false;

    //Check product in Cart
    $cart_item= ($cart != false && count($cart) > 0) ? array_search($id, $cart): false;
    $cart_item_button= ($cart_item !== false) ? "Remove from Cart" : "Add to Cart";
    $cart_item_status= ($cart_item !== false) ? "delete" : "add";
}else{
    $details= false;
}


if($details){//Load related product if exist..
    $num_of_row= $product->total_product($details['category'], "id != $details[id]");
    if($num_of_row != 0){
        $pagination= pagination($num_of_row, $limit, $page);
        $data= $product->load_product($limit, $pagination['offset'], $details['category'], "id != $details[id]");
    }else{
        $data= false;
    }
}
?>




<?php
//View
include_once('include/header.php');
if($details){
    $stock_avail_qty= intval($details['stock']) - intval($details['sold_product']);
    if($stock_avail_qty > 0){
        $stock_stat= ($stock_avail_qty > 1) ? $stock_avail_qty." stocks available" : $stock_avail_qty." stock available";
        $btn_disable= "";
    }else{
        $stock_stat= "<span style='color: #ff0000;'>Out of stock</span>";
        $btn_disable= "disabled= true";
    }
?>
<section id="checkout">
    <h1>Product detail</h1>
    <figure>
        <img src="img/product/<?= $details['img']; ?>" alt="<?= htmlentities($details['name']); ?>">
        <figcaption>
            <h2><?= htmlentities($details['name']); ?></h2>
            <div><strong>Description</strong><?= htmlentities($details['description']); ?></div>
            <div><strong>Price</strong>&nbsp;:&nbsp;&#8358;<?= htmlentities($details['price']); ?></div>
            <div><small>&nbsp;&nbsp;<?= $stock_stat; ?></small></div>
            <input type="hidden" value="<?= htmlentities($details['id']); ?>">
            <input type="hidden" value="<?= $cart_item_status ?>">
            <button <?= $btn_disable ?>><i class="fa-solid fa-cart-plus"></i>&nbsp;<?= $cart_item_button; ?></button>
        </figcaption>
    </figure>
</section>


<?php if($data != false){ ?>
<section class="product">
  <h2>Related products</h2>
<?php
while($d= $data->fetch(PDO::FETCH_ASSOC)){ ?>
    <figure>
        <a href="checkout.php?id=<?= $d['id']; ?>">
        <img src="img/product/<?= $d['img']; ?>">
        <figcaption>
            <?= htmlentities($d['name']); ?><br>
            price:&nbsp;&nbsp;&#8358;<?= htmlentities($d['price']); ?> 
                </figcaption>  
        </figcaption>
        </a>
    </figure>
<?php 
        }
        echo "</section>";
    }
}else{
    echo '<div id="info">Invalid request</div>';
}

include_once('include/footer.php');
?>