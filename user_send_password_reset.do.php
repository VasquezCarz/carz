<?php
session_start();

include 'config/carz.conf.php';
include PATH_SCRIPTS.'/php/Database.class.php';
include PATH_SCRIPTS.'/php/util.inc.php';
  
$db = new Database();
$db->connect();

// TODO
if (trim($_POST['txtMail']) != '') {
  $query = 'SELECT id_utilisateur, login, mail FROM crz_utilisateur WHERE mail = %s';
  $query = $db->writeQuery($query, trim($_POST['txtMail']));
  $result = $db->query($query);
  $num_rows = $db->numRows($result);
  $data = $db->fetchAssoc($result);
  if ($num_rows == 1) {
    $date_expiration = date('Y-m-d H:i:s', strtotime('+24 hours'));
    $hash = md5(random_str());
    
    $query = 'UPDATE crz_utilisateur SET date_expiration = %s, hash_reset = %s WHERE id_utilisateur = %d';
    $query = $db->writeQuery($query, $date_expiration, $hash, (int) $data['id_utilisateur']);
    $db->query($query);
    
    //------- Mail -------------------------------------------------------------
    $to = $data['mail'];
    $subject = 'Réinitialisation de vos identifiants de connexion sur l\'application Web "Carz"';
    $body = sprintf(file_get_contents('mails/password_reset.htm'), $_SESSION['login'], BASE_URL.'/user_password_reset.php?user_id='.$data['id_utilisateur'].'&hash='.$hash);
    
    $headers  = 'MIME-Version: 1.0'."\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";
    mail($to, $subject, $body, $headers);
    //--------------------------------------------------------------------------
  }
  else {
    $_SESSION['msg'] = '<span class="error">Cette adresse e-mail n\'existe pas dans la base de données !</span>';
  }
}

$db->close();

header('Location: index.php');
exit();
?>