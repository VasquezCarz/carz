<?php
session_start();

$_SESSION['login'] = $_POST['login'];
$_SESSION['password1'] = $_POST['password1'];
$_SESSION['password2'] = $_POST['password2'];
$_SESSION['nom'] = $_POST['nom'];
$_SESSION['prenom'] = $_POST['prenom'];
$_SESSION['mail'] = $_POST['mail'];

if (empty($_POST['login']) || empty($_POST['password1']) || empty($_POST['password2']) || empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['mail'])) {
  $_SESSION['msg'] = '<span class="error">Veuillez renseigner les champs marqués d\'une astérisque !</span>';
  header('Location: user_registration.php');
  exit();
}

include 'config/carz.conf.php';
include PATH_SCRIPTS.'/php/Database.class.php';
include PATH_SCRIPTS.'/php/util.inc.php';
require_once(PATH_SCRIPTS.'/php/User.class.php');

$db = new Database();
$db->connect();

$query = 'SELECT id_utilisateur FROM crz_utilisateur WHERE UPPER(login) = %s';
$query = $db->writeQuery($query, trim(strtoupper($_POST['login'])));
if($result = $db->query($query)){
	if($user= $result->fetch_object('User')){
		$_SESSION['msg'] = '<span class="error">Ce login existe déjà !</span>';
		$db->close();
		header('Location: user_registration.php');
		exit();
	}
}
	
/*$result = $db->query($query);
if ($db->numRows($result) > 0) {
  $_SESSION['msg'] = '<span class="error">Ce login existe déjà !</span>';
  $db->close();
  header('Location: user_registration.php');
  exit();
}*/

if ($_POST['password1'] != $_POST['password2']) {
  $_SESSION['msg'] = '<span class="error">Les mots de passe ne correspondent pas !</span>';
  $_SESSION['password1'] = '';
  $_SESSION['password2'] = '';
  $db->close();
  header('Location: user_registration.php');
  exit();
}

$_SESSION['hash_activation'] = md5(random_str());

$query = 'INSERT INTO crz_utilisateur (login, password, nom, prenom, mail, hash_activation) VALUES (%s, %s, %s, %s, %s, %s)';
$query = $db->writeQuery($query, trim($_POST['login']), md5($_POST['password1']), $_POST['nom'], $_POST['prenom'], $_POST['mail'], $_SESSION['hash_activation']);
$db->query($query);

$_SESSION['id_utilisateur'] = $db->getInsertId();

$db->close();

// envoyer le mail d'activation
header('Location: user_send_activation.php');
exit();
?>