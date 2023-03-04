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