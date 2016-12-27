<?php
session_start();
if (empty($_SESSION['id_utilisateur'])) {
  $_SESSION['msg'] = '<span class="error">La session a expiré !</span>';
  header('Location: index.php');
  exit();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Carz - Profil</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="scripts/css/style.css" />
    <!-- Add jQuery library -->
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <!-- Add fancyBox -->
    <link rel="stylesheet" href="scripts/js/fancyBox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <script type="text/javascript" src="scripts/js/fancyBox/jquery.fancybox.pack.js?v=2.1.5"></script>
  </head>
  
  <body>
  	<header>
  		<?php include 'header.inc.php'; ?>
  	</header>
    
    <nav>
      <?php include 'nav.inc.php'; ?>
      <h2>Profil</h2>
    </nav>
    
    <section>
      <?php
      include 'config/carz.conf.php';
      include PATH_SCRIPTS.'/php/Database.class.php';
      require_once(PATH_SCRIPTS.'/php/User.class.php');
      require_once(PATH_SCRIPTS.'/php/Voiture.class.php');
      
      $db = new Database();
      $db->connect();
      
      $query = $db->writeQuery('SELECT login, nom, prenom, mail, hash_activation FROM crz_utilisateur WHERE id_utilisateur = %d', (int) $_SESSION['id_utilisateur']);
      if ($result = $db->query($query)) {
        if ($user = $result->fetch_object('User')) {
          $dir = 'uploads/'.str_pad($_SESSION['id_utilisateur'], 10, '0', STR_PAD_LEFT);
      ?>
      
          <fieldset>
            <legend>Photos</legend>
            <form action="profile_upload.do.php" method="post" enctype="multipart/form-data">        
              <table>
                <tr>
                  <td><a class="fancybox" href="<?php echo $dir; ?>/avatar.jpg"><img src="<?php echo $dir; ?>/avatar.jpg" height="100" /></a></td>
                  <td><a class="fancybox" href="<?php echo $dir; ?>/portrait.jpg"><img src="<?php echo $dir; ?>/portrait.jpg" height="100" /></a></td>
                  <td><a class="fancybox" href="<?php echo $dir; ?>/car.jpg"><img src="<?php echo $dir; ?>/car.jpg" height="100" /></a></td>
                </tr>
                <tr>
                  <td><input type="radio" id="ava" name="picture" value="avatar" checked="checked" /><label for="ava">Avatar</label></td>
                  <td><input type="radio" id="por" name="picture" value="portrait" /><label for="por">Portrait</label></td>
                  <td><input type="radio" id="car" name="picture" value="car" /><label for="car">Voiture</label></td>
                </tr>
              </table>
              <input type="file" name="fileToUpload" id="fileToUpload" />
              <input type="submit" value="Télécharger l'image" name="submit" />
              <span class="info">Taille max : 2 Mo</span>
            </form>
          </fieldset>
          <br />
      
          <fieldset>
            <legend>Infos personnelles</legend>
            <form name="frmProfile" action="profile_update.do.php" method="post">  
              <table>
                <tr>
                  <td>Login</td>
                  <td><input type="text" name="login" value="<?php echo $user->login; ?>" /></td>
                </tr>
                <tr>
                  <td>Mot de passe</td>
                  <td><input type="password" name="password1" /></td>
                </tr>
                <tr>
                  <td>Confirmer le mot de passe</td>
                  <td><input type="password" name="password2" /></td>
                </tr>
                <tr>
                  <td>Nom</td>
                  <td><input type="text" name="nom" value="<?php echo $user->nom; ?>" /></td>
                </tr>
                <tr>
                  <td>Prénom</td>
                  <td><input type="text" name="prenom" value="<?php echo $user->prenom; ?>" /></td>
                </tr>
                <tr>
                  <td>E-mail</td>
                  <td><input type="text" name="mail" value="<?php echo $user->mail; ?>" /></td>
                </tr>
                <tr>
                  <td colspan="2" align="right"><input type="submit" value="Enregistrer" /></td>
                </tr>
              </table>
            </form>
            <?php
            if ($user->hash_activation != '') {
              $_SESSION['login'] = $user->login;
              $_SESSION['hash_activation'] = $user->hash_activation;
              $_SESSION['mail'] = $user->mail;
              echo '[<a href="user_send_activation.do.php">Renvoyer le mail d\'activation</a>]';
            }
            ?>
          </fieldset>
      <?php
        }
      }
      ?>
      <br />
      <fieldset>
        <legend>Mes voitures</legend>
        [<a href="profile_car.php">Ajouter une voiture</a>]
        <br />
        <table class="border listing">
          <tr><th>Marque</th><th>Modèle</th><th>Année</th><th>Code</th><th>Motorisation</th><th>Puissance (ch)</th><th>Couple (N.m)</th><th>Boîte</th><th>Libellé</th></tr>
          <?php
          $query = 'SELECT v.id_voiture, v.lib_voiture, v.annee, ma.lib_marque, m.lib_modele, c.lib_code, mot.lib_motorisation, p.puissance, p.couple, b.lib_boite';
          $query .= ' FROM crz_voiture v';
          $query .= ' INNER JOIN crz_modele m ON v.fk_modele = m.id_modele';
          $query .= ' INNER JOIN crz_marque ma ON m.fk_marque = ma.id_marque';
          $query .= ' INNER JOIN crz_code c ON v.fk_code = c.id_code';
          $query .= ' INNER JOIN crz_boite b ON v.fk_boite = b.id_boite';
          $query .= ' LEFT OUTER JOIN crz_puissance p ON v.fk_puissance = p.id_puissance';
          $query .= ' LEFT OUTER JOIN crz_motorisation mot ON p.fk_motorisation = mot.id_motorisation';
          $query .= ' WHERE v.fk_utilisateur = %d';
          $query .= ' ORDER BY ma.lib_marque, c.id_code, m.id_modele';
          $query = $db->writeQuery($query, (int) $_SESSION['id_utilisateur']);
          
          if ($result = $db->query($query)) {
            while ($voiture = $result->fetch_object('Voiture')) {
              echo '<tr><td>', $voiture->lib_marque, '</td><td>', $voiture->lib_modele, '</td><td>', $voiture->annee, '</td><td>',
                $voiture->lib_code, '</td><td>', $voiture->lib_motorisation, '</td><td>', $voiture->puissance, '</td><td>',
                $voiture->couple, '</td><td>', $voiture->lib_boite, '</td><td>', $voiture->lib_voiture, '</td>',
                '<td><a href="profile_car_edit.php?car_id=', $voiture->id_voiture, '"><img src="graphics/pencil.png" alt="Modifier" title="Modifier" /></a></td>',
                '<td><a href="profile_car_delete.do.php?car_id=', $voiture->id_voiture, '">',
                '<img src="graphics/delete.png" alt="Supprimer" title="Supprimer" onclick="return confirm(\'Êtes-vous sûr de supprimer cette voiture ?\');" />',
                '</a></td></tr>', "\n";
            }
          }
          ?>
        </table>
        <div class="info">Seules les voitures qui ne sont pas dans un groupe peuvent être supprimées.</div>
      </fieldset>
      <?php
      $db->close();
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