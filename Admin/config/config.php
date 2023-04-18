<?php
/**
 * Must be the first file to load in autoload.php
 * 
 * 
 *$FOLDER_LEVEL This specify the name of subfolder where project is placed,
 *Path name should begin with "/" e.g. $FOLDER_LEVEL=/lumienux
 *$FOLDER_LEVEL should be left empty if project is not in subfolder i.e $FOLDER_LEVEL=""
 */
$FOLDER_LEVEL="/lumienux";


//Path setting
define('_DOMAIN_', $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].$FOLDER_LEVEL);//Domain e.g. http://localhost, http://abc.com

define( '_RQT_URL_', $_SERVER['REQUEST_URI']); //reuest url path excluding domain name and HTTP protocol img/brand/ || can include file name if added in browser search box

define( '_ROOT_', $_SERVER['DOCUMENT_ROOT'].$FOLDER_LEVEL);// Root path e.g. C:/xampp/htdocs

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



//Email setup
define("_MAIL_HOST_", getenv('MAIL_HOST'));
define('_MAIL_USER_', getenv('MAIL_USER'));
define('_MAIL_PASS_', getenv('MAIL_PASS'));
define('_MAIL_PORT_', getenv('MAIL_PORT'));
define('_MAIL_REPLY_', getenv('MAIL_REPLY'));
define('_MAIL_REPLY_NAME_', getenv('MAIL_REPLY_NAME'));


//Payment Gateway API KEY
define("_FLW_SECRET_KEY_", getenv('FLW_SECRET_KEY'));


//Recaptcha V2 API keys
define("_V2_SECRET_KEY_", getenv('V2_SECRET_KEY'));
define("_V2_SITE_KEY_", getenv('V2_SITE_KEY'));


//Recaptcha V3 API keys
define("_V3_SECRET_KEY_", getenv('V3_SECRET_KEY'));
define("_V3_SITE_KEY_", getenv('V3_SITE_KEY'));


//Admin Credentials
define('_ADMIN_', getenv('ADMIN_EMAIL'));
define('_ADMIN_PHONE_', getenv('ADMIN_PHONE'));

//SOcial Link
define('_WHATSAPP_', getenv('WHATSAPP'));
define('_FACEBOOK_', getenv('FACEBOOK'));
define('_INSTAGRAM_', getenv('INSTAGRAM'));