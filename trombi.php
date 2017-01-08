<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Carz - Trombinoscope</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="UTF-8" />
    <meta name="title" content="Carz" />
    <meta name="author" content="Vasquez" />
    <meta name="language" content="fr" />
    <meta name="keywords" content="vasquez, audi, friends, club" />
    <meta name="robots" content="index, follow" />
    <!--<link rel="icon" type="image/png" href="graphics/favicon.png" />-->
    <link rel="stylesheet" type="text/css" href="scripts/css/style.css" />
    <!-- Add jQuery library -->
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <!-- Add fancyBox -->
    <link rel="stylesheet" href="scripts/js/fancyBox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <script type="text/javascript" src="scripts/js/fancyBox/jquery.fancybox.pack.js?v=2.1.5"></script>
  </head>

  <body id="trombi">
    <?php
    include 'config/carz.conf.php';
    require_once(PATH_SCRIPTS.'/php/Database.class.php');
    require_once(PATH_SCRIPTS.'/php/User.class.php');     
    ?>
    <header>
      <?php include 'header.inc.php'; ?>
    </header>
    
    <nav>      
      <?php include 'nav.inc.php'; ?>
    </nav>
    
    <section>
      <?php
      if (empty($_SESSION['id_utilisateur'])) {
        echo 'Veuillez vous authentifier pour accéder au contenu...';
      }
      else {
        $db = new Database();
        $db->connect();
        
        $query = 'SELECT fk_groupe, admin_groupe FROM crz_groupe_utilisateur WHERE fk_utilisateur = %d';
        $query = $db->writeQuery($query, (int) $_SESSION['id_utilisateur']);
        $result = $db->query($query);
        if ($result->num_rows == 0) {
          echo 'Demandez à un administrateur du site de vous ajouter dans un groupe...';
        }
        else {
      ?>
      <table>
        <?php
        $query = 'SELECT id_utilisateur, login, prenom FROM crz_utilisateur';
        $query .= ' WHERE id_utilisateur IN (SELECT fk_utilisateur FROM crz_groupe_utilisateur)';
        $query .= ' ORDER BY login';
        if ($result = $db->query($query)) {
          $i = 0;
          while ($user = $result->fetch_object('User')) {
            $src = 'uploads/'.str_pad($user->id_utilisateur, 10, '0', STR_PAD_LEFT).'/portrait.jpg';
            if ($i % 5 == 0) echo "<tr>";
            echo '<td style="width: 130px; border: 1px gray dotted; padding: 10px;">',
              '<a class="fancybox" href="', $src, '">',
              '<img style="width: 130px;" src="', $src, '" onerror="this.src=\'graphics/portrait_default.jpg\'" /></a>',
              '<div style="text-align: center;">', $user->login, '<br /><i>', $user->prenom, '</i></div></td>';
            if ($i % 5 == 4) echo "</tr>\n";
            $i++;
          }
        }
        ?>
      </table>
      <?php
        }
        $db->close();
      }
      ?>
    </section>
    
    <script type="text/javascript">
      $(document).ready(function() {
        $(".fancybox").fancybox();
      });
    </script>
    
    <footer>
      <?php include 'footer.inc.php'; ?>
    </footer>
  </body>
</html>