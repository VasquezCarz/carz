<?php
session_start();

include 'config/carz.conf.php';
include PATH_SCRIPTS.'/php/Database.class.php';

$db = new Database();
$db->connect();

$date_expiration = date('Y-m-d H:i:s', strtotime('+24 hours'));

$query = $db->writeQuery('UPDATE crz_utilisateur SET date_expiration = %s WHERE id_utilisateur = %d', $date_expiration, (int) $_SESSION['id_utilisateur']);
$db->query($query);

$db->close();

//------- Mail -----------------------------------------------------------------
$to = $_SESSION['mail'];
$subject = 'Activation de votre compte sur l\'application Web "Carz"';
$body = sprintf(file_get_contents('mails/activation.htm'), $_SESSION['login'], BASE_URL.'/user_activate.do.php?user_id='.$_SESSION['id_utilisateur'].'&hash='.$_SESSION['hash_activation']);

$headers  = 'MIME-Version: 1.0'."\r\n";
$headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";
mail($to, $subject, $body, $headers);
//------------------------------------------------------------------------------

header('Location: profile.php');
exit();
?>