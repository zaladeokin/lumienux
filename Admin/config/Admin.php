<?php
require_once('config.php');

Class Admin extends PDO{
    function __construct(){
        Parent::__construct('mysql:host='._HOST_.';port='._PORT_.';dbname='._DB_, _USER_, _PASS_);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}