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
    <title>Carz - Profil > Créer une voiture</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="scripts/css/style.css" />
  </head>
  
  <body>
    <header>
  		<?php include 'header.inc.php'; ?>
  	</header>
    
    <nav>
      <?php include 'nav.inc.php'; ?>
      <h2>Profil > Créer une voiture</h2>
    </nav>
    
    <section>
      <form name="frmCar" action="profile_car_create.do.php" method="post">
        <!--------- Marque ---------------------------------------------------->
        <fieldset>
          <legend>Marque</legend>
          <select name="selectedBrand" onchange="document.frmCar.submit();" class="thick">
            <?php
            $selectedBrand = empty($_SESSION['selectedBrand']) ? '' : $_SESSION['selectedBrand'];
            $first = 0;
            $found = false;
            $query = 'SELECT id_marque, lib_marque FROM crz_marque ORDER BY lib_marque';
            $result = $db->query($query);
            $i = 0;
            while ($voiture = $result->fetch_object('Voiture')) {
              $id_marque = $voiture->id_marque;
              if ($i == 0) $first = $id_marque;
              if ($selectedBrand == $id_marque) $found = true;
              $lib_marque = $voiture->lib_marque;
              echo '<option value="', $id_marque, '"', $selectedBrand == $id_marque ? ' selected="selected"' : '', '>', $lib_marque, '</option>', "\n";
              $i++;
            }
            if (!$found) $_SESSION['selectedBrand'] = $first;
            ?>
          </select>
          <hr />
          <i>Nouvelle :</i><br />
          <input type="text" name="txtNewBrand" size="7" /><br />
          <select id="country" name="selectedCountry">
            <?php
            $query = 'SELECT id_pays, lib_pays FROM crz_pays ORDER BY lib_pays';
            $result = $db->query($query);
            while ($pays = $result->fetch_object('Pays')) {
              $id_pays = $pays->id_pays;
              $lib_pays = $pays->lib_pays;
              echo '<option value="', $id_pays, '">', $lib_pays, '</option>', "\n";
            }
            ?>
          </select>
          <input type="submit" name="addNewBrand" value="+" />
        </fieldset>
        <!--------------------------------------------------------------------->
        <div class="floating-box">
          <input type="submit" name="selectBrand" value=">" />
        </div>
        <!--------- Modèle ---------------------------------------------------->
        <fieldset>
          <legend>Modèle</legend>
          <select name="selectedModel" onchange="document.frmCar.submit();" class="thick">
            <?php
            $selectedModel = empty($_SESSION['selectedModel']) ? '' : $_SESSION['selectedModel'];
            $first = 0;
            $found = false;
            $query = $db->writeQuery('SELECT id_modele, lib_modele FROM crz_modele WHERE fk_marque = %d ORDER BY lib_modele', (int) $_SESSION['selectedBrand']);
            $result2 = $db->query($query);
            $i = 0;
            while ($voiture2 = $result2->fetch_object('Voiture')) {
              $id_modele = $voiture2->id_modele;
              if ($i == 0) $first = $id_modele;
              if ($selectedModel == $id_modele) $found = true;
              $lib_modele = $voiture2->lib_modele;
              echo '<option value="', $id_modele, '"', $selectedModel == $id_modele ? ' selected="selected"' : '', '>', $lib_modele, '</option>', "\n";
              $i++;
            }
            if (!$found) $_SESSION['selectedModel'] = $first;
            ?>
          </select>
          <?php
          if ($_SESSION['admin'] == 1) {
            echo '<input type="submit" name="removeModel" value="-" onclick="return confirm(\'Retirer ?\');" />';
          }
          ?>
          <hr />
          <i>Nouveau :</i><br />
          <input type="text" name="txtNewModel" size="7" />
          <input type="submit" name="addNewModel" value="+" />
        </fieldset>
        <!--------------------------------------------------------------------->
        <div class="floating-box">
          <input type="submit" name="selectModel" value=">" />
        </div>
        <!--------- Code ------------------------------------------------------>
        <fieldset>
          <legend>Code</legend>
          <select name="selectedCode" onchange="document.frmCar.submit();" class="thick">
            <?php
            $selectedCode = empty($_SESSION['selectedCode']) ? '' : $_SESSION['selectedCode'];
            $first = 0;
            $found = false;
            $query = 'SELECT c.id_code, c.lib_code FROM crz_code c';
            $query .= ' INNER JOIN crz_modele_code mc ON c.id_code = mc.fk_code';
            $query .= ' WHERE mc.fk_modele = %d';
            $query .= ' ORDER BY c.lib_code';
            $query = $db->writeQuery($query, (int) $_SESSION['selectedModel']);
            $result3 = $db->query($query);
            $i = 0;
            while ($voiture3 = $result3->fetch_object('Voiture')) {
              $id_code = $voiture3->id_code;
              if ($i == 0) $first = $id_code;
              if ($_SESSION['selectedCode'] == $id_code) $found = true;
              $lib_code = $voiture3->lib_code;
              echo '<option value="', $id_code, '"', $selectedCode == $id_code ? ' selected="selected"' : '', '>', $lib_code, '</option>', "\n";
              $i++;
            }
            if (!$found) $_SESSION['selectedCode'] = $first;
            ?>
          </select>
          <?php
          if ($_SESSION['admin'] == 1) {
            echo '<input type="submit" name="removeCode" value="-" onclick="return confirm(\'Retirer ?\');" />';
          }
          ?>
          <hr />
          <i>Autre :</i><br />
          <select name="selectedOtherCode">
            <?php
            $query = 'SELECT DISTINCT c.id_code, c.lib_code FROM crz_code c';
            $query .= ' INNER JOIN crz_modele_code mc ON c.id_code = mc.fk_code';
            $query .= ' INNER JOIN crz_modele m ON mc.fk_modele = m.id_modele';
            $query .= ' WHERE m.fk_marque = %d';
            $query .= ' AND c.id_code NOT IN (SELECT fk_code FROM crz_modele_code WHERE fk_modele = %d)';
            $query .= ' ORDER BY c.lib_code';
            $query = $db->writeQuery($query, (int) $_SESSION['selectedBrand'], (int) $_SESSION['selectedModel']);
            $result4 = $db->query($query);
            while ($voiture4 = $result4->fetch_object('Voiture')) {
              $id_code = $voiture4->id_code;
              $lib_code = $voiture4->lib_code;
              echo '<option value="', $id_code, '">', $lib_code, '</option>', "\n";
            }
            ?>
          </select>
          <input type="submit" name="addOtherCode" value="+" /><br /><br />
          <i>Ou nouveau :</i><br />
          <input type="text" name="txtNewCode" size="7" />
          <input type="submit" name="addNewCode" value="+" />
        </fieldset>
        <!--------------------------------------------------------------------->
        <div class="floating-box">
          <input type="submit" name="selectCode" value=">" />
        </div>
        <!--------- Puissance / couple ---------------------------------------->
        <fieldset>
          <legend>Puissance / couple</legend>
          <select name="selectedPower" onchange="document.frmCar.submit();" class="thick">
            <?php
            $first = 0;
            $found = false;
            $query = 'SELECT p.id_puissance, p.puissance, p.regime_puissance, p.couple, p.regime_couple FROM crz_puissance p';
            $query .= ' INNER JOIN crz_modele_code_puissance mcp ON p.id_puissance = mcp.fk_puissance';
            $query .= ' WHERE mcp.fk_modele = %d AND mcp.fk_code = %d';
            $query .= ' ORDER BY p.puissance';
            $query = $db->writeQuery($query, (int) $_SESSION['selectedModel'], (int) $_SESSION['selectedCode']);
            $result5 = $db->query($query);
            $i = 0;
            while ($voiture5 = $result5->fetch_object('Voiture')) {
              $id_puissance = $voiture5->id_puissance;
              if ($i == 0) $first = $id_puissance;
              if ($_SESSION['selectedPower'] == $id_puissance) $found = true;
              $puissance = $voiture5->puissance;
              $regime_puissance = $voiture5->regime_puissance;
              $couple = $voiture5->couple;
              $regime_couple = $voiture5->regime_couple;
              echo '<option value="', $id_puissance, '"', $_SESSION['selectedPower'] == $id_puissance ? ' selected="selected"' : '',
                ' title="', $puissance, ' à ', $regime_puissance, ' rpm / ', $couple, ' à ', $regime_couple, ' rpm">',
                $puissance, ' ch / ', $couple, ' N.m</option>', "\n";
              $i++;
            }
            if (!$found) $_SESSION['selectedPower'] = $first;
            ?>
          </select>
          <?php
          if ($_SESSION['admin'] == 1) {
            echo '<input type="submit" name="removePower" value="-" onclick="return confirm(\'Retirer ?\');" />';
          }
          ?>
          <hr />
          <i>Autre :</i><br />
          <select name="selectedOtherPower">
            <?php
            $query = 'SELECT id_puissance, puissance, regime_puissance, couple, regime_couple FROM crz_puissance';
            $query .= ' WHERE id_puissance NOT IN (SELECT fk_puissance FROM crz_modele_code_puissance WHERE fk_modele = %d AND fk_code = %d)';
            $query .= ' ORDER BY puissance';
            $query = $db->writeQuery($query, (int) $_SESSION['selectedModel'], (int) $_SESSION['selectedCode']);
            $result6 = $db->query($query);
            while ($voiture6 = $result6->fetch_object('Voiture')) {
              $id_puissance = $voiture6->id_puissance;
              $puissance = $voiture6->puissance;
              $regime_puissance = $voiture6->regime_puissance;
              $couple = $voiture6->couple;
              $regime_couple = $voiture6->regime_couple;
              echo '<option value="', $id_puissance, '"',
                ' title="', $puissance, ' à ', $regime_puissance, ' rpm / ', $couple, ' à ', $regime_couple, ' rpm">',
                $puissance, ' ch / ', $couple, ' N.m</option>', "\n";
            }
            ?>
          </select>
          <input type="submit" name="addOtherPower" value="+" /><br /><br />
          <i>Ou nouveau :</i><br />
          puissance
          <input type="text" name="txtPower" size="1" /> à <input type="text" name="txtPowerRpm" size="1" /> rpm<br />
          couple
          <input type="text" name="txtTorque" size="1" /> à <input type="text" name="txtTorqueRpm" size="1" /> rpm
          <input type="submit" name="addNewPower" value="+" />
        </fieldset>
        <!--------------------------------------------------------------------->
        <div class="floating-box">
          <input type="submit" name="selectPower" value=">" />
        </div>
        <!--------- Motorisation ---------------------------------------------->
        <fieldset>
          <legend>Motorisation</legend>
          <select name="selectedEngine" onchange="document.frmCar.submit();" class="thick">
            <?php
            $first = 0;
            $found = false;
            $query = 'SELECT m.id_motorisation, m.lib_motorisation, m.cylindree FROM crz_motorisation m';
            $query .= ' INNER JOIN crz_puissance p ON m.id_motorisation = p.fk_motorisation';
            $query .= ' WHERE p.id_puissance = %d';
            $query .= ' ORDER BY m.lib_motorisation';
            $query = $db->writeQuery($query, (int) $_SESSION['selectedPower']);
            $result7 = $db->query($query);
            $i = 0;
            while ($voiture7 = $result7->fetch_object('Voiture')) {
              $id_motorisation = $voiture7->id_motorisation;
              if ($i == 0) $first = $id_motorisation;
              if ($_SESSION['selectedEngine'] == $id_motorisation) $found = true;
              $lib_motorisation = $voiture7->lib_motorisation;
              $cylindree = $voiture7->cylindree;
              echo '<option value="', $id_motorisation, '"', $_SESSION['selectedEngine'] == $id_motorisation ? ' selected="selected"' : '', '>',
                $lib_motorisation, ' (', $cylindree, ' cm3)</option>', "\n";
              $i++;
            }
            if (!$found) $_SESSION['selectedEngine'] = $first;
            ?>
          </select>
          <?php
          if ($_SESSION['admin'] == 1) {
            echo '<input type="submit" name="removeEngine" value="-" onclick="return confirm(\'Retirer ?\');" />';
          }
          ?>
          <hr />
          <i>Autre :</i><br />
          <select name="selectedOtherEngine">
            <?php
            $query = 'SELECT id_motorisation, lib_motorisation, cylindree FROM crz_motorisation';
            $query .= ' WHERE id_motorisation NOT IN (SELECT fk_motorisation FROM crz_puissance WHERE id_puissance = %d)';
            $query .= ' ORDER BY lib_motorisation';
            $query = $db->writeQuery($query, (int) $_SESSION['selectedPower']);
            $result8 = $db->query($query);
            while ($voiture8 = $result8->fetch_object('Voiture')) {
              $id_motorisation = $voiture8->id_motorisation;
              $lib_motorisation = $voiture8->lib_motorisation;
              $cylindree = $voiture8->cylindree;
              echo '<option value="', $id_motorisation, '">', $lib_motorisation, ' (', $cylindree, ' cm3)</option>', "\n";
            }
            ?>
          </select>
          <input type="submit" name="addOtherEngine" value="+" /><br /><br />
          <i>Ou nouvelle :</i><br />
          libellé
          <input type="text" name="txtNewEngine" size="15" /><br />
          énergie
          <select name="selectedEnergy">
            <option value="essence">Essence</option>
            <option value="diesel">Diesel</option>
            <option value="hybride">Hybride</option>
            <option value="electrique">Electrique</option>
          </select><br />
          cylindrée
          <input type="text" name="txtDisplacement" size="1" /> cm<sup>3</sup><br />
          cylindres
          <select name="selectedCylinders">
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="8">8</option>
            <option value="10">10</option>
            <option value="12">12</option>
          </select><br />
          soupapes/cylindre
          <select name="selectedValves">
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
          </select><br />
          suralimentation
          <select name="selectedSupercharging">
            <option value="atmo">Atmo</option>
            <option value="turbo">Turbo</option>
            <option value="compresseur">Compresseur</option>
          </select><br />
          injection
          <select name="selectedInjection">
            <option value="direct">Direct</option>
            <option value="indirect">Indirect</option>
            <option value="carbu">Carbu</option>
          </select>
          <input type="submit" name="addNewEngine" value="+" />
        </fieldset>
        <!--------------------------------------------------------------------->
        <div class="floating-box">
          <input type="submit" name="selectEngine" value=">" />
        </div>
        <!--------- Boîte ----------------------------------------------------->
        <fieldset>
          <legend>Boîte</legend>
          <select name="selectedGearbox" onchange="document.frmCar.submit();" class="thick">
            <?php
            $first = 0;
            $found = false;
            $query = 'SELECT b.id_boite, b.lib_boite FROM crz_boite b';
            $query .= ' INNER JOIN crz_modele_code_motorisation_boite mcmb ON b.id_boite = mcmb.fk_boite';
            $query .= ' WHERE mcmb.fk_modele = %d AND mcmb.fk_code = %d AND mcmb.fk_motorisation = %d';
            $query .= ' ORDER BY b.lib_boite';
            $query = $db->writeQuery($query, (int) $_SESSION['selectedModel'], (int) $_SESSION['selectedCode'], (int) $_SESSION['selectedEngine']);
            $result9 = $db->query($query);
            $i = 0;
            while ($voiture9 = $result9->fetch_object('Voiture')) {
              $id_boite = $voiture9->id_boite;
              if ($i == 0) $first = $id_boite;
              if ($_SESSION['selectedGearbox'] == $id_boite) $found = true;
              $lib_boite = $voiture9->lib_boite;
              echo '<option value="', $id_boite, '"', $_SESSION['selectedGearbox'] == $id_boite ? ' selected="selected"' : '','>',
                $lib_boite, '</option>', "\n";
              $i++;
            }
            if (!$found) $_SESSION['selectedGearbox'] = $first;
            ?>
          </select>
          <?php
          if ($_SESSION['admin'] == 1) {
            echo '<input type="submit" name="removeGearbox" value="-" onclick="return confirm(\'Retirer ?\');" />';
          }
          ?>
          <hr />
          <i>Autre :</i><br />
          <select name="selectedOtherGearbox">
            <?php
            $query = 'SELECT id_boite, lib_boite FROM crz_boite';
            $query .= ' WHERE id_boite NOT IN (SELECT fk_boite FROM crz_modele_code_motorisation_boite WHERE fk_modele = %d AND fk_code = %d AND fk_motorisation = %d)';
            $query .= ' ORDER BY lib_boite';
            $query = $db->writeQuery($query, (int) $_SESSION['selectedModel'], (int) $_SESSION['selectedCode'], (int) $_SESSION['selectedEngine']);
            $result10 = $db->query($query);
            while ($voiture10 = $result10->fetch_object('Voiture')) {
              $id_boite = $voiture10->id_boite;
              $lib_boite = $voiture10->lib_boite;
              echo '<option value="', $id_boite, '">', $lib_boite, '</option>', "\n";
            }
            ?>
          </select>
          <input type="submit" name="addOtherGearbox" value="+" /><br /><br />
          <i>Ou nouvelle boîte auto :</i><br />
          <input type="text" name="txtNewGearbox" size="10" />
          <input type="submit" name="addNewGearbox" value="+" />
        </fieldset>
        <!--------------------------------------------------------------------->
        <br />
        Année :
        <select name="selectedYear" class="thick">
          <?php
          $year = date('Y');
          for ($i = $year - 50; $i <= $year + 1; $i++) {
            echo '<option value="', $i, '"', $i == $year ? ' selected="selected"' : '', '>', $i, '</option>';
          }
          ?>
        </select>
        
        <br /><br />
        Libellé :
        <input type="text" name="txtNewCar" size="40" class="thick" />
        <input type="submit" name="createCar" value="Créer la voiture" />
        <div class="info">
          Exemple : Audi S5 Coupe V8 4.2 FSI 354 quattro
        </div>
      </form>
    </section>
    
    <footer>
      <?php include 'footer.inc.php'; ?>
    </footer>
  </body>
</html>