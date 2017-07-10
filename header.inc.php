<?php
include 'config/carz.conf.php';
include PATH_SCRIPTS.'/php/Database.class.php';

//require_once(PATH_SCRIPTS.'/php/MyLogPHP.class.php');
require_once(PATH_SCRIPTS.'/php/Groupe.class.php');
require_once(PATH_SCRIPTS.'/php/Voiture.class.php');
require_once(PATH_SCRIPTS.'/php/User.class.php');
require_once(PATH_SCRIPTS.'/php/Stats1.class.php');
require_once(PATH_SCRIPTS.'/php/Stats2.class.php');
require_once(PATH_SCRIPTS.'/php/Stats3.class.php');
require_once(PATH_SCRIPTS.'/php/Pays.class.php');

//$log = new MyLogPHP('./logs/debug.log.csv', ';');
$db = new Database();
$db->connect();
?>
<div>
  
</div>