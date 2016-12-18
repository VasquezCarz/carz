<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Carz - Index</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="UTF-8" />
    <meta name="title" content="Carz" />
    <meta name="author" content="Vasquez" />
    <meta name="language" content="fr" />
    <meta name="keywords" content="vasquez, audi, friends, club" />
    <meta name="robots" content="index, follow" />
    <!--<link rel="icon" type="image/png" href="graphics/favicon.png" />-->
    <link rel="stylesheet" type="text/css" href="scripts/css/style.css" />
  </head>

  <body>
    <?php
    include 'config/carz.conf.php';
    include PATH_SCRIPTS.'/php/Database.class.php';
    require_once(PATH_SCRIPTS.'/php/Groupe.class.php');
    require_once(PATH_SCRIPTS.'/php/Voiture.class.php');
    require_once(PATH_SCRIPTS.'/php/MyLogPHP.class.php');
    ?>
    
    <header>
      <?php include 'header.inc.php'; ?>
    </header>
    
    <nav>
      Index
      <?php include 'nav.inc.php'; ?>
    </nav>
    
    <section>
      <?php
      $db = new Database();
      $db->connect();
      $log = new MyLogPHP('./log/debug.log.csv', ';');
      ?>
      <fieldset>
        <legend>
          <form name="frmGroup" action="index.php" method="post">
            Groupe :
            <select name="group" onchange="document.frmGroup.submit();">
              <?php
              // Requête sur les groupes
              $query = 'SELECT id_groupe as id, lib_groupe as libelle FROM crz_groupe ORDER BY lib_groupe';
              if ($result = $db->query($query)) {
                //$log->debug('Before groupe fetching');
                $first_group = 0;
                $group = empty($_POST['group']) ? 0 : $_POST['group'];
                
                $i = 0;
                while ($groupe = $result->fetch_object('Groupe')) {
                  if ($i == 0) $first_group = $groupe->id;              
                  echo '<option value="', $groupe->id, '"', $groupe->id == $group ? ' selected="selected"' : '', '>', $groupe->libelle, '</option>', "\n";
                  $i++;           
                }
              }
              ?>
            </select>
          </form>
        </legend>
      
        <table class="listing border">
          <tr><th>Marque</th><th>Modèle</th><th>Année</th><th>Code</th><th>Motorisation</th><th>Energie</th><th>Puiss.<br />(ch)</th><th>Couple<br />(N.m)</th><th>Boîte</th><th>Propriétaire</th></tr>
          <?php
          $id_groupe = empty($_POST['group']) ? $first_group : $_POST['group'];
          
          $query = 'SELECT v.id_voiture, v.lib_voiture, v.annee, ma.lib_marque, m.lib_modele, c.lib_code, mot.lib_motorisation, mot.energie, p.puissance, p.couple, b.lib_boite, u.id_utilisateur, u.login';
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
          $query = $db->writeQuery($query, (int) $id_groupe);
          
          if ($result = $db->query($query)) {
            while ($voiture = $result->fetch_object('Voiture')) {           
              $admin = empty($_SESSION['admin']) ? 0 : $_SESSION['admin'];
              
              echo '<tr><td>', $voiture->lib_marque, '</td><td>', $voiture->lib_modele, '</td><td>', $voiture->annee, '</td><td>', $voiture->lib_code,
                '</td><td>', $voiture->lib_motorisation, '</td><td>', $voiture->energie, '</td><td>', $voiture->puissance, '</td><td>', $voiture->couple,
                '</td><td>', $voiture->lib_boite, '</td><td><img class="avatar" src="uploads/',
                str_pad($voiture->id_utilisateur, 10, '0', STR_PAD_LEFT), '/thumbnail.jpg" /> <span title="', $voiture->lib_voiture, '">', $voiture->login, '</span></td>',
                $admin == '1' ? '<td><a href="profile_car_edit.php?car_id='.$voiture->id_voiture.'"><img src="graphics/pencil.png" /></a></td>' : '',
                '</tr>', "\n";
            }
          }
          ?>
        </table>
      </fieldset>
      <?php
      $db->close();
      ?>
    </section>
    
    <footer>
      <?php include 'footer.inc.php'; ?>
    </footer>
  </body>
</html>