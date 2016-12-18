<?php
//------- Set base URL of site ----------------------------
define('BASE_URL', 'http://'.$_SERVER['SERVER_NAME'].'/carz');
//---------------------------------------------------------

//------- Set all paths -----------------------------------
define('PATH_SITE',     $_SERVER['DOCUMENT_ROOT'].'/carz');
define('PATH_SCRIPTS',  PATH_SITE.'/scripts');
define('PATH_GRAPHICS', PATH_SITE.'/graphics');
//---------------------------------------------------------

//------- Database settings -------------------------------
define('DB_HOST',     'localhost');
define('DB_NAME',     'jeloge');
define('DB_USER',     'root');
define('DB_PASSWORD', '');
//---------------------------------------------------------
?>
