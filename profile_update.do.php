<?php
session_start();
if (empty($_SESSION['id_utilisateur'])) {
  $_SESSION['msg'] = '<span class="error">La session a expiré !</span>';
  header('Location: index.php');
  exit();
}

if (trim($_POST['login']) == '') {
  $_SESSION['msg'] = '<span class="error">Le login ne doit pas être vide !</span>';
  header('Location: profile.php');
  exit();
}

include 'config/carz.conf.php';
include PATH_SCRIPTS.'/php/Database.class.php';

$db = new Database();
$db->connect();

$query = 'SELECT id_utilisateur FROM crz_utilisateur WHERE id_utilisateur <> %d AND UPPER(login) = %s';
$query = $db->writeQuery($query, (int) $_SESSION['id_utilisateur'], trim(strtoupper($_POST['login'])));
$result = $db->query($query);
if ($db->numRows($result) > 0) {
  $_SESSION['msg'] = '<span class="error">Ce login existe déjà !</span>';
  $db->close();
  header('Location: profile.php');
  exit();
}

if (!empty($_POST['password1']) || !empty($_POST['password2'])) {
  if ($_POST['password1'] != $_POST['password2']) {
    $_SESSION['msg'] = '<span class="error">Les mots de passe ne correspondent pas !</span>';
    $db->close();
    header('Location: profile.php');
    exit();
  }
  else {
    $query = 'UPDATE crz_utilisateur SET password = %s WHERE id_utilisateur = %d';
    $query = $db->writeQuery($query, md5($_POST['password1']), (int) $_SESSION['id_utilisateur']);
    $db->query($query);
  }
}

$query = 'UPDATE crz_utilisateur SET login = %s, nom = %s, prenom = %s, mail = %s WHERE id_utilisateur = %d';
$query = $db->writeQuery($query, trim($_POST['login']), $_POST['nom'], $_POST['prenom'], trim($_POST['mail']), (int) $_SESSION['id_utilisateur']);
$db->query($query);

$db->close();

$_SESSION['msg'] = '<span class="success">Votre compte a bien été enregistré.</span>';
header('Location: profile.php');
exit();
?>