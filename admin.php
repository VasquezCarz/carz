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
    <title>Carz - Administration</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="scripts/css/style.css" />
  </head>
  
  <body>
    <header>
      <?php include "header.inc.php"; ?>      
    </header>
    
    <nav>      
      <?php include 'nav.inc.php'; ?>
      <h2>Administration</h2>
    </nav>
    
    <section>
      <?php
      include 'config/carz.conf.php';
      include PATH_SCRIPTS.'/php/Database.class.php';
      require_once(PATH_SCRIPTS.'/php/Groupe.class.php');
      require_once(PATH_SCRIPTS.'/php/User.class.php');
      require_once(PATH_SCRIPTS.'/php/Voiture.class.php');
      
      $db = new Database();
      $db->connect();
      ?>
      
      <fieldset>
        <legend>Groupes / Utilisateurs</legend>
        <form name="frmGroupUser" action="admin_update.do.php" method="post">
          <input type="hidden" name="form" value="frmGroupUser" />
          <select name="selectedGroup" onchange="document.frmGroupUser.submit();">
            <?php
            $select_options = '';
            $first_group = 0;
            $query = 'SELECT id_groupe, lib_groupe FROM crz_groupe';
            $query .= ' WHERE id_groupe IN (SELECT fk_groupe FROM crz_groupe_utilisateur WHERE fk_utilisateur = %d AND admin_groupe = 1)';
            $query .= ' OR 1 = %d';
            $query .= ' ORDER BY lib_groupe';
            $query = $db->writeQuery($query, (int) $_SESSION['id_utilisateur'], (int) $_SESSION['admin']);
            
            if ($result = $db->query($query)) {
              $i = 0;              
              while ($groupe = $result->fetch_object('Groupe')) {
                $id_groupe = $groupe->id_groupe;
                $lib_groupe = $groupe->lib_groupe;
                if ($i == 0) $first_group = $id_groupe;
                $select_options .= '<option value="'.$id_groupe.'"'.($id_groupe == $_SESSION['selectedGroup'] ? ' selected="selected"' : '').'>'.$lib_groupe.'</option>'."\n";
                $k++;
              }
              echo $select_options;
            }
            ?>
          </select>
          <br />
          <div class="select users">
            <?php
            $id_groupe = empty($_SESSION['selectedGroup']) ? $first_group : $_SESSION['selectedGroup'];
            
            $query1 = 'SELECT id_utilisateur, login FROM crz_utilisateur ORDER BY login';
            
            if ($result1 = $db->query($query1)) {
              $usersInGroup = array();
              $adminsInGroup = array();
              
              $query2 = $db->writeQuery('SELECT fk_utilisateur, admin_groupe FROM crz_groupe_utilisateur WHERE fk_groupe = %d', (int) $id_groupe);
              if ($result2 = $db->query($query2)) {
                $i = 0;
                while ($user2 = $result2->fetch_object('User')) {
                  $user = $user2->fk_utilisateur;
                  $usersInGroup[$i] = $user;
                  $adminsInGroup[$i] = ($user2->admin_groupe == 1 ? $user : 0);
                  $i++;
                }
              }
              
              $i = 0;
              while ($user1 = $result1->fetch_object('User')) {
                $id_utilisateur = $user1->id_utilisateur;
                $login = $user1->login;
                echo '<input type="checkbox" id="gu', $i, '" name="selectedUsers[]"',
                  ' value="', $id_utilisateur, '"', in_array($id_utilisateur, $usersInGroup) ? ' checked="checked"' : '', ' />',
                  '<label for="gu', $i, '">', $login, '</label>';
                echo ' (<input type="checkbox" name="selectedAdmins[]"', ' value="', $id_utilisateur, '"',
                  in_array($id_utilisateur, $adminsInGroup) ? ' checked="checked"' : '', ' />admin)<br />', "\n";                
                $i++;
              }
            }
            ?>
          </div>
          <input type="submit" name="saveUsersInGroup" value="Enregistrer" />
        </form>
        <div class="info">
          <ul>
            <li>Cochez les utilisateurs que vous souhaitez ajouter dans le groupe.</li>
            <li>Cochez la case "admin" des utilisateurs que vous souhaitez mettre admin du groupe.</li>
            <li>Puis cliquez sur le bouton "Enregistrer".</li>
          </ul>
        </div>
      </fieldset>
      
      <fieldset>
        <legend>Groupes / Voitures</legend>
        <form name="frmGroupCar" action="admin_update.do.php" method="post">
          <input type="hidden" name="form" value="frmGroupCar" />
          <select name="selectedGroup" onchange="document.frmGroupCar.submit();">
            <?php echo $select_options; ?>
          </select>
          <br />
          <div class="select cars">
            <?php
            $id_groupe = empty($_SESSION['selectedGroup']) ? $first_group : $_SESSION['selectedGroup'];
      
            $query3 = 'SELECT v.id_voiture, v.lib_voiture, u.login FROM crz_voiture v';
            $query3 .= ' INNER JOIN crz_utilisateur u ON v.fk_utilisateur = u.id_utilisateur';
            $query3 .= ' ORDER BY u.login, v.lib_voiture';
            
            if ($result3 = $db->query($query3)) {              
              $query4 = $db->writeQuery('SELECT fk_voiture FROM crz_groupe_voiture WHERE fk_groupe = %d', (int) $id_groupe);
              $carsInGroup = array();
              
              if ($result4 = $db->query($query4)) {
                $i = 0;
                while($voiture4 = $result4->fetch_object('Voiture')) {
                  $carsInGroup[$i] = $voiture4->fk_voiture;
                  $i++;
                }
              }
              
              $i = 0;
              while ($voiture3 = $result3->fetch_object('Voiture')) {
                $id_voiture = $voiture3->id_voiture;
                $lib_voiture = $voiture3->lib_voiture;
                $login = $voiture3->login;
                echo '<input type="checkbox" id="gc', $i, '" name="selectedCars[]"',
                  ' value="', $id_voiture, '"', in_array($id_voiture, $carsInGroup) ? ' checked="checked"' : '', ' />',
                  '<label for="gc', $i, '">[', $login, '] ', $lib_voiture, '</label><br />', "\n";
                $i++;
              }
            }
            ?>
          </div>
          <input type="submit" name="saveCarsInGroup" value="Enregistrer" />
        </form>
        <div class="info">
          <ul>
            <li>Cochez les voitures que vous souhaitez ajouter dans le groupe.</li>
            <li>Puis cliquez sur le bouton "Enregistrer".</li>
          </ul>
        </div>
      </fieldset>
      <br />
      
      <?php if ($_SESSION['admin'] == '1') { ?>
      <fieldset>
        <legend>Utilisateurs</legend>
        <form name="frmUser" action="admin_update.do.php" method="post">
          <input type="hidden" name="form" value="frmUser" />
          <div class="select users">
            <?php
            $usersInGroup = array();
            $query5 = 'SELECT id_utilisateur, login FROM crz_utilisateur ORDER BY login';
            if ($result5 = $db->query($query5)) {
              $query6 = 'SELECT DISTINCT fk_utilisateur FROM crz_groupe_utilisateur';
              if ($result6 = $db->query($query6)) {
                $i = 0;
                while ($user6 = $result6->fetch_object('User')) {
                  $usersInGroup[$i] = $user6->fk_utilisateur;
                  $i++;
                }
              }
              
              $i = 0;
              while ($user5 = $result5->fetch_object('User')) {
                $id_utilisateur = $user5->id_utilisateur;
                $login = $user5->login;
                echo '<input type="checkbox" id="u', $i, '" name="selectedUsers[]"', in_array($id_utilisateur, $usersInGroup) ? ' disabled="disabled"' : '',
                  ' value="', $id_utilisateur, '" /><label for="u', $i, '">', $login, '</label><br />', "\n"; 
                $i++;
              }
            }
            ?>
          </div>
          <input type="submit" name="deleteUsers" value="Supprimer" />
          <div class="info">
            <ul>
              <li>Cochez les utilisateurs que vous souhaitez supprimer.</li>
              <li>Puis cliquez sur le bouton "Supprimer".</li>
            </ul>
            Seuls les utilisateurs qui ne sont pas dans un groupe peuvent être supprimés.<br />
            Attention ! Les voitures des utilisateurs supprimés sont aussi supprimées.
          </div>
        </form>
      </fieldset>
      
      <fieldset>
        <legend>Voitures</legend>
        <form name="frmCar" action="admin_update.do.php" method="post">
          <input type="hidden" name="form" value="frmCar" />
          <div class="select cars">
            <?php
            $query7 = 'SELECT v.id_voiture, v.lib_voiture, u.login FROM crz_voiture v';
            $query7 .= ' INNER JOIN crz_utilisateur u ON v.fk_utilisateur = u.id_utilisateur';
            $query7 .= ' ORDER BY u.login, v.lib_voiture';
      
            $carsInGroup = array();
            if ($result7 = $db->query($query7)) {
              $query8 = 'SELECT DISTINCT fk_voiture FROM crz_groupe_voiture';
              if ($result8 = $db->query($query8)) {
                $i = 0;
                while ($voiture8 = $result8->fetch_object('Voiture')) {
                  $carsInGroup[$i] = $voiture8->fk_voiture;  
                  $i++;
                }
              }
              
              $i = 0;
              while($voiture7 = $result7->fetch_object('Voiture')) {
                $id_voiture = $voiture7->id_voiture;
                $lib_voiture = $voiture7->lib_voiture;
                $login = $voiture7->login;                
                echo '<input type="checkbox" id="c', $i, '" name="selectedCars[]"', in_array($id_voiture, $carsInGroup) ? ' disabled="disabled"' : '',
                ' value="', $id_voiture, '" /><label for="c', $i, '">[', $login, '] ', $lib_voiture, '</label><br />', "\n";
                $i++;
              }              
            }
            ?>
          </div>
          <input type="submit" name="deleteCars" value="Supprimer" />
          <div class="info">
            <ul>
              <li>Cochez les voitures que vous souhaitez supprimer.</li>
              <li>Puis cliquez sur le bouton "Supprimer".</li>
            </ul>
            Seules les voitures qui ne sont pas dans un groupe peuvent être supprimées.
          </div>
        </form>
      </fieldset>
      <br />
      
      <fieldset>
        <legend>Groupes</legend>
        <form name="frmGroup" action="admin_update.do.php" method="post">
          <input type="hidden" name="form" value="frmGroup" />
          <div class="select groups">
            <?php
            $query9 = 'SELECT id_groupe, lib_groupe FROM crz_groupe ORDER BY lib_groupe';
      
            $notEmptygroups = array();
            if ($result9 = $db->query($query9)) {
              $query10 = 'SELECT DISTINCT fk_groupe FROM crz_groupe_voiture';
              if ($result10 = $db->query($query10)) {
                $i = 0;
                while ($groupe10 = $result10->fetch_object('Groupe')) {
                  $notEmptyGroups[$i] = $groupe10->fk_groupe;
                  $i++;
                }
              }
              
              $i = 0;
              while ($groupe9 = $result9->fetch_object('Groupe')) {
                $id_groupe = $groupe9->id_groupe;
                $lib_groupe = stripslashes($groupe9->lib_groupe);
                echo '<input type="checkbox" id="g', $i, '" name="selectedGroups[]"',
                  in_array($id_groupe, $notEmptyGroups) ? ' disabled="disabled"' : '',
                  ' value="', $id_groupe, '" /><label for="g', $i, '" id="lbl', $i, '">', $lib_groupe, '</label>',
                  '<input type="radio" name="selectedGroup" value="', $id_groupe, '"',
                  ' onclick="document.frmGroup.textGroup.value = document.getElementById(\'lbl', $i, '\').textContent;" /><br />', "\n";
                $i++;
              }
            }
            ?>
          </div>
          <div class="floating-box">
            <input type="text" name="textNewGroup" />
            <input type="submit" name="createGroup" value="Créer" />
            <br />
            <input type="text" name="textGroup" />
            <input type="submit" name="renameGroup" value="Renommer" />
            <br /><br />
            <div align="left"><input type="submit" name="deleteGroups" value="Supprimer" /></div>
            <div class="info">
              <ul>
                <li>Entrez le libellé du nouveau groupe, puis cliquez sur "Créer".</li>
                <li>Sélectionnez le groupe (bouton radio) que vous souhaitez renommer, puis modifier le libellé, et enfin cliquez sur "Renommer".</li>
                <li>Cochez les groupes que vous souhaitez supprimer, puis cliquez sur "Supprimer".</li>
              </ul>
              Seuls les groupes qui ne contiennent pas de voiture peuvent être supprimés.
            </div>
          </div>
        </form>
      </fieldset>
      <?php
      }      
      $db->close();
      ?>
    </section>
    
    <footer>
      <?php include 'footer.inc.php'; ?>
    </footer>
  </body>
</html>