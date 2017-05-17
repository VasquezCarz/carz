<?php
session_start();
if ($_SESSION['admin'] != '1') {
  $_SESSION['msg'] = '<span class="error">Accès interdit !</span>';
  header('Location: index.php');
  exit();
}

include 'config/carz.conf.php';
include PATH_SCRIPTS.'/php/Database.class.php';

$db = new Database();
$db->connect();

if (!empty($_GET['mc'])) {
  $tokens = explode(',', $_GET['mc']);
  $query = 'DELETE FROM crz_modele_code WHERE fk_modele = %d AND fk_code = %d';
  $query = $db->writeQuery($query, (int) $tokens[0], (int) $tokens[1]);
  $db->query($query);
}

if (!empty($_GET['mcmb'])) {
  $tokens = explode(',', $_GET['mcmb']);
  $query = 'DELETE FROM crz_modele_code_motorisation_boite WHERE fk_modele = %d AND fk_code = %d AND fk_motorisation = %d AND fk_boite = %d';
  $query = $db->writeQuery($query, (int) $tokens[0], (int) $tokens[1], (int) $tokens[2], (int) $tokens[3]);
  $db->query($query);
}

if (!empty($_GET['mcp'])) {
  $tokens = explode(',', $_GET['mcp']);
  $query = 'DELETE FROM crz_modele_code_puissance WHERE fk_modele = %d AND fk_code = %d AND fk_puissance = %d';
  $query = $db->writeQuery($query, (int) $tokens[0], (int) $tokens[1], (int) $tokens[2]);
  $db->query($query);
}

$db->close();
header('Location: cleaner.php');
exit();
?>