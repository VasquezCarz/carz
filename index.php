<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Carz - Accueil</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="UTF-8" />
    <meta name="title" content="Carz" />
    <meta name="author" content="Vasquez" />
    <meta name="language" content="fr" />
    <meta name="keywords" content="vasquez, audi, friends, club" />
    <meta name="robots" content="noindex, nofollow" />
    <!--<link rel="icon" type="image/png" href="graphics/favicon.png" />-->
    <link rel="stylesheet" type="text/css" href="scripts/css/style.css" />
    <!-- Add jQuery library -->
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <!-- Add fancyBox -->
    <link rel="stylesheet" href="scripts/js/fancyBox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <script type="text/javascript" src="scripts/js/fancyBox/jquery.fancybox.pack.js?v=2.1.5"></script>
  </head>

  <body id="home">
    
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
        //$log = new MyLogPHP('./logs/debug.log.csv', ';');
        $query = 'SELECT fk_groupe, admin_groupe FROM crz_groupe_utilisateur WHERE fk_utilisateur = %d';
        $query = $db->writeQuery($query, (int) $_SESSION['id_utilisateur']);
        $result = $db->query($query);
        if ($result->num_rows == 0) {
          echo 'Demandez à un administrateur du site de vous ajouter dans un groupe...';
        }
        else {
      ?>
      
        <table class="listing border">
          <tr>
            <th style="border-left: none; border-top: none;"></th>
            <th>Marque</th>
            <th>Modèle</th>
            <th>Année</th>
            <th>Code</th>
            <th>Motorisation</th>
            <th>Energie</th>
            <th>Puiss.<br />(ch)</th>
            <th>Couple<br />(N.m)</th>
            <th>Boîte</th>
            <th colspan="2">Propriétaire</th>
          </tr>
          <?php
          $query = 'SELECT v.id_voiture, v.lib_voiture, v.annee, ma.lib_marque, m.lib_modele, c.lib_code, mot.lib_motorisation, mot.energie, p.puissance, p.couple, b.lib_boite, u.id_utilisateur, u.login, u.prenom, u.prenom';
          $query .= ' FROM crz_voiture v';
          $query .= ' INNER JOIN crz_modele m ON v.fk_modele = m.id_modele';
          $query .= ' INNER JOIN crz_marque ma ON m.fk_marque = ma.id_marque';
          $query .= ' INNER JOIN crz_code c ON v.fk_code = c.id_code';
          $query .= ' INNER JOIN crz_boite b ON v.fk_boite = b.id_boite';
          $query .= ' INNER JOIN crz_utilisateur u ON v.fk_utilisateur = u.id_utilisateur';
          $query .= ' INNER JOIN crz_groupe_voiture gv ON v.id_voiture = gv.fk_voiture';
          $query .= ' LEFT OUTER JOIN crz_puissance p ON v.fk_puissance = p.id_puissance';
          $query .= ' LEFT OUTER JOIN crz_motorisation mot ON p.fk_motorisation = mot.id_motorisation';
          $query .= ' WHERE gv.fk_groupe = %d';
          $query .= ' ORDER BY ma.lib_marque, c.id_code, m.id_modele, u.login';
          $query = $db->writeQuery($query, (int) $_SESSION['group']);
          
          if ($result = $db->query($query)) {
            while ($voiture = $result->fetch_object('Voiture')) {
              $ava_src = 'uploads/'.str_pad($voiture->id_utilisateur, 10, '0', STR_PAD_LEFT).'/avatar.jpg';
              $car_src = 'uploads/'.str_pad($voiture->id_utilisateur, 10, '0', STR_PAD_LEFT).'/car'.str_pad($voiture->id_voiture, 10, '0', STR_PAD_LEFT).'.jpg';
              $admin = empty($_SESSION['admin']) ? 0 : $_SESSION['admin'];
              
              echo '<tr>',
                '<td><a class="fancybox" href="', $car_src, '" title="', $voiture->lib_voiture, '">',
                '<img class="avatar" src="', $car_src, '" onerror="this.src=\'graphics/default.png\'" /></a></td>',
                '<td>', $voiture->lib_marque, '</td><td>', $voiture->lib_modele, '</td><td>', $voiture->annee, '</td>',
                '<td>', $voiture->lib_code, '</td><td>', $voiture->lib_motorisation, '</td><td>', $voiture->energie, '</td>',
                '<td>', $voiture->puissance, '</td><td>', $voiture->couple, '</td><td>', $voiture->lib_boite, '</td>',
                '<td style="border-right: none; text-align: right;"><a class="fancybox" href="', $ava_src, '">',
                '<img class="avatar" src="', $ava_src, '" onerror="this.src=\'graphics/default.png\'" /></a></td>',
                '<td style="border-left: none;">', $voiture->login, '<br /><i>', $voiture->prenom, '</i></td>',
                $admin == '1' ? '<td><a href="profile_car_edit.php?car_id='.$voiture->id_voiture.'"><img  src="graphics/pencil.png" /></a></td>' : '',
                '</tr>', "\n";
            }
          }
          ?>
        </table>
      <?php
        }
      }
      ?>
    </section>
    
    <script type="text/javascript">
      $(document).ready(function() {
        $(".fancybox").fancybox({
          helpers: {
            title: { type: 'inside' }
          }
        });
      });
    </script>
    
    <footer>
      <?php include 'footer.inc.php'; ?>
    </footer>
  </body>
</html>