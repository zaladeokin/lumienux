<?php
//Database Config
define("_HOST_", 'localhost');
define('_PORT_', '3306');
define('_DB_', 'Lumienux');
define('_USER_', 'root');
define('_PASS_', '');

//Path setting

define('_DOMAIN_', $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].'/lumienux');//Domain e.g. http://localhost, http://abc.com
//Remove '/lumienux' for live production.
//define('_DOMAIN_', $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']);

//define( '_RQT_URL_', $_SERVER['REQUEST_URI']); //reuest url path excluding domain name and HTTP protocol img/brand/ || can include file name if added in browser search box

define( '_ROOT_', $_SERVER['DOCUMENT_ROOT'].'/lumienux');// Root path e.g. C:/xampp/htdocs
//Remove '/lumienux' for live production.
//define( '_ROOT_', $_SERVER['DOCUMENT_ROOT']);

//$_SERVER['SCRIPT_NAME'].;//reuest file name with path img/brand/photo.jpg
define('_CURRENT_FILE_', basename($_SERVER['SCRIPT_NAME'])); //basename() extract file name from path as provided by $_SERVER['SCRIPT_NAME']


//Admin Credentials
define('_ADMIN_', 'zaladeokin@gmail.com');