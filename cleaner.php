<?php
session_start();
if ($_SESSION['admin'] != '1') {
  $_SESSION['msg'] = '<span class="error">Accès interdit !</span>';
  header('Location: index.php');
  exit();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Nettoyage</title>
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
    </nav>
    
    <section>
      <?php
      include 'config/carz.conf.php';
      include PATH_SCRIPTS.'/php/Database.class.php';
      require_once(PATH_SCRIPTS.'/php/Voiture.class.php');
      
      $db = new Database();
      $db->connect();
      ?>
      
      <fieldset>
        <legend>Modele / Code</legend>
        <table class="listing border">
          <tr><th>Modèle</th><th>Code</th></tr>
          <?php
          $query1 = 'SELECT mc.fk_modele, m.lib_modele, mc.fk_code, c.lib_code FROM crz_modele_code mc';
          $query1 .= ' INNER JOIN crz_modele m ON mc.fk_modele = m.id_modele';
          $query1 .= ' INNER JOIN crz_code c ON mc.fk_code = c.id_code';
          if ($result1 = $db->query($query1)) {
            while ($v1 = $result1->fetch_object('Voiture')) {
              echo '<tr><td>', $v1->lib_modele, '</td><td>', $v1->lib_code, '</td>',
              '<td><a href="cleaner.do.php?mc=', $v1->fk_modele, ',', $v1->fk_code, '"><img src="graphics/delete.png" /></a></td></tr>';
            }
          }
          ?>
        </table>
      </fieldset>
      
      <fieldset>
        <legend>Modele / Code / Motorisation / Boîte</legend>
        <table class="listing border">
          <tr><th>Modèle</th><th>Code</th><th>Motorisation</th><th>Boîte</th></tr>
          <?php
          $query2 = 'SELECT mcmb.fk_modele, m.lib_modele, mcmb.fk_code, c.lib_code, mcmb.fk_motorisation, mt.lib_motorisation, mcmb.fk_boite, b.lib_boite FROM crz_modele_code_motorisation_boite mcmb';
          $query2 .= ' INNER JOIN crz_modele m ON mcmb.fk_modele = m.id_modele';
          $query2 .= ' INNER JOIN crz_code c ON mcmb.fk_code = c.id_code';
          $query2 .= ' INNER JOIN crz_motorisation mt ON mcmb.fk_motorisation = mt.id_motorisation';
          $query2 .= ' INNER JOIN crz_boite b ON mcmb.fk_boite = b.id_boite';
          if ($result2 = $db->query($query2)) {
            while ($v2 = $result2->fetch_object('Voiture')) {
              echo '<tr><td>', $v2->lib_modele, '</td><td>', $v2->lib_code, '</td><td>', $v2->lib_motorisation,
                '</td><td>', $v2->lib_boite, '</td><td><a href="cleaner.do.php?mcmb=', $v2->fk_modele, ',', $v2->fk_code,
                ',', $v2->fk_motorisation, ',', $v2->fk_boite, '"><img src="graphics/delete.png" /></a></td></tr>';
            }
          }
          ?>
        </table>
      </fieldset>
      
      <fieldset>
        <legend>Modele / Code / Puissance</legend>
        <table class="listing border">
          <tr><th>Modèle</th><th>Code</th><th>Puiss. / Couple</th></tr>
          <?php
          $query3 = 'SELECT mcp.fk_modele, m.lib_modele, mcp.fk_code, c.lib_code, mcp.fk_puissance, p.puissance, p.couple FROM crz_modele_code_puissance mcp';
          $query3 .= ' INNER JOIN crz_modele m ON mcp.fk_modele = m.id_modele';
          $query3 .= ' INNER JOIN crz_code c ON mcp.fk_code = c.id_code';
          $query3 .= ' INNER JOIN crz_puissance p ON mcp.fk_puissance = p.id_puissance';
          if ($result3 = $db->query($query3)) {
            while ($v3 = $result3->fetch_object('Voiture')) {
              echo '<tr><td>', $v3->lib_modele, '</td><td>', $v3->lib_code, '</td><td>', $v3->puissance, ' / ', $v3->couple, '</td>',
              '<td><a href="cleaner.do.php?mcp=', $v3->fk_modele, ',', $v3->fk_code, ',', $v3->fk_puissance, '"><img src="graphics/delete.png" /></a></td></tr>';
            }
          }
          ?>
        </table>
      </fieldset>
      
      <?php
      $db->close();
      ?>
    </section>
  </body>
</html>