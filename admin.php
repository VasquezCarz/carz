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
    <title>Administration</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="scripts/css/style.css" />
  </head>
  
  <body>
    <header>
      <?php include "header.inc.php"; ?>      
    </header>
    
    <nav>
      Administration
      <?php include 'nav.inc.php'; ?>
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
      
      <br />
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
              $k = 0;
              
              while ($groupe = $result->fetch_object('Groupe')) {
                $id_groupe = $groupe->id_groupe;
                $lib_groupe = $groupe->lib_groupe;
                if ($k == 0) $first_group = $id_groupe;
                $select_options .= '<option value="'.$id_groupe.'"'.($id_groupe == $_SESSION['selectedGroup'] ? ' selected="selected"' : '').'>'.$lib_groupe.'</option>'."\n";
            /*$result = $db->query($query);
                  $num_rows = $db->numRows($result);
                  for ($i = 0; $i < $num_rows; $i++) {
                    $id_groupe = $db->result($result, $i, 'id_groupe');
                    if ($i == 0) $first_group = $id_groupe;
                    $lib_groupe = $db->result($result, $i, 'lib_groupe');
                    $select_options .= '<option value="'.$id_groupe.'"'.($id_groupe == $_SESSION['selectedGroup'] ? ' selected="selected"' : '').'>'.$lib_groupe.'</option>'."\n";
                  }*/
                $k++;
              }
              echo $select_options;
            }      
            ?>
          </select>
          <br />
          <div class="select users">
            <?php
            if (empty($_SESSION['selectedGroup']))
              $id_groupe = $first_group;
            else
              $id_groupe = $_SESSION['selectedGroup'];
            
            $query1 = 'SELECT id_utilisateur, login FROM crz_utilisateur ORDER BY login';
            
            if ($result1 = $db->query($query1)) {
              $usersInGroup = array();
              $adminsInGroup = array();
              
              $query2 = $db->writeQuery('SELECT fk_utilisateur, admin_groupe FROM crz_groupe_utilisateur WHERE fk_groupe = %d', (int) $id_groupe);
              if ($result2 = $db->query($query2)) {
                $j = 0;
                while($user2 = $result2->fetch_object('User')) {
                  $user = $user2->fk_utilisateur;
                  $usersInGroup[$j] = $user;
                  $adminsInGroup[$j] = ($user2->admin_groupe == 1 ? $user : 0);
                  $j++;
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
                  //in_array($id_utilisateur, $usersInGroup) ? '' : ' disabled="disabled"',
                  in_array($id_utilisateur, $adminsInGroup) ? ' checked="checked"' : '', ' />admin)<br />', "\n";                
                $i++;
              }              
            }
      
            /*$result1 = $db->query($query1);
            $num_rows1 = $db->numRows($result1);
            $query2 = $db->writeQuery('SELECT fk_utilisateur, admin_groupe FROM crz_groupe_utilisateur WHERE fk_groupe = %d', (int) $id_groupe);
            $result2 = $db->query($query2);
            $num_rows2 = $db->numRows($result2);
            $usersInGroup = array();
            $adminsInGroup = array();
            for ($i = 0; $i < $num_rows2; $i++) {
              $user = $db->result($result2, $i, 'fk_utilisateur');
              $usersInGroup[$i] = $user;
              $adminsInGroup[$i] = ($db->result($result2, $i, 'admin_groupe') == 1 ? $user : 0);
            }
            for ($i = 0; $i < $num_rows1; $i++) {
              $id_utilisateur = $db->result($result1, $i, 'id_utilisateur');
              $login = $db->result($result1, $i, 'login');
              echo '<input type="checkbox" id="gu', $i, '" name="selectedUsers[]"',
                ' value="', $id_utilisateur, '"', in_array($id_utilisateur, $usersInGroup) ? ' checked="checked"' : '', ' />',
                '<label for="gu', $i, '">', $login, '</label>';
              echo ' (<input type="checkbox" name="selectedAdmins[]"', ' value="', $id_utilisateur, '"',
                //in_array($id_utilisateur, $usersInGroup) ? '' : ' disabled="disabled"',
                in_array($id_utilisateur, $adminsInGroup) ? ' checked="checked"' : '', ' />admin)<br />', "\n";
            }*/
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
      
            $query1 = 'SELECT v.id_voiture, v.lib_voiture, u.login FROM crz_voiture v';
            $query1 .= ' INNER JOIN crz_utilisateur u ON v.fk_utilisateur = u.id_utilisateur';
            $query1 .= ' ORDER BY u.login, v.lib_voiture';
            
            if ($result6 = $db->query($query1)) {              
              $query2 = $db->writeQuery('SELECT fk_voiture FROM crz_groupe_voiture WHERE fk_groupe = %d', (int) $id_groupe);
              $carsInGroup = array();
              
              if ($result7 = $db->query($query2)) {
                $l = 0;
                while($voiture7 = $result7->fetch_object('Voiture')) {
                  $carsInGroup[$l] = $voiture7->fk_voiture;
                  $l++;
                }
              }
              
              $m = 0;
              while($voiture6 = $result6->fetch_object('Voiture')) {
                $id_voiture = $voiture6->id_voiture;
                $lib_voiture = $voiture6->lib_voiture;
                $login = $voiture6->login;
                 echo '<input type="checkbox" id="gc', $m, '" name="selectedCars[]"',
                  ' value="', $id_voiture, '"', in_array($id_voiture, $carsInGroup) ? ' checked="checked"' : '', ' />',
                  '<label for="gc', $m, '">[', $login, '] ', $lib_voiture, '</label><br />', "\n";
                $m++;
              }
            }
      
      /*$result1 = $db->query($query1);
            $num_rows1 = $db->numRows($result1);
            $query2 = $db->writeQuery('SELECT fk_voiture FROM crz_groupe_voiture WHERE fk_groupe = %d', (int) $id_groupe);
            $result2 = $db->query($query2);
            $num_rows2 = $db->numRows($result2);
            $carsInGroup = array();
            for ($i = 0; $i < $num_rows2; $i++) {
              $carsInGroup[$i] = $db->result($result2, $i, 'fk_voiture');
            }
            for ($i = 0; $i < $num_rows1; $i++) {
              $id_voiture = $db->result($result1, $i, 'id_voiture');
              $lib_voiture = $db->result($result1, $i, 'lib_voiture');
              $login = $db->result($result1, $i, 'login');
              echo '<input type="checkbox" id="gc', $i, '" name="selectedCars[]"',
                ' value="', $id_voiture, '"', in_array($id_voiture, $carsInGroup) ? ' checked="checked"' : '', ' />',
                '<label for="gc', $i, '">[', $login, '] ', $lib_voiture, '</label><br />', "\n";
            }*/
      
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
            $query1 = 'SELECT id_utilisateur, login FROM crz_utilisateur ORDER BY login';
            if ($result7 = $db->query($query1)) {
              $query2 = 'SELECT DISTINCT fk_utilisateur FROM crz_groupe_utilisateur';
              if ($result8 = $db->query($query2)) {
                $n4 = 0;
                while ($user8 = $result8->fetch_object('User')) {
                  $usersInGroup[$n4] = $user8->fk_utilisateur;
                  $n4++;
                }
              }
              
              $n = 0;
              while ($user9 = $result7->fetch_object('User')) {
                $id_utilisateur = $user9->id_utilisateur;
                $login = $user9->login;
                echo '<input type="checkbox" id="u', $n, '" name="selectedUsers[]"', in_array($id_utilisateur, $usersInGroup) ? ' disabled="disabled"' : '',
                  ' value="', $id_utilisateur, '" /><label for="u', $n, '">', $login, '</label><br />', "\n"; 
                $n++;
              }
            }
        
      /*$result1 = $db->query($query1);
            $num_rows1 = $db->numRows($result1);
            $query2 = 'SELECT DISTINCT fk_utilisateur FROM crz_groupe_utilisateur';
            $result2 = $db->query($query2);
            $num_rows2 = $db->numRows($result2);
            $usersInGroup = array();
            for ($i = 0; $i < $num_rows2; $i++) {
              $usersInGroup[$i] = $db->result($result2, $i, 'fk_utilisateur');
            }
            for ($i = 0; $i < $num_rows1; $i++) {
              $id_utilisateur = $db->result($result1, $i, 'id_utilisateur');
              $login = $db->result($result1, $i, 'login');
              echo '<input type="checkbox" id="u', $i, '" name="selectedUsers[]"', in_array($id_utilisateur, $usersInGroup) ? ' disabled="disabled"' : '',
                ' value="', $id_utilisateur, '" /><label for="u', $i, '">', $login, '</label><br />', "\n";
            }*/
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
            $query1 = 'SELECT v.id_voiture, v.lib_voiture, u.login FROM crz_voiture v';
            $query1 .= ' INNER JOIN crz_utilisateur u ON v.fk_utilisateur = u.id_utilisateur';
            $query1 .= ' ORDER BY u.login, v.lib_voiture';
      
            $carsInGroup = array();
            if ($result9 = $db->query($query1)) {
              $query2 = 'SELECT DISTINCT fk_voiture FROM crz_groupe_voiture';
              if ($result10 = $db->query($query2)) {
                $n2 = 0;
                while ($voiture8 = $result10->fetch_object('Voiture')) {
                  $carsInGroup[$n2] = $voiture8->fk_voiture;  
                  $n2++;
                }
              }
              
              $n1 = 0;
              while($voiture10 = $result9->fetch_object('Voiture')) {
                $id_voiture = $voiture10->id_voiture;
                $lib_voiture = $voiture10->lib_voiture;
                $login = $voiture10->login;
                
                echo '<input type="checkbox" id="c', $n1, '" name="selectedCars[]"', in_array($id_voiture, $carsInGroup) ? ' disabled="disabled"' : '',
                ' value="', $id_voiture, '" /><label for="c', $n1, '">[', $login, '] ', $lib_voiture, '</label><br />', "\n";       
                
                $n1++;
              }              
            }
      
            /*$result1 = $db->query($query1);
            $num_rows1 = $db->numRows($result1);
            $query2 = 'SELECT distinct fk_voiture FROM crz_groupe_voiture';
            $result2 = $db->query($query2);
            $num_rows2 = $db->numRows($result2);
            $carsInGroup = array();
            for ($i = 0; $i < $num_rows2; $i++) {
              $carsInGroup[$i] = $db->result($result2, $i, 'fk_voiture');
            }
            for ($i = 0; $i < $num_rows1; $i++) {
              $id_voiture = $db->result($result1, $i, 'id_voiture');
              $lib_voiture = $db->result($result1, $i, 'lib_voiture');
              $login = $db->result($result1, $i, 'login');
              echo '<input type="checkbox" id="c', $i, '" name="selectedCars[]"', in_array($id_voiture, $carsInGroup) ? ' disabled="disabled"' : '',
                ' value="', $id_voiture, '" /><label for="c', $i, '">[', $login, '] ', $lib_voiture, '</label><br />', "\n";
            }*/
      
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
            $query1 = 'SELECT id_groupe, lib_groupe FROM crz_groupe ORDER BY lib_groupe';
      
            $notEmptygroups = array();
            if ($resultGr = $db->query($query1)) {
              $query2 = 'SELECT DISTINCT fk_groupe FROM crz_groupe_voiture';
              if ($resultGrVoiture = $db->query($query2)) {
                $n3 = 0;
                while ($groupe = $resultGrVoiture->fetch_object('Groupe')) {
                  $notEmptyGroups[$n3] = $groupe->fk_groupe;
                  $n3++;
                }
              }
              
              $n5 = 0;
              while ($groupe2 = $resultGrVoiture->fetch_object('Groupe')) {
                $id_groupe = $groupe2->id_groupe;
                $lib_groupe = stripslashes($groupe2->lib_groupe);
                echo '<input type="checkbox" id="g', $n5, '" name="selectedGroups[]"',
                  in_array($id_groupe, $notEmptyGroups) ? ' disabled="disabled"' : '',
                  ' value="', $id_groupe, '" /><label for="g', $n5, '" id="lbl', $n5, '">', $lib_groupe, '</label>',
                  '<input type="radio" name="selectedGroup" value="', $id_groupe, '"',
                  ' onclick="document.frmGroup.textGroup.value = document.getElementById(\'lbl', $n5, '\').textContent;" /><br />', "\n";
                $n5++;
              }
            }
      
      
      /*$result1 = $db->query($query1);
            $num_rows1 = $db->numRows($result1);
            $query2 = 'SELECT DISTINCT fk_groupe FROM crz_groupe_voiture';
            $result2 = $db->query($query2);
            $num_rows2 = $db->numRows($result2);
            $notEmptygroups = array();
            for ($i = 0; $i < $num_rows2; $i++) {
              $notEmptyGroups[$i] = $db->result($result2, $i, 'fk_groupe');
            }
            for ($i = 0; $i < $num_rows1; $i++) {
              $id_groupe = $db->result($result1, $i, 'id_groupe');
              $lib_groupe = stripslashes($db->result($result1, $i, 'lib_groupe'));
              echo '<input type="checkbox" id="g', $i, '" name="selectedGroups[]"',
                in_array($id_groupe, $notEmptyGroups) ? ' disabled="disabled"' : '',
                ' value="', $id_groupe, '" /><label for="g', $i, '" id="lbl', $i, '">', $lib_groupe, '</label>',
                '<input type="radio" name="selectedGroup" value="', $id_groupe, '"',
                ' onclick="document.frmGroup.textGroup.value = document.getElementById(\'lbl', $i, '\').textContent;" /><br />', "\n";
            }*/
      
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