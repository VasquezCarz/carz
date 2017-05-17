<!DOCTYPE html>
<html>
  <head>
  </head>
  <body>
    <?php
    include 'config/carz.conf.php';
    include PATH_SCRIPTS.'/php/Database.class.php';
    include PATH_SCRIPTS.'/php/util.inc.php';
    
    $db = new Database();
    $db->connect();
    
    echo $db->writeQuery('SELECT id FROM table WHERE num = %d AND lib LIKE %s', 3, '%toto%');
    
    $db->close();
    
    echo '<br />', date('Y-m-d H:i:s', strtotime('+24 hours'));
    
    echo '<br />', file_get_contents('mails/activation.htm');
    
    echo '<br />', BASE_URL;
    
    $str = random_str();
    echo '<br />', $str, ' --> ', md5($str);
    
    $var = array('toto', 'titi', 'tata');
    echo '<br />count($var) = ', count($var);
    
    $var = '1984';
    echo '<br />is_numeric($var) = ', is_numeric($var);
    
    $var1 = '3';
    $var2 = '7';
    echo '<br />$var1 * $var2 = ', $var1 * $var2;
    
    echo '<br />__DIR__ = ', __DIR__;
    
    /*
    $to = 'toto.vasquez@yopmail.com';
    $subject = 'Activation de votre compte sur l\'application Web "Carz"';
    $message = sprintf(file_get_contents('mails/activation.htm'), $_POST['login'], BASE_URL.'/activate.php?user_id=toto&hash='.md5(random_str()));
    $headers  = 'MIME-Version: 1.0'."\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";
    mail($to, $subject, $message, $headers);
    */
    ?>
    <br /><br />
    <form name="frmTest" action="test.php" method="post">
      <div style="width: 100px; height: 100px; overflow-y: scroll;">
        <?php
        if (empty($_POST['selectedValues'])) $_POST['selectedValues'] = array();
        for ($i = 0; $i < 10; $i++) {
          $value = 'value'.$i;
          echo '<input type="checkbox" id="chk', $i, '" name="selectedValues[]" value="', $value, '"', (in_array($value, $_POST['selectedValues']) ? ' checked="checked"' : ''),' /><label for="chk', $i, '">', $value, '</label><br />', "\n";
        }
        ?>
      </div>
      <input type="submit" name="submitForm" value="OK" />
    </form>
    <?php
    //echo 'selectedValues = ', implode($_POST['selectedValues']);
    print_r($_POST['selectedValues']);
    ?>
  </body>
</html>