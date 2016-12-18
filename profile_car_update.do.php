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

$_SESSION['selectedBrand'] = $_POST['selectedBrand'];
$_SESSION['selectedModel'] = $_POST['selectedModel'];
$_SESSION['selectedCode'] = $_POST['selectedCode'];
$_SESSION['selectedPower'] = $_POST['selectedPower'];
$_SESSION['selectedEngine'] = $_POST['selectedEngine'];
$_SESSION['selectedGearbox'] = $_POST['selectedGearbox'];

if ($_POST['updateCar'] == 'Enregistrer') {
  $query = 'UPDATE crz_voiture';
  $query .= ' SET lib_voiture = %s, fk_modele = %d, fk_code = %d, fk_boite = %d, fk_puissance = %d, annee = %d';
  $query .= ' WHERE id_voiture = %d';
  $query = $db->writeQuery($query, $_POST['txtCar'], (int) $_POST['selectedModel'], (int) $_POST['selectedCode'], (int) $_POST['selectedGearbox'], (int) $_POST['selectedPower'], (int) $_POST['selectedYear'], (int) $_SESSION['id_voiture']);
  $db->query($query);
  
  $db->close();
  header('Location: profile.php');
  exit();
}

$db->close();
header('Location: profile_car_edit.php');
exit();
?>