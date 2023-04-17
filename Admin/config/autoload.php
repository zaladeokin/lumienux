<?php
session_start();
require_once('config.php');
require_once('Admin.php');
require_once('Product.php');
require_once('Order.php');
require_once('DeliveryFee.php');
require_once('Suscriber.php');


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
    $admin= new Admin(_ADMIN_USER_, _ADMIN_PASS_);// Instatiate model class
}
if( _CURRENT_FILE_ == 'product.php' || _CURRENT_FILE_ == 'order.php' ){
    $product= new Product(_ADMIN_USER_, _ADMIN_PASS_);
}
if( _CURRENT_FILE_ == 'order.php' ){
    $order= new Order(_ADMIN_USER_, _ADMIN_PASS_);
}
if( _CURRENT_FILE_ == 'deliveryfee.php' ){
    $deliveryFee= new DeliveryFee(_ADMIN_USER_, _ADMIN_PASS_);
}
if( _CURRENT_FILE_ == 'product.php' || _CURRENT_FILE_ == 'suscriber.php' ){
    $suscriber= new Suscriber(_ADMIN_USER_, _ADMIN_PASS_);// Instatiate model class
}