<?php
session_start();
if (empty($_SESSION['id_utilisateur'])) {
  $_SESSION['msg'] = '<span class="error">La session a expiré !</span>';
  header('Location: index.php');
  exit();
}

include 'config/carz.conf.php';
include PATH_SCRIPTS.'/php/Database.class.php';

$db = new Database();
$db->connect();

if (!empty($_GET['car_id'])) {
  $query = 'DELETE FROM crz_voiture WHERE id_voiture = %d AND fk_utilisateur = %d';
  $query .= ' AND id_voiture NOT IN (SELECT fk_voiture FROM crz_groupe_voiture)';
  $query = $db->writeQuery($query, (int) $_GET['car_id'], (int) $_SESSION['id_utilisateur']);
  $db->query($query);
}

$db->close();

header('Location: profile.php');
exit();
?>