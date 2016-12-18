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
    <title>Carz - Mon compte > Ma voiture</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="scripts/css/style.css" />
  </head>
  
  <body>
    <header>
      <?php include 'header.inc.php'; ?>
    </header>
  
    <nav>
      Mon compte > Ma voiture
      <?php include 'nav.inc.php'; ?>
    </nav>
    
    <section>
      <?php
		include 'config/carz.conf.php';
		include PATH_SCRIPTS.'/php/Database.class.php';
		require_once(PATH_SCRIPTS.'/php/Voiture.class.php');  
		$db = new Database();
		$db->connect();
      
		if (!empty($_GET['car_id'])) {
			$_SESSION['id_voiture'] = $_GET['car_id'];
		}
      
		$query = 'SELECT v.id_voiture, v.lib_voiture, v.annee, ma.id_marque, ma.lib_marque, m.id_modele, c.id_code, mot.id_motorisation, p.id_puissance, b.id_boite';
		$query .= ' FROM crz_voiture v';
		$query .= ' INNER JOIN crz_modele m ON v.fk_modele = m.id_modele';
		$query .= ' INNER JOIN crz_marque ma ON m.fk_marque = ma.id_marque';
		$query .= ' INNER JOIN crz_code c ON v.fk_code = c.id_code';
		$query .= ' INNER JOIN crz_boite b ON v.fk_boite = b.id_boite';
		$query .= ' LEFT OUTER JOIN crz_puissance p ON v.fk_puissance = p.id_puissance';
		$query .= ' LEFT OUTER JOIN crz_motorisation mot ON p.fk_motorisation = mot.id_motorisation';
		$query .= ' WHERE v.id_voiture = %d AND (v.fk_utilisateur = %d OR %d = 1)';
		$query = $db->writeQuery($query, (int) $_SESSION['id_voiture'], (int) $_SESSION['id_utilisateur'], (int) $_SESSION['admin']);
      
		if($result = $db->query($query)){
		//$data = $db->fetchAssoc($db->query($query));
			if($voiture= $result->fetch_object('Voiture')){
				if (!empty($_GET['car_id'])) {
					$_SESSION['selectedBrand'] = $voiture->id_marque;
					$_SESSION['selectedModel'] = $voiture->id_modele;
					$_SESSION['selectedCode'] = $voiture->id_code;
					$_SESSION['selectedPower'] = $voiture->id_puissance;
					$_SESSION['selectedEngine'] = $voiture->id_motorisation;
					$_SESSION['selectedGearbox'] = $voiture->id_boite;
				}
      ?>
      <br />
      <form name="frmUpdateCar" action="profile_car_update.php" method="post">
        <table class="listing border">
          <tr><th>Marque</th><th>Modèle</th><th>Année</th><th>Code</th><th>Puissance / Couple</th><th>Motorisation</th><th>Boîte</th><th>Libellé</th></tr>
          <tr>
            <td>
              <input type="hidden" name="selectedBrand" value="<?php echo $voiture->id_marque; ?>" />
              <?php echo $voiture->lib_marque; ?>
            </td>
            <td>
              <select name="selectedModel" onchange="document.frmUpdateCar.submit();">
                <?php
                $first = 0;
                $found = false;
                $query = 'SELECT id_modele, lib_modele FROM crz_modele WHERE fk_marque = %d';
                $query = $db->writeQuery($query, (int) $_SESSION['selectedBrand']);
                
				if($result2 = $db->query($query)){
					while($voiture2= $result2->fetch_object('Voiture')){
						$id_modele = $voiture2->id_modele;
						$lib_modele = $voiture2->lib_modele;
						if ($_SESSION['selectedModel'] == $id_modele) $found = true;
						echo '<option value="', $id_modele, '"', $_SESSION['selectedModel'] == $id_modele ? ' selected="selected"' : '', '>',
							$lib_modele, '</option>', "\n";
					}
				}
				
				/*$result = $db->query($query);
                $num_rows = $db->numRows($result);
                for ($i = 0; $i < $num_rows; $i++) {
                  $id_modele = $db->result($result, $i, 'id_modele');
                  if ($i == 0) $first = $id_modele;
                  if ($_SESSION['selectedModel'] == $id_modele) $found = true;
                  $lib_modele = $db->result($result, $i, 'lib_modele');
                  echo '<option value="', $id_modele, '"', $_SESSION['selectedModel'] == $id_modele ? ' selected="selected"' : '', '>',
                    $lib_modele, '</option>', "\n";
                }*/
				
                if (!$found) $_SESSION['selectedBrand'] = $first;
                ?>
              </select>
            </td>
            <td>
              <select name="selectedYear">
                <?php
                $year = empty($voiture->annee) ? date('Y') : $voiture->annee;
                for ($i = $year - 50; $i <= date('Y') + 1; $i++) {
                  echo '<option value="', $i, '"', $i == $year ? ' selected="selected"' : '', '>', $i, '</option>';
                }
                ?>
              </select>
            </td>
            <td>
              <select name="selectedCode" onchange="document.frmUpdateCar.submit();">
                <?php
                $first = 0;
                $found = false;
                $query = 'SELECT c.id_code, c.lib_code FROM crz_code c';
                $query .= ' INNER JOIN crz_modele_code mc ON c.id_code = mc.fk_code';
                $query .= ' WHERE mc.fk_modele = %d';
                $query .= ' ORDER BY c.lib_code';
                $query = $db->writeQuery($query, (int) $_SESSION['selectedModel']);
               
				if($result3 = $db->query($query)){
					while($voiture3= $result3->fetch_object('Voiture')){
						$id_code = $voiture3->id_code;
						if ($_SESSION['selectedCode'] == $id_code) $found = true;
						$lib_code = $voiture3->lib_code;
						echo '<option value="', $id_code, '"', $_SESSION['selectedCode'] == $id_code ? ' selected="selected"' : '', '>',
							$lib_code, '</option>', "\n";
					}
				}
			   
				/*$result = $db->query($query);
                $num_rows = $db->numRows($result);
                for ($i = 0; $i < $num_rows; $i++) {
                  $id_code = $db->result($result, $i, 'id_code');
                  if ($i == 0) $first = $id_code;
                  if ($_SESSION['selectedCode'] == $id_code) $found = true;
                  $lib_code = $db->result($result, $i, 'lib_code');
                  echo '<option value="', $id_code, '"', $_SESSION['selectedCode'] == $id_code ? ' selected="selected"' : '', '>',
                    $lib_code, '</option>', "\n";
                }*/
				
                if (!$found) $_SESSION['selectedCode'] = $first;
                ?>
              </select>
            </td>
            <td>
              <select name="selectedPower" onchange="document.frmUpdateCar.submit();">
                <?php
                $first = 0;
                $found = false;
                $query = 'SELECT p.id_puissance, p.puissance, p.regime_puissance, p.couple, p.regime_couple FROM crz_puissance p';
                $query .= ' INNER JOIN crz_modele_code_puissance mcp ON p.id_puissance = mcp.fk_puissance';
                $query .= ' WHERE mcp.fk_modele = %d AND mcp.fk_code = %d';
                $query .= ' ORDER BY p.puissance';
                $query = $db->writeQuery($query, (int) $_SESSION['selectedModel'], (int) $_SESSION['selectedCode']);
                
				if($result4 = $db->query($query)){
					while($voiture4= $result4->fetch_object('Voiture')){
						$id_puissance = $voiture4->id_puissance;
						if ($_SESSION['selectedPower'] == $id_puissance) $found = true;
						$puissance = $voiture4->puissance;
						$regime_puissance = $voiture4->regime_puissance;
						$couple = $voiture4->couple;
						$regime_couple = $voiture4->regime_couple;
						echo '<option value="', $id_puissance, '"', $_SESSION['selectedPower'] == $id_puissance ? ' selected="selected"' : '',
							' title="', $puissance, ' à ', $regime_puissance, ' rpm / ', $couple, ' à ', $regime_couple, ' rpm">',
							$puissance, ' ch / ', $couple, ' N.m</option>', "\n";
					}
				}
				
				/*$result = $db->query($query);
                $num_rows = $db->numRows($result);
                for ($i = 0; $i < $num_rows; $i++) {
                  $id_puissance = $db->result($result, $i, 'id_puissance');
                  if ($i == 0) $first = $id_puissance;
                  if ($_SESSION['selectedPower'] == $id_puissance) $found = true;
                  $puissance = $db->result($result, $i, 'puissance');
                  $regime_puissance = $db->result($result, $i, 'regime_puissance');
                  $couple = $db->result($result, $i, 'couple');
                  $regime_couple = $db->result($result, $i, 'regime_couple');
                  echo '<option value="', $id_puissance, '"', $_SESSION['selectedPower'] == $id_puissance ? ' selected="selected"' : '',
                    ' title="', $puissance, ' à ', $regime_puissance, ' rpm / ', $couple, ' à ', $regime_couple, ' rpm">',
                    $puissance, ' ch / ', $couple, ' N.m</option>', "\n";
                }*/
				
                if (!$found) $_SESSION['selectedPower'] = $first;
                ?>
              </select>
            </td>
            <td>
              <select name="selectedEngine" onchange="document.frmUpdateCar.submit();">
                <?php
                $first = 0;
                $found = false;
                $query = 'SELECT m.id_motorisation, m.lib_motorisation FROM crz_motorisation m';
                $query .= ' INNER JOIN crz_puissance p ON m.id_motorisation = p.fk_motorisation';
                $query .= ' WHERE p.id_puissance = %d';
                $query .= ' ORDER BY m.lib_motorisation';
                $query = $db->writeQuery($query, (int) $_SESSION['selectedPower']);
                
				if($result5 = $db->query($query)){
					while($voiture5= $result5->fetch_object('Voiture')){
						$id_motorisation = $voiture5->id_motorisation;
						if ($_SESSION['selectedEngine'] == $id_motorisation) $found = true;
						$lib_motorisation = $voiture5->lib_motorisation;
						echo '<option value="', $id_motorisation, '"', $_SESSION['selectedEngine'] == $id_motorisation ? ' selected="selected"' : '', '>',
								$lib_motorisation, '</option>', "\n";
					}
				}
				/*$result = $db->query($query);
                $num_rows = $db->numRows($result);
                for ($i = 0; $i < $num_rows; $i++) {
                  $id_motorisation = $db->result($result, $i, 'id_motorisation');
                  if ($i == 0) $first = $id_motorisation;
                  if ($_SESSION['selectedEngine'] == $id_motorisation) $found = true;
                  $lib_motorisation = $db->result($result, $i, 'lib_motorisation');
                  echo '<option value="', $id_motorisation, '"', $_SESSION['selectedEngine'] == $id_motorisation ? ' selected="selected"' : '', '>',
                    $lib_motorisation, '</option>', "\n";
                }*/
				
                if (!$found) $_SESSION['selectedEngine'] = $first;
                ?>
              </select>
            </td>
            <td>
              <select name="selectedGearbox" onchange="document.frmUpdateCar.submit();">
                <?php
                $first = 0;
                $found = false;
                $query = 'SELECT b.id_boite, b.lib_boite FROM crz_boite b';
                $query .= ' INNER JOIN crz_modele_code_motorisation_boite mcmb ON b.id_boite = mcmb.fk_boite';
                $query .= ' WHERE mcmb.fk_modele = %d AND mcmb.fk_code = %d AND mcmb.fk_motorisation = %d';
                $query .= ' ORDER BY b.lib_boite';
                $query = $db->writeQuery($query, (int) $_SESSION['selectedModel'], (int) $_SESSION['selectedCode'], (int) $_SESSION['selectedEngine']);
                
				if($result6 = $db->query($query)){
					while($voiture6= $result6->fetch_object('Voiture')){
						$id_boite = $voiture6->id_boite;
						if ($_SESSION['selectedGearbox'] == $id_boite) $found = true;
						$lib_boite = $voiture6->lib_boite;
						echo '<option value="', $id_boite, '"', $_SESSION['selectedGearbox'] == $id_boite ? ' selected="selected"' : '','>',
							$lib_boite, '</option>', "\n";
					}
				}
				
				/*$result = $db->query($query);
                $num_rows = $db->numRows($result);
                for ($i = 0; $i < $num_rows; $i++) {
                  $id_boite = $db->result($result, $i, 'id_boite');
                  if ($i == 0) $first = $id_boite;
                  if ($_SESSION['selectedGearbox'] == $id_boite) $found = true;
                  $lib_boite = $db->result($result, $i, 'lib_boite');
                  echo '<option value="', $id_boite, '"', $_SESSION['selectedGearbox'] == $id_boite ? ' selected="selected"' : '','>',
                    $lib_boite, '</option>', "\n";
                }*/
				
                if (!$found) $_SESSION['selectedGearbox'] = $first;
                ?>
              </select>
            </td>
            <td><input type="text" name="txtCar" value="<?php echo $voiture->lib_voiture; ?>" size="40" /></td>
            <td><input type="submit" name="updateCar" value="Enregistrer" /></td>
          </tr>
        </table>
      </form>
      
		<?php
			}
		}
	  
      $db->close();
      ?>
    </section>
    
    <footer>
      <?php include 'footer.inc.php'; ?>
    </footer>
  </body>
</html>