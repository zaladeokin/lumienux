<?php
session_start();
require_once('Admin.php');
require_once('Product.php');
require_once(_ROOT_.'/lib/zlib.php');


if(!isset($_SESSION['Admin']) && _CURRENT_FILE_ != 'index.php' && _CURRENT_FILE_ != 'reset_password.php' && _CURRENT_FILE_ != 'dbsetup.php'){
    //Ensure User is authorized (i.e. login) before having access to all resources.
    header("Location: "._DOMAIN_."/Admin/index.php");
    return;
}

/*
Any cookie work goes here....

*/

//Instatiating Model class
if( _CURRENT_FILE_ == 'index.php' || _CURRENT_FILE_ == 'reset_password.php' || _CURRENT_FILE_ == 'dbsetup.php' ){
    $admin= new Admin();// Instatiate model class
}elseif( _CURRENT_FILE_ == 'product.php' ){
    $product= new Product();
}