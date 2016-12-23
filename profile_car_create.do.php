<?php
session_start();
if (empty($_SESSION['id_utilisateur'])) {
  $_SESSION['msg'] = '<span class="error">La session a expiré !</span>';
  header('Location: index.php');
  exit();
}

include 'config/carz.conf.php';
include PATH_SCRIPTS.'/php/Database.class.php';

$db = new Database();
$db->connect();

$_SESSION['selectedBrand'] = empty($_POST['selectedBrand']) ? '' : $_POST['selectedBrand'];
$_SESSION['selectedModel'] = empty($_POST['selectedModel']) ? '' : $_POST['selectedModel'];
$_SESSION['selectedCode'] = empty($_POST['selectedCode']) ? '' : $_POST['selectedCode'];
$_SESSION['selectedPower'] = empty($_POST['selectedPower']) ? '' : $_POST['selectedPower'];
$_SESSION['selectedEngine'] = empty($_POST['selectedEngine']) ? '' : $_POST['selectedEngine'];
$_SESSION['selectedGearbox'] = empty($_POST['selectedGearbox']) ? '' : $_POST['selectedGearbox'];

// Ajouter une nouvelle marque
if (!empty($_POST['addNewBrand']) && $_POST['addNewBrand'] == '+' && trim($_POST['txtNewBrand']) != '') {
  $query = $db->writeQuery('INSERT INTO crz_marque (lib_marque, fk_pays) VALUES (%s, %d)', trim($_POST['txtNewBrand']), (int) $_POST['selectedCountry']);
  $db->query($query);
  $_SESSION['selectedBrand'] = $db->getInsertId();
}

// Ajouter un nouveau modèle
if (!empty($_POST['addNewModel']) && $_POST['addNewModel'] == '+' && trim($_POST['txtNewModel']) != '') {
  $query = $db->writeQuery('INSERT INTO crz_modele (lib_modele, fk_marque) VALUES (%s, %d)', $_POST['txtNewModel'], (int) $_POST['selectedBrand']);
  $db->query($query);
  $_SESSION['selectedModel'] = $db->getInsertId();
}

// Retirer un modèle
if (!empty($_POST['removeModel']) && $_POST['removeModel'] == '-' && !empty($_POST['selectedModel'])) {
  $query = 'DELETE FROM crz_modele_code WHERE fk_modele = %d';
  $query = $db->writeQuery($query, (int) $_POST['selectedModel']);
  $db->query($query);
  
  $query = 'DELETE FROM crz_modele_code_motorisation_boite WHERE fk_modele = %d';
  $query = $db->writeQuery($query, (int) $_POST['selectedModel']);
  $db->query($query);
  
  $query = 'DELETE FROM crz_modele_code_puissance WHERE fk_modele = %d';
  $query = $db->writeQuery($query, (int) $_POST['selectedModel']);
  $db->query($query);
  
  $query = 'DELETE FROM crz_modele_finition WHERE fk_modele = %d';
  $query = $db->writeQuery($query, (int) $_POST['selectedModel']);
  $db->query($query);
}

// Ajouter un nouveau code
if (!empty($_POST['addNewCode']) && $_POST['addNewCode'] == '+' && trim($_POST['txtNewCode']) != '' && !empty($_POST['selectedModel'])) {
  $query = $db->writeQuery('INSERT INTO crz_code (lib_code) VALUES (%s)', trim($_POST['txtNewCode']));
  $db->query($query);
  $id_code = $db->getInsertId();
  $_SESSION['selectedCode'] = $id_code;
  
  $query = $db->writeQuery('INSERT INTO crz_modele_code (fk_modele, fk_code) VALUES (%d, %d)', (int) $_POST['selectedModel'], (int) $id_code);
  $db->query($query);
}

// Ajouter un autre code
if (!empty($_POST['addOtherCode']) && $_POST['addOtherCode'] == '+' && !empty($_POST['selectedOtherCode']) && !empty($_POST['selectedModel'])) {
  $query = $db->writeQuery('INSERT INTO crz_modele_code (fk_modele, fk_code) VALUES (%d, %d)', (int) $_POST['selectedModel'], (int) $_POST['selectedOtherCode']);
  $db->query($query);
  $_SESSION['selectedCode'] = $_POST['selectedOtherCode'];
}

// Retirer un code
if (!empty($_POST['removeCode']) && $_POST['removeCode'] == '-' && !empty($_POST['selectedCode'])) {
  $query = 'DELETE FROM crz_modele_code WHERE fk_modele = %d AND fk_code = %d';
  $query = $db->writeQuery($query, (int) $_POST['selectedModel'], (int) $_POST['selectedCode']);
  $db->query($query);
  
  $query = 'DELETE FROM crz_modele_code_motorisation_boite';
  $query .= ' WHERE fk_modele = %d AND fk_code = %d AND fk_motorisation = %d AND fk_boite = %d';
  $query = $db->writeQuery($query, (int) $_POST['selectedModel'], (int) $_POST['selectedCode'], (int) $_POST['selectedEngine'], (int) $_POST['selectedGearbox']);
  $db->query($query);
  
  $query = 'DELETE FROM crz_modele_code_puissance WHERE fk_modele = %d AND fk_code = %d AND fk_puissance = %d';
  $query = $db->writeQuery($query, (int) $_POST['selectedModel'], (int) $_POST['selectedCode'], (int) $_POST['selectedPower']);
  $db->query($query);
}

// Ajouter une nouvelle puissance
if (!empty($_POST['addNewPower']) && $_POST['addNewPower'] == '+' && is_numeric($_POST['txtPower']) && is_numeric($_POST['txtPowerRpm']) && is_numeric($_POST['txtTorque']) && is_numeric($_POST['txtTorqueRpm'])) {
  $query = 'INSERT INTO crz_puissance (puissance, regime_puissance, couple, regime_couple) VALUES (%d, %d, %d, %d)';
  $query = $db->writeQuery($query, (int) $_POST['txtPower'], (int) $_POST['txtPowerRpm'], (int) $_POST['txtTorque'], (int) $_POST['txtTorqueRpm']);
  $db->query($query);
  $id_puissance = $db->getInsertId();
  $_SESSION['selectedPower'] = $id_puissance;
  
  $query = 'INSERT INTO crz_modele_code_puissance (fk_modele, fk_code, fk_puissance) VALUES (%d, %d, %d)';
  $query = $db->writeQuery($query, (int) $_POST['selectedModel'], (int) $_POST['selectedCode'], (int) $id_puissance);
  $db->query($query);
}

// Ajouter une autre puissance
if (!empty($_POST['addOtherPower']) && $_POST['addOtherPower'] == '+' && !empty($_POST['selectedOtherPower'])) {
  $query = 'INSERT INTO crz_modele_code_puissance (fk_modele, fk_code, fk_puissance) VALUES (%d, %d, %d)';
  $query = $db->writeQuery($query, (int) $_POST['selectedModel'], (int) $_POST['selectedCode'], (int) $_POST['selectedOtherPower']);
  $db->query($query);
  $_SESSION['selectedPower'] = $_POST['selectedOtherPower'];
}

// Retirer une puissance
if (!empty($_POST['removePower']) && $_POST['removePower'] == '-' && !empty($_POST['selectedPower'])) {
  $query = 'DELETE FROM crz_modele_code_puissance WHERE fk_modele = %d AND fk_code = %d AND fk_puissance = %d';
  $query = $db->writeQuery($query, (int) $_POST['selectedModel'], (int) $_POST['selectedCode'], (int) $_POST['selectedPower']);
  $db->query($query);
}

// Ajouter une nouvelle motorisation
if (!empty($_POST['addNewEngine']) && $_POST['addNewEngine'] == '+' && trim($_POST['txtNewEngine']) != '' && is_numeric($_POST['txtDisplacement']) && !empty($_POST['selectedPower'])) {
  $nb_soupapes = $_POST['selectedCylinders'] * $_POST['selectedValves'];
  $query = 'INSERT INTO crz_motorisation (lib_motorisation, energie, cylindree, nb_cylindres, nb_soupapes, suralimentation, injection) VALUES (%s, %s, %d, %d, %d, %s, %s)';
  $query = $db->writeQuery($query, trim($_POST['txtNewEngine']), $_POST['selectedEnergy'], (int) $_POST['txtDisplacement'], (int) $_POST['selectedCylinders'], $nb_soupapes, $_POST['selectedSupercharging'], $_POST['selectedInjection']);
  $db->query($query);
  $id_motorisation = $db->getInsertId();
  $_SESSION['selectedEngine'] = $id_motorisation;
  
  $query = 'UPDATE crz_puissance SET fk_motorisation = %d WHERE id_puissance = %d';
  $query = $db->writeQuery($query, (int) $id_motorisation, (int) $_POST['selectedPower']);
  $db->query($query);
}

// Ajouter une autre motorisation
if (!empty($_POST['addOtherEngine']) && $_POST['addOtherEngine'] == '+' && !empty($_POST['selectedOtherEngine']) && !empty($_POST['selectedPower'])) {
  $query = 'UPDATE crz_puissance SET fk_motorisation = %d WHERE id_puissance = %d';
  $query = $db->writeQuery($query, (int) $_POST['selectedOtherEngine'], (int) $_POST['selectedPower']);
  $db->query($query);
  $_SESSION['selectedEngine'] = $_POST['selectedOtherEngine'];
}

// Retirer une motorisation
if (!empty($_POST['removeEngine']) && $_POST['removeEngine'] == '-' && !empty($_POST['selectedEngine'])) {
  $query = 'DELETE FROM crz_modele_code_motorisation_boite';
  $query .= ' WHERE fk_modele = %d AND fk_code = %d AND fk_motorisation = %d';
  $query = $db->writeQuery($query, (int) $_POST['selectedModel'], (int) $_POST['selectedCode'], (int) $_POST['selectedEngine']);
  $db->query($query);
}

// Ajouter une nouvelle boîte
if ($_POST['addNewGearbox'] == '+' && trim($_POST['txtNewGearbox']) != '') {
  $query = $db->writeQuery('INSERT INTO crz_boite (lib_boite, auto) VALUES (%s, 1)', trim($_POST['txtNewGearbox']));
  $db->query($query);
  $id_boite = $db->getInsertId();
  $_SESSION['selectedGearbox'] = $id_boite;
  
  $query = 'INSERT INTO crz_modele_code_motorisation_boite (fk_modele, fk_code, fk_motorisation, fk_boite) VALUES (%d, %d, %d, %d)';
  $query = $db->writeQuery($query, (int) $_POST['selectedModel'], (int) $_POST['selectedCode'], (int) $_POST['selectedEngine'], (int) $id_boite);
  $db->query($query);
}

// Ajouter une autre boîte
if ($_POST['addOtherGearbox'] == '+' && !empty($_POST['selectedOtherGearbox'])) {
  $query = 'INSERT INTO crz_modele_code_motorisation_boite (fk_modele, fk_code, fk_motorisation, fk_boite) VALUES (%d, %d, %d, %d)';
  $query = $db->writeQuery($query, (int) $_POST['selectedModel'], (int) $_POST['selectedCode'], (int) $_POST['selectedEngine'], (int) $_POST['selectedOtherGearbox']);
  $db->query($query);
  $_SESSION['selectedGearbox'] = $_POST['selectedOtherGearbox'];
}

// Retirer une boîte
if ($_POST['removeGearbox'] == '-' && !empty($_POST['selectedGearbox'])) {
  $query = 'DELETE FROM crz_modele_code_motorisation_boite';
  $query .= ' WHERE fk_modele = %d AND fk_code = %d AND fk_motorisation = %d AND fk_boite = %d';
  $query = $db->writeQuery($query, (int) $_POST['selectedModel'], (int) $_POST['selectedCode'], (int) $_POST['selectedEngine'], (int) $_POST['selectedGearbox']);
  $db->query($query);  
}

// Créer la nouvelle voiture
if ($_POST['createCar'] == 'Créer la voiture' && !empty($_POST['selectedBrand']) && !empty($_POST['selectedModel']) && !empty($_POST['selectedCode']) && !empty($_POST['selectedPower']) && !empty($_POST['selectedEngine']) && !empty($_POST['selectedGearbox']) && trim($_POST['txtNewCar']) != '') {
  $query = 'INSERT INTO crz_voiture (lib_voiture, fk_utilisateur, fk_modele, fk_code, fk_boite, fk_puissance, annee) VALUES (%s, %d, %d, %d, %d, %d, %d)';
  $query = $db->writeQuery($query, trim($_POST['txtNewCar']), (int) $_SESSION['id_utilisateur'], (int) $_POST['selectedModel'], (int) $_POST['selectedCode'], (int) $_POST['selectedGearbox'], (int) $_POST['selectedPower'], (int) $_POST['selectedYear']);
  $db->query($query);
  
  $db->close();
  header('Location: profile.php');
  exit();
}


$db->close();
header('Location: profile_car.php');
exit();
?>