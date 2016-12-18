<?php
session_start();
if (empty($_SESSION['id_utilisateur'])) {
  $_SESSION['msg'] = '<span class="error">La session a expiré !</span>';
  header('Location: index.php');
  exit();
}

if(isset($_POST['selectedGroup'])) $_SESSION['selectedGroup'] = $_POST['selectedGroup'];

include 'config/carz.conf.php';
include PATH_SCRIPTS.'/php/Database.class.php';

$db = new Database();
$db->connect();

// Contrôler que l'utilisateur connecté est bien admin du groupe sélectionné
$query = 'SELECT admin_groupe FROM crz_groupe_utilisateur WHERE fk_groupe = %d AND fk_utilisateur = %d';
$query = $db->writeQuery($query, (int) $_POST['selectedGroup'], (int) $_SESSION['id_utilisateur']);
$result = $db->query($query);
$data = $db->fetchAssoc($result);
if ($_SESSION['admin'] != '1' && $data['admin_groupe'] != 1) {
  $_SESSION['msg'] = '<span class="error">Vous n\'êtes pas administrateur de ce groupe !</span>';
  $db->close();
  header('Location: admin.php');
  exit();
}

// Ajout / retrait des utilisateurs dans le groupe
if ($_POST['form'] == 'frmGroupUser' && $_POST['saveUsersInGroup'] == 'Enregistrer' && !empty($_POST['selectedGroup'])) {
  $query = $db->writeQuery('DELETE FROM crz_groupe_utilisateur WHERE fk_groupe = %d', (int) $_POST['selectedGroup']);
  $db->query($query);
  for ($i = 0; $i < count($_POST['selectedUsers']); $i++) {
    $query = 'INSERT INTO crz_groupe_utilisateur (fk_groupe, fk_utilisateur, admin_groupe) VALUES (%d, %d, %d)';
    $query = $db->writeQuery($query, (int) $_POST['selectedGroup'], (int) $_POST['selectedUsers'][$i], in_array($_POST['selectedUsers'][$i], $_POST['selectedAdmins']) ? 1 : 0);
    $db->query($query);
  }
}

// Ajout / retrait des voitures dans le groupe
if ($_POST['form'] == 'frmGroupCar' && $_POST['saveCarsInGroup'] == 'Enregistrer' && !empty($_POST['selectedGroup'])) {
  $query = $db->writeQuery('DELETE FROM crz_groupe_voiture WHERE fk_groupe = %d', (int) $_POST['selectedGroup']);
  $db->query($query);
  for ($i = 0; $i < count($_POST['selectedCars']); $i++) {
    $query = 'INSERT INTO crz_groupe_voiture (fk_groupe, fk_voiture) VALUES (%d, %d)';
    $query = $db->writeQuery($query, (int) $_POST['selectedGroup'], (int) $_POST['selectedCars'][$i]);
    $db->query($query);
  }
}

// Suppression des utilisateurs qui ne sont pas dans un groupe
if ($_POST['form'] == 'frmUser' && $_SESSION['admin'] == '1' && $_POST['deleteUsers'] == 'Supprimer' && !empty($_POST['selectedUsers'])) {
  $usersToDelete = implode(',', $_POST['selectedUsers']);
  
  $query = 'DELETE FROM crz_utilisateur_pseudo WHERE fk_utilisateur <> %d AND fk_utilisateur IN ('.$usersToDelete.')';
  $query = $db->writeQuery($query, (int) $_SESSION['id_utilisateur']);
  $db->query($query);
  
  $query = 'DELETE FROM crz_pseudo WHERE id_pseudo NOT IN (SELECT fk_pseudo FROM crz_utilisateur_pseudo)';
  $db->query($query);
  
  $query = 'DELETE FROM crz_voiture WHERE fk_utilisateur <> %d AND fk_utilisateur IN ('.$usersToDelete.')';
  $query = $db->writeQuery($query, (int) $_SESSION['id_utilisateur']);
  $db->query($query);
  
  $query = 'DELETE FROM crz_utilisateur WHERE id_utilisateur <> %d AND id_utilisateur IN ('.$usersToDelete.')';
  $query = $db->writeQuery($query, (int) $_SESSION['id_utilisateur']);
  $db->query($query);
}

// Suppression des voitures qui ne sont pas dans un groupe
if ($_POST['form'] == 'frmCar' && $_SESSION['admin'] == '1' && $_POST['deleteCars'] == 'Supprimer' && !empty($_POST['selectedCars'])) {
  $query = 'DELETE FROM crz_voiture WHERE id_voiture IN ('.implode(',', $_POST['selectedCars']).')';
  $db->query($query);
}

// Création d'un groupe
if ($_POST['form'] == 'frmGroup' && $_SESSION['admin'] == '1' && $_POST['createGroup'] == 'Créer' && !empty($_POST['textNewGroup'])) {
  $query = $db->writeQuery('INSERT INTO crz_groupe (lib_groupe) VALUES (%s)', trim($_POST['textNewGroup']));
  $db->query($query);
}

// Renommage du groupe
if ($_POST['form'] == 'frmGroup' && $_SESSION['admin'] == '1' && $_POST['renameGroup'] == 'Renommer' && !empty($_POST['selectedGroup']) && trim($_POST['textGroup']) != '') {
  $query = $db->writeQuery('UPDATE crz_groupe SET lib_groupe = %s WHERE id_groupe = %d', trim($_POST['textGroup']), (int) $_POST['selectedGroup']);
  $db->query($query);
}

// Suppression du groupe
if ($_POST['form'] == 'frmGroup' && $_SESSION['admin'] == '1' && $_POST['deleteGroups'] == 'Supprimer' && !empty($_POST['selectedGroups'])) {
  $query = 'DELETE FROM crz_groupe WHERE id_groupe IN ('.implode(',', $_POST['selectedGroups']).')';
  $db->query($query);
}

$db->close();

header('Location: admin.php');
exit();
?>