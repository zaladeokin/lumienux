<?php
session_start();
require_once('Admin.php');
require_once(_ROOT_.'/lib/zlib.php');


if(!isset($_SESSION['Admin']) && _CURRENT_FILE_ != 'index.php'){
    //Ensure User is authorized (i.e. login) before having access to all resources.
    header("Location: "._DOMAIN_."/Admin/index.php");
}


/*
Any cookie work goes here....

*/


$admin= new Admin();// Instatiate model class