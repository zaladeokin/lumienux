<?php
if(isset($_POST['page'])){
    require_once('autoload.php');
    $page= $_POST['page'];
    /***
    *Orders Stat
    */
    if($page == 'home.php' || $page == 'order.php'){
        $order= new Order(_ADMIN_USER_, _ADMIN_PASS_);  //Instatiate order for home.php
        $pending= $order->total_order('0');// Pending order stat
        /** Data below is available for order.php only */
        if($page == 'order.php'){
            $total_order= $order->total_order();// Total number of Order
            $completed= $order->total_order('1');// Completed order stat
        }
    }



    /***
    * Product Stat
    */
    if($page == 'home.php' || $page == 'product.php'){ 
        $product= new Product(_ADMIN_USER_, _ADMIN_PASS_);  //Instatiate product for home.php
        $out_0f_stock= $product->total_product(false, 'sold_product = stock');//Total number of unavailable/ exhausted product
        /** Data below is available for product.php only */
        if($page == 'product.php'){
            $total_product= $product->total_product();//Total number of product in Database
            $batteries= $product->total_product('1');//Battery stat
            $inverter= $product->total_product('2');//Inverter stat
            $panel= $product->total_product('3');//Panel stat
            $controller= $product->total_product('4');//Charge Controller stat
            $light= $product->total_product('5');//Solar Light stat
            $accessory= $product->total_product('6');//Solar Accesories stat
        }
    }



?>


    <?php
    //view
    ?>
    <table class="table table-sm table-striped caption-top text-center">
        <caption style="font-style: oblique; font-weight:normal;font-size:1.5em;color:#b09337;">Statitics</caption>
        <thead class="table-dark">
            <tr><th scope="col" style="font-weight:normal;">Scope</th> <th scope="col" style="font-weight:normal;">Value</th></tr>
        </thead>
        <tbody>
            <?php if($page == 'order.php'){ ?>
            <tr><td>Total Orders</td><td><?= $total_order; ?></td></tr>
            <tr><td style="color:#008000;">Completed Orders</td><td style="color:#008000;"><?= $completed; ?></td></tr>
            <?php 
            }
            if($page == 'home.php' || $page == 'order.php'){ 
            ?>
            <tr><td style="color:#ff0000;">Pending Orders</td><td style="color:#ff0000;"><?= $pending; ?></td></tr>
            <?php
            } 
            if($page == 'product.php'){ ?>
            <tr><td>Total product</td><td><?= $total_product; ?></td></tr>
            <tr><td>Batteries</td><td><?= $batteries; ?></td></tr>
            <tr><td>Inverters</td><td><?= $inverter; ?></td></tr>
            <tr><td>Solar Panels</td><td><?= $panel; ?></td></tr>
            <tr><td>Charge Controller</td><td><?= $controller; ?></td></tr>
            <tr><td>Light</td><td><?= $light; ?></td></tr>
            <tr><td>Accessories</td><td><?= $accessory; ?></td></tr>
            <?php 
            } 
            if($page == 'home.php' || $page == 'product.php'){ 
            ?>
            <tr><td style="color:#ff0000;">Product out of stock</td><td style="color:#ff0000;"><?= $out_0f_stock; ?></td></tr>
            <?php 
            }
            ?>
        </tbody>
    </table>
<?php }else{ ?>
    <section id="stat" class="table-responsive-sm"></section>
<script src="<?= _DOMAIN_; ?>/js/stat.js"></script>
<script>
    //update statatistic every 30sec
  $(document).ready(function(){
    $.ajaxSetup({cache: false});
    stat('<?= _CURRENT_FILE_; ?>');
    setInterval(stat, 5000, '<?= _CURRENT_FILE_; ?>');
  });
</script>
<?php } ?>