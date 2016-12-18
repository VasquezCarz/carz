<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Carz</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="UTF-8" />
    <meta name="title" content="Carz" />
    <meta name="author" content="Vasquez" />
    <meta name="language" content="fr" />
    <meta name="keywords" content="vasquez, audi, friends, club" />
    <meta name="robots" content="index, follow" />
    <!--<link rel="icon" type="image/png" href="graphics/favicon.png" />-->
    <link rel="stylesheet" type="text/css" href="scripts/css/style.css" />
	<script src="scripts/js/Chart.min.js"></script>
  </head>

  <body>
    <?php
    include 'config/carz.conf.php';
    require_once(PATH_SCRIPTS.'/php/Database.class.php');
    require_once(PATH_SCRIPTS.'/php/User.class.php');	
    
    ?>
    <header>
      <?php include "header.inc.php"; ?>
    </header>
    
    <nav>
      
      <?php include 'nav.inc.php'; ?>
      <h2>Trombinoscope</h2>
    </nav>
    
    <section>
	  <table>
	  <?php
        $db = new Database();
        $db->connect();
	    $query = 'SELECT login FROM crz_utilisateur';
        if ($result = $db->query($query)) {
          $i = 0;
          while ($user = $result->fetch_object('User')) {
            if($i%3 == 0) echo "<tr>";
            echo "<td style=\"width:100px;border:1px gray dotted; padding:10px;\"><img style=\"width:50px;margin-left:25px;\" src=\"graphics/people.png\"/><p style=\"text-align:center;\">" . $user->login . "</p></td>";
            if($i%3 == 2) echo "</tr>\n";
            $i++;
          }
        }
	  ?>
      </table>
    </section>
		
    <footer>
      <?php include "footer.inc.php"; ?>
    </footer>
  </body>
</html>