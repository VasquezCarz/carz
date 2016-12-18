<?php
// Démarrage ou restauration de la session
session_start();

// Importation du fichier de conf et de la classe 'Database'
include('config/carz.conf.php');
include(PATH_SCRIPTS.'/php/Database.class.php');
require_once(PATH_SCRIPTS.'/php/User.class.php');

$db = new Database();
$db->connect();
$query = 'SELECT id_utilisateur, login, password, mail, admin FROM crz_utilisateur WHERE login = %s';
$query = $db->writeQuery($query, $_POST['login']);

if ($result = $db->query($query)) {
	if (($user= $result->fetch_object('User')) && (md5($_POST['password']) == $user->password)) {
		$_SESSION['id_utilisateur'] = $user->id_utilisateur;
		$_SESSION['login'] = $user->login;
		$_SESSION['mail'] = $user->mail;
		$_SESSION['admin'] = $user->admin;
	}
  else {
		$_SESSION['msg'] = '<span class="error">Login ou mot de passe erroné !</span>';
	}
}

$db->close();

header('Location: index.php');
exit();
?>