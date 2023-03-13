<?php
/**
 * Must be the first file to load in autoload.php
 */


//Path setting
define('_DOMAIN_', $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].'/lumienux');//Domain e.g. http://localhost, http://abc.com
//Remove '/lumienux' for live production.
//define('_DOMAIN_', $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']);

define( '_RQT_URL_', $_SERVER['REQUEST_URI']); //reuest url path excluding domain name and HTTP protocol img/brand/ || can include file name if added in browser search box

define( '_ROOT_', $_SERVER['DOCUMENT_ROOT'].'/lumienux');// Root path e.g. C:/xampp/htdocs
//Remove '/lumienux' for live production.
//define( '_ROOT_', $_SERVER['DOCUMENT_ROOT']);

//$_SERVER['SCRIPT_NAME'].;//request file name with path img/brand/photo.jpg
define('_CURRENT_FILE_', basename($_SERVER['SCRIPT_NAME'])); //basename() extract file name from path as provided by $_SERVER['SCRIPT_NAME']

// Load zlib
require_once(_ROOT_.'/lib/zlib.php');
//Set .env file
setEnV(_ROOT_."/Admin/config/.env");

//Database Config
define("_HOST_", getenv('DB_HOST'));
define('_PORT_', getenv('DB'));
define('_DB_', getenv('DB'));
define('_ADMIN_USER_', getenv('ADMIN_DB_USER'));
define('_ADMIN_PASS_', getenv('ADMIN_DB_PASS'));
define('_USER_', getenv('DB_USER'));
define('_PASS_', getenv('DB_PASS'));



//Admin Credentials
define('_ADMIN_', getenv('ADMIN_EMAIL'));
define('_ADMIN_PHONE_', getenv('ADMIN_PHONE'));

//SOcial Link
define('_WHATSAPP_', getenv('WHATSAPP'));
define('_FACEBOOK_', getenv('FACEBOOK'));
define('_INSTAGRAM_', getenv('INSTAGRAM'));