<?php
require_once('config/autoload.php');

//Search API
if(isset($_POST['search'])){
    header('Content-Type: application/json; charset=utf-8');
    $category= isset($_POST['category']) ? $_POST['category'] : false;
    echo $product->search($_POST['search'], $category);
    return;
}


//Product Category
if(isset($_GET['category'])){
    $category= intval($_GET['category']);
    $category= ($category > 0) ? $category : false;
}else{
    $category= false;
}
$cat_name= ["Batteries", "Inverters", "Solar Panels", "Charge Controllers", "Light", "Accessories"];
$cat_display= ($category && $category < 7) ? $cat_name[$category-1] : "All Products";
//Load product if exist..
$num_of_row= $product->total_product($category);
if($num_of_row != 0){
    $pagination= pagination($num_of_row, $limit, $page);
    $data= $product->load_product($limit, $pagination['offset'], $category);
}else{
    $data= false;
}
?>



<?php
//View
include_once('include/header.php');
?>
<section class="product">
    <h1>Products&nbsp;<i class="fa-sharp fa-solid fa-right-long"></i>&nbsp;<?= $cat_display; ?></h1>
    <input type="hidden" id="post_category" value="<?= $category; ?>">
  <?php if($data != false){ ?>
<?php
while($d= $data->fetch(PDO::FETCH_ASSOC)){ ?>
    <figure>
        <a href="checkout.php?id=<?= $d['id']; ?>">
        <img src="img/product/<?= $d['img']; ?>" alt="<?= $d['name']; ?>">
        <figcaption>
            <?= htmlentities($d['name']); ?><br>
            price:&nbsp;&nbsp;&#8358;<?= htmlentities($d['price']); ?>  
        </figcaption>
        </a>
    </figure>
<?php 
    }
include('include/next_button.php'); 
}else{
    echo "<strong>No result found</strong>";
}
?>
</section>
<?php
include_once('include/footer.php');
?>