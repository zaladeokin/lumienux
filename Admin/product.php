<?php
require_once('config/autoload.php');

//Search API
if(isset($_POST['search'])){
  header('Content-Type: application/json; charset=utf-8');
  $category= isset($_POST['category']) ? $_POST['category'] : false;
  echo $product->search($_POST['search'], $category);
  return;
}




//give template based on action
if(isset($_GET['action'])){
    $action= $_GET['action'];
}else{
    $action= false;
}

//Get Post- form action
if(isset($_POST['action'])){
  $p_action= $_POST['action'];
}else{
  $p_action= false;
}

//________________________________________________________________________________


//Post Action here

if($p_action == "upload"){//Product Upload
  $product->upload($_POST); 
  return;
}elseif($p_action == 'edit'){//Edit Product
  $product->edit_product($_POST);
  return;
}elseif($p_action == 'delete'){
  $product->delete_product($_POST['product_id']);
  return;
}



?>





<?php
//View
include_once('include/header.php');
?>


<?php
//Edit product information
if($action == 'edit'){
  if(isset($_GET['id'])){
    $data= $product->get_product($_GET['id']);
    $exist= $product->if_exist($data['name']);
    if($exist == 0 || $exist == false){
      $_SESSION['info'] = "<div id='info'>Invalid action.</div>";
      header('Location: product.php');
      return;
    }
  }else{
    $_SESSION['info'] = "<div id='info'>Invalid action.</div>";
    header('Location: product.php');
    return;
  }
  $p_name= isset($_SESSION['p_name']) ? repopulate('p_name') : $data['name'];
  $p_price= isset($_SESSION['p_price']) ? repopulate('p_price') : $data['price'];
  $p_qty= isset($_SESSION['p_qty']) ? repopulate('p_qty') : $data['stock'];
  $p_cat= isset($_SESSION['p_cat']) ? repopulate('p_cat') : $data['category'];
  $p_desc= isset($_SESSION['p_desc']) ? repopulate('p_desc') : $data['description'];
  $display_img= $data['img'] != null ? _DOMAIN_."/img/product/$data[img]": null;
  function cat($id, $p_cat){
    $select= ($id == $p_cat) ? 'selected' : '';
    return $select;
  }
?>
<section>
  <h1>Edit product</h1>
  <form method="POST" enctype="multipart/form-data">
    <label class="zl-form-info" <?= FormFlashMsg('name_err'); ?>>Product name&nbsp;<input type="text" name="product_name" value="<?= $p_name; ?>"></label>
    <label class="zl-form-info" <?= FormFlashMsg('price_err'); ?>>Price&nbsp;<input type="number" name="product_price" value="<?= $p_price; ?>"></label>
    <label class="zl-form-info" <?= FormFlashMsg('qty_err'); ?>>Quantity&nbsp;<input type="number" name="product_qty" value="<?= $p_qty; ?>"></label>
    <label class="zl-form-info" <?= FormFlashMsg('cat_err'); ?>>Category&nbsp;
        <select name="category">
          <option value="0">Select product category</option>
            <option value="1" <?= cat('1', $p_cat); ?>> Batteries</option>
            <option value="2" <?= cat('2', $p_cat); ?>>Inverters</option>
            <option value="3" <?= cat('3', $p_cat); ?>>Solar Panels</option>
            <option value="4" <?= cat('4', $p_cat); ?>>Charge Controllers</option>
            <option value="5" <?= cat('5', $p_cat); ?>>Accessories</option>
        </select>
    </label>
    <label class="zl-form-info" <?= FormFlashMsg('desc_err'); ?>>Product description<br><textarea name="product_desc" placeholder="Brief description of product here"><?= $p_desc; ?></textarea></label>
    <label class="zl-form-info" id="img_field" <?= FormFlashMsg('img_err'); ?>>Display picture&nbsp;
<?php
//check if image exist
if($display_img){
  echo <<<_img
    <div id="preview"><img alt="$p_name" src="$display_img" width= "50%"></div>
    <button id="edit_img">Change image</button><br>
    <script>
    var change= document.getElementById('edit_img');
    change.addEventListener("click", function(event){
      event.preventDefault();
      var input= document.createElement('input');
      input.setAttribute('type', 'file');
      input.setAttribute('accept', 'image/*');
      input.setAttribute('id', 'image');
      input.setAttribute('name', 'product_img');
      document.getElementById('img_field').append(input);
      change.remove();
      document.getElementById('image').addEventListener("change", preview);
    });
    </script>
  _img;
}else{
  echo '<input type="file" accept="image/*" id= "image" name="product_img"><div id="preview"></div><br>';
}
?>
  </label>
  <input type="hidden" name="product_id" value="<?= $data['id']; ?>">
    <input type="hidden" name="action" value="edit">
    <input type="submit" value="edit">
    </form>
  </section>
  <script src="<?= _DOMAIN_;?>/js/imagePreview.js"></script>
<?php } ?>

<?php
//Upload product
if($action == 'upload'){ 
//repopulate form
$p_name= repopulate('p_name');
$p_price= repopulate('p_price');
$p_qty= repopulate('p_qty');
$p_cat= repopulate('p_cat');
$p_desc= repopulate('p_desc');
function cat($id, $p_cat){
  $select= ($id == $p_cat) ? 'selected' : '';
  return $select;
}


?>
<section>
  <h1>Upload Products</h1>
  <form method="POST" enctype="multipart/form-data">
    <label class="zl-form-info" <?= FormFlashMsg('name_err'); ?>>Product name&nbsp;<input type="text" name="product_name" value="<?= $p_name; ?>"></label>
    <label class="zl-form-info" <?= FormFlashMsg('price_err'); ?>>Price&nbsp;<input type="number" name="product_price" value="<?= $p_price; ?>"></label>
    <label class="zl-form-info" <?= FormFlashMsg('qty_err'); ?>>Quantity&nbsp;<input type="number" name="product_qty" value="<?= $p_qty; ?>"></label>
    <label class="zl-form-info" <?= FormFlashMsg('cat_err'); ?>>Category&nbsp;
        <select name="category">
          <option value="0">Select product category</option>
            <option value="1" <?= cat('1', $p_cat); ?>> Batteries</option>
            <option value="2" <?= cat('2', $p_cat); ?>>Inverters</option>
            <option value="3" <?= cat('3', $p_cat); ?>>Solar Panels</option>
            <option value="4" <?= cat('4', $p_cat); ?>>Charge Controllers</option>
            <option value="5" <?= cat('5', $p_cat); ?>>Accessories</option>
        </select>
    </label>
    <label class="zl-form-info" <?= FormFlashMsg('desc_err'); ?>>Product description<br><textarea name="product_desc" placeholder="Brief description of product here"><?= $p_desc; ?></textarea></label>
    <label class="zl-form-info" <?= FormFlashMsg('img_err'); ?>>Display picture&nbsp;<input type="file" accept="image/*" id= "image" name="product_img"><div id="preview"></div></label>
    <input type="hidden" name="action" value="upload">
    <input type="submit" value="Upload">
    </form>
  </section>
  <script src="<?= _DOMAIN_;?>/js/imagePreview.js"></script>
<?php } ?>

<?php
//Delete Product
if($action == 'delete'){
    if(isset($_GET['id'])){
      $data= $product->get_product($_GET['id']);
      $exist= $product->if_exist($data['name']);
      if($exist == 0 || $exist == false){
        $_SESSION['info'] = "<div id='info'>Invalid action.</div>";
        header('Location: product.php');
        return;
      }
    }else{
      $_SESSION['info'] = "<div id='info'>Invalid action.</div>";
      header('Location: product.php');
      return;
    }
    $p_name= $data['name'];
    $p_price= $data['price'];
    $p_qty= $data['stock'];
    $p_cat= $data['category'];
    $p_desc= $data['description'];
    $display_img=  _DOMAIN_."/img/product/$data[img]"; 
  
?>
<section id="checkout">
    <h1>Delete product</h1>
    <figure>
        <img src="<?= $display_img; ?>">
        <figcaption>
            <h2><?= $p_name; ?></h2>
            <div><strong>Description</strong><?= $p_desc; ?></div>
            <div><strong>Price</strong>&nbsp;:&nbsp;&#8358;<?= $p_price; ?></div>
            <div><small>&nbsp;&nbsp;<?= $p_qty; ?> stocks available</small></div> 
            <form method="POST">
              <input type="hidden" name="product_id" value="<?= $data['id']; ?>">
              <input type="hidden" name="action" value="delete">
              <input type="submit" value="Delete">
            </form>
        </figcaption>
    </figure>
</section>
<?php } ?>


  <?php
  //Product Management
  if($action == 'product_mgt'){ ?>
   <section>
  <h1>Products</h1>
  <fieldset>
<legend> Search for product</legend>
<select id="category">
  <option value="0">Select product category</option>
  <option value="1"> Batteries</option>
  <option value="2">Inverters</option>
  <option value="3">Solar Panels</option>
  <option value="4">Charge Controllers</option>
  <option value="5">Accessories</option>
</select>
<br><br>
<input type="search" id="search" placeholder="Enter product name" ?>
  </fieldset><br>
  <img id="spinner" src="../img/gif/spinner.gif" alt="Loading" height="25" style="vertical-align: middle; display:none;">
  <br>
  <div id="search_outcome"></div><br>
  </section>

  <script type="text/javascript" src="<?= _DOMAIN_;?>/js/jquery.min.js"></script>
  <script src="<?= _DOMAIN_;?>/js/search.js"></script>
    <?php } ?>


    <?php 
if(!$action){
    //Product Landing page
    ?>
  <section>
  <h1>Categories</h1>
    <ul>
        <li><a href="<?= _CURRENT_FILE_; ?>?action=product_mgt">Product Management</a></li>
        <li><a href="<?= _CURRENT_FILE_; ?>?action=upload">Upload Product</a></li>
    <ul>
  </section>
  <?php } ?>
  

<?php   include_once('include/footer.php'); ?>