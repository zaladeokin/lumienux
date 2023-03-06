<?php
require_once('config/autoload.php');

//Get product_id
if(isset($_GET['id'])){
    $id= intval($_GET['id']);
    $details= ($id > 0) ? $product->get_product($id) : false;

}else{
    $details= false;
}


//Load related product if exist..
$num_of_row= $product->total_product($details['category'], "id != $details[id]");
if($num_of_row != 0){
    $pagination= pagination($num_of_row, $limit, $page);
    $data= $product->load_product($limit, $pagination['offset'], $details['category'], "id != $details[id]");
}else{
    $data= false;
}
?>




<?php
//View
include_once('include/header.php');
if($details){
?>
<section id="checkout">
    <h1>Product detail</h1>
    <figure>
        <img src="img/product/<?= $details['img']; ?>">
        <figcaption>
            <h2><?= htmlentities($details['name']); ?></h2>
            <div><strong>Description</strong><?= htmlentities($details['description']); ?></div>
            <div><strong>Price</strong>&nbsp;:&nbsp;&#8358;<?= htmlentities($details['price']); ?></div>
            <div><small>&nbsp;&nbsp;<?= htmlentities($details['stock']); ?> stocks available</small></div> 
            <button><i class="fa-solid fa-cart-plus"></i>&nbsp;Add to cart</button>
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