<?php
session_start();

include 'config/carz.conf.php';
include PATH_SCRIPTS.'/php/Database.class.php';
  
$db = new Database();
$db->connect();

$query = $db->writeQuery('SELECT login, mail, admin, date_expiration, hash_reset FROM crz_utilisateur WHERE id_utilisateur = %d', (int) $_GET['user_id']);
$result = $db->query($query);
$data = $db->fetchAssoc($result);

if ($data['date_expiration'] > date('Y-m-d H:i:s') && $data['hash_reset'] == $_GET['hash']) {
  $query = $db->writeQuery('UPDATE crz_utilisateur SET date_expiration = NULL, hash_reset = NULL WHERE id_utilisateur = %d', (int) $_GET['user_id']);
  $db->query($query);
  $_SESSION['msg'] = '<span class="warning">Saisissez un nouveau mot de passe et confirmez-le !</span>';
  
  $_SESSION['id_utilisateur'] = $_GET['user_id'];
  $_SESSION['login'] = $data['login'];
  $_SESSION['mail'] = $data['mail'];
  $_SESSION['admin'] = $data['admin'];  
  header('Location: profile.php');
}
else {
  $_SESSION['msg'] = '<span class="error">Erreur de réinitialisation de mot de passe ! La demande a expiré.</span>';
  header('Location: index.php');
}

$db->close();
exit();
?>