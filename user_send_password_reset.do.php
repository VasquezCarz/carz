<?php
session_start();

include 'config/carz.conf.php';
include PATH_SCRIPTS.'/php/Database.class.php';
include PATH_SCRIPTS.'/php/util.inc.php';
  
$db = new Database();
$db->connect();

if (trim($_POST['txtMail']) != '') {
  $query = 'SELECT id_utilisateur, login, mail FROM crz_utilisateur WHERE mail = %s';
  $query = $db->writeQuery($query, trim($_POST['txtMail']));
  $result = $db->query($query);
  $num_rows = $result->num_rows;
  $data = $result->fetch_assoc();
  if ($num_rows == 1) {
    $date_expiration = date('Y-m-d H:i:s', strtotime('+24 hours'));
    $hash = md5(random_str());
    
    $query = 'UPDATE crz_utilisateur SET date_expiration = %s, hash_reset = %s WHERE id_utilisateur = %d';
    $query = $db->writeQuery($query, $date_expiration, $hash, (int) $data['id_utilisateur']);
    $db->query($query);
    
    //------- Mail -------------------------------------------------------------
    $to = $data['mail'];
    $subject = 'Réinitialisation de vos identifiants de connexion sur l\'application Web "Carz"';
    $body = sprintf(file_get_contents('mails/password_reset.htm'), $_SESSION['login'], BASE_URL.'/user_password_reset.do.php?user_id='.$data['id_utilisateur'].'&hash='.$hash);
    
    $headers  = 'MIME-Version: 1.0'."\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";
    mail($to, $subject, $body, $headers);
    
    /*
    require PATH_SCRIPTS.'/php/PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer;
    
    //$mail->SMTPDebug = 3;                               // Enable verbose debug output
    
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.free.fr';                         // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'carzclub@free.fr';                 // SMTP username
    $mail->Password = 'fe1-l0ng';                         // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to
    
    $mail->setFrom('carzclub@free.fr', 'Carz Mailer');
    $mail->addAddress($to);                               // Add a recipient
    
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->CharSet = 'UTF-8';                             // Set charset to UTF-8
    
    $mail->Subject = $subject;
    $mail->Body = $body;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
    if (!$mail->send()) {
      $_SESSION['msg'] = '<span class="error">Mailer Error: '.$mail->ErrorInfo.'</span>';
    }
    else {
      $_SESSION['msg'] = '<span class="success">Le message de réinitialisation des identifiants de connexion a été envoyé.</span>';
    }
    */
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