<?php
require_once('config/autoload.php');

//Search API
if(isset($_POST['search'])){
    header('Content-Type: application/json; charset=utf-8');
    $category= isset($_POST['category']) ? $_POST['category'] : false;
    echo $product->search($_POST['search'], $category);
    return;
}



?>