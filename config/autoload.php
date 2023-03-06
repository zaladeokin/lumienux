<?php
session_start();
require_once('Admin/config/config.php');
require_once(_ROOT_.'/Admin/config/Product.php');
require_once(_ROOT_.'/Admin/config/Suscriber.php');
require_once(_ROOT_.'/lib/zlib.php');


/*
Any cookie work goes here....

*/

//Instantiate Product Model
$product= new Product();



//pagination API
$limit= 6;
if(isset($_POST['page'])){
    $page= intval($_POST['page']);
    //Product Category
    if(isset($_POST['category'])){
        $category= intval($_POST['category']);
        $category= ($category > 0) ? $category : false;
    }else{
        $category= false;
    }
    $pagination= pagination($product->total_product(), $limit, $page);
    $data= $product->load_product($limit, $pagination['offset'], $category);
    while($d= $data->fetch(PDO::FETCH_ASSOC)){
        $id= $d['id'];
        $name= htmlentities($d['name']);
        $price= "&#8358;".htmlentities($d['price']); 
        echo <<<_tmp
            <figure>
                <a href="checkout.php?id=$id">
                <img src="img/product/$d[img]">
                <figcaption>
                    $name<br>
                    price:&nbsp;&nbsp;$price 
                </figcaption>
                </a>
            </figure>
        _tmp;
    }
    include('include/next_button.php');
    $force_stop= true;
    return; 
}else{
    $page= 1;
    $force_stop= false;//Stop propagation
}



//Newsletter COntroller
if(isset($_POST['scb_email']) && isset($_POST['scb_name'])){
$scb= new Suscriber();
$scb->add($_POST);
$force_stop= true;
return;
}else{
    $force_stop= false;
}
//repopulate form for newsletter field
$scb_name= repopulate('scb_name');
$scb_email= repopulate('scb_email');