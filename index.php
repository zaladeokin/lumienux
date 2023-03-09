<?php
require_once('config/autoload.php');

//Load product if exist..
$num_of_row= $product->total_product();
if($num_of_row != 0){
    $pagination= pagination($num_of_row, $limit, $page);
    $data= $product->load_product($limit, $pagination['offset']);
}else{
    $data= false;
}
?>


<?php
//View
include_once('include/header.php');
?>
<article>
    <h1>Brand Statement</h1>
    <p> We specialized in off grid Solar intatllation, sales of solar product.this and that<br>
    Our services range from installation, maintenance ans sales of related product.</p>
</article>

<article>
  <h1>Perfect solar setup for your budget</h1>
    <p> You don't have to break your bank before you can get a solar setup. The plan below might suite your budget</p>
    <ul>
      <li>0.5Kva Setup (80,000 Naira)</li>
      <li>1Kva Setup (120,000 Naira)</li>
      <li>1.5Kva Setup (180,000 Naira)</li>
      <li>1Kva Setup (230,000 Naira)</li>
    </ul>
    <a href="https://api.whatsapp.com/send?phone=2347034205664"><button>Contact us for your suitable setup plan</button></a><br><br>
</article>
<?php if($data != false){ ?>
<section class="product">
    <h2>Featured products</h2>
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
echo "</section>";
}

include_once('include/footer.php');
?>