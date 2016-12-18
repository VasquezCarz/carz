<?php
session_start();

include 'config/carz.conf.php';
include PATH_SCRIPTS.'/php/Database.class.php';
  
$db = new Database();
$db->connect();

$query = $db->writeQuery('SELECT date_expiration, hash_activation FROM crz_utilisateur WHERE id_utilisateur = %d', (int) $_GET['user_id']);
$result = $db->query($query);
$data = $db->fetchAssoc($result);

if ($data['date_expiration'] > date('Y-m-d H:i:s') && $data['hash_activation'] == $_GET['hash']) {
  $query = $db->writeQuery('UPDATE crz_utilisateur SET date_expiration = NULL, hash_activation = NULL WHERE id_utilisateur = %d', (int) $_GET['user_id']);
  $db->query($query);
  $_SESSION['msg'] = '<span class="success">Votre compte a été activé.</span>';
}
else {
  $_SESSION['msg'] = '<span class="error">Erreur d\'activation ! Compte déjà activé ou ID utilisateur incorrect ou activation expirée.</span>';
}

$db->close();

header('Location: index.php');
exit();
?>