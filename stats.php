<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Carz - Statistiques</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="UTF-8" />
    <meta name="title" content="Carz" />
    <meta name="author" content="Vasquez" />
    <meta name="language" content="fr" />
    <meta name="keywords" content="vasquez, audi, friends, club" />
    <meta name="robots" content="index, follow" />
    <!--<link rel="icon" type="image/png" href="graphics/favicon.png" />-->
    <link rel="stylesheet" type="text/css" href="scripts/css/style.css" />
    <script src="scripts/js/Chart.min.js"></script>
  </head>

  <body id="stats">
    <?php
    include 'config/carz.conf.php';
    include PATH_SCRIPTS.'/php/Database.class.php';
  
    require_once(PATH_SCRIPTS.'/php/Stats1.class.php');
    require_once(PATH_SCRIPTS.'/php/Stats2.class.php');
    require_once(PATH_SCRIPTS.'/php/Stats3.class.php'); 
    ?>
    <header>
      <?php include 'header.inc.php'; ?>
    </header>
    
    <nav>
      <?php include 'nav.inc.php'; ?>
    </nav>
    
    <section>
      <?php
      $db = new Database();
      $db->connect();
      
      $query = 'SELECT COUNT(v.id_voiture) AS ccnt,';
      $query .= ' MIN(p.puissance) AS pmin, ROUND(AVG(p.puissance), 2) AS pavg, MAX(p.puissance) AS pmax, SUM(p.puissance) AS psum,';
      $query .= ' MIN(p.couple) AS tmin, ROUND(AVG(p.couple), 2) AS tavg, MAX(p.couple) AS tmax, SUM(p.couple) AS tsum,';
      $query .= ' MIN(m.cylindree) AS dmin, ROUND(AVG(m.cylindree), 2) AS davg, MAX(m.cylindree) AS dmax, SUM(m.cylindree) AS dsum,';
      $query .= ' MIN(m.nb_cylindres) AS cmin, ROUND(AVG(m.nb_cylindres), 2) AS cavg, MAX(m.nb_cylindres) AS cmax, SUM(m.nb_cylindres) AS csum,';
      $query .= ' MIN(m.nb_soupapes) AS vmin, ROUND(AVG(m.nb_soupapes), 2) AS vavg, MAX(m.nb_soupapes) AS vmax, SUM(m.nb_soupapes) AS vsum';
      $query .= ' FROM crz_voiture v';
      $query .= ' INNER JOIN crz_puissance p ON v.fk_puissance = p.id_puissance';
      $query .= ' INNER JOIN crz_motorisation m ON p.fk_motorisation = m.id_motorisation';      
      $query .= ' WHERE v.id_voiture IN (SELECT fk_voiture FROM crz_groupe_voiture)';
      
      if ($result = $db->query($query)) {
        if ($stats1 = $result->fetch_object('Stats1')) {
      ?>  
      <div style="margin-top: 20px; height: 320px;">  
        <div style="width:400px; height: 300px; float: left;">    
          <table class="border stats">
            <tr>
              <td></td>
              <th class="stats_taux">Min</th>
              <th class="stats_taux">Moy</th>
              <th class="stats_taux">Max</th>
              <th class="stats_taux">Total</th>
            </tr>
            <tr>
              <th>Nb voitures</th>
              <td></td>
              <td></td>
              <td></td>
              <td><?php echo $stats1->ccnt; ?></td>
            </tr>
            <tr>
              <th>Puissance (ch)</th>
              <td><?php echo $stats1->pmin; ?></td>
              <td><?php echo $stats1->pavg; ?></td>
              <td><?php echo $stats1->pmax; ?></td>
              <td><?php echo $stats1->psum; ?></td>
            </tr>
            <tr>
              <th>Couple (N.m)</th>
              <td><?php echo $stats1->tmin; ?></td>
              <td><?php echo $stats1->tavg; ?></td>
              <td><?php echo $stats1->tmax; ?></td>
              <td><?php echo $stats1->tsum; ?></td>
            </tr>
            <tr>
              <th>Cylindrée (cm<sup>3</sup>)</th>
              <td><?php echo $stats1->dmin; ?></td>
              <td><?php echo $stats1->davg; ?></td>
              <td><?php echo $stats1->dmax; ?></td>
              <td><?php echo $stats1->dsum; ?></td>
            </tr>
            <tr>
              <th>Nb cylindres</th>
              <td><?php echo $stats1->cmin; ?></td>
              <td><?php echo $stats1->cavg; ?></td>
              <td><?php echo $stats1->cmax; ?></td>
              <td><?php echo $stats1->csum; ?></td>
            </tr>
            <tr>
              <th>Nb soupapes</th>
              <td><?php echo $stats1->vmin; ?></td>
              <td><?php echo $stats1->vavg; ?></td>
              <td><?php echo $stats1->vmax; ?></td>
              <td><?php echo $stats1->vsum; ?></td>
            </tr>
          </table>
        </div>
        <div style="width:300px; height: 300px; float: left;">
          <canvas id="myChart3" width="300" height="300"></canvas>
        </div>    
      </div>    
      <?php
        }
      }
            
      $query = 'SELECT m.energie, COUNT(v.id_voiture) AS nb_energie';
      $query .= ' FROM crz_voiture v';
      $query .= ' INNER JOIN crz_puissance p ON v.fk_puissance = p.id_puissance';
      $query .= ' INNER JOIN crz_motorisation m ON p.fk_motorisation = m.id_motorisation';
      $query .= ' WHERE v.id_voiture IN (SELECT fk_voiture FROM crz_groupe_voiture)';
      $query .= ' GROUP BY m.energie';
      
      if ($result = $db->query($query)) {
      ?>
      <hr />
      
      <div style="margin-top: 20px; height: 320px;">    
        <div style="width: 300px; height: 300px; float: left;">
        <table class="border stats">
          <tr><th class="stats_label">Energie</th><th class="stats_nb">Nb</th><th class="stats_taux">Taux</th></tr>
          <?php
          $labels = '';
          $valeurs = '';
          while ($stats2 = $result->fetch_object('Stats2')) {
            // PG : pour les stats
            $labels .= '"' . ucfirst($stats2->energie) . '",';
            $valeurs .= $stats2->nb_energie . ',';
            // PG : Fin pour les stats
            echo '<tr><td>', ucfirst($stats2->energie), '</td><td>', $stats2->nb_energie, '</td><td>', round($stats2->nb_energie * 100 / $stats1->ccnt, 2), '%</td></tr>';
          }
          ?>
        </table>
        </div>
        <div style="width: 300px; height: 300px; float: left;">
          <canvas id="myChart" width="300" height="300"></canvas>
        </div>
      </div>
      <?php
      }
      ?>

      <hr />
      
      <div style="margin-top: 20px; height: 320px;">
        <?php
        $query = 'SELECT m.suralimentation, COUNT(v.id_voiture) AS nb_sural';
        $query .= ' FROM crz_voiture v';
        $query .= ' INNER JOIN crz_puissance p ON v.fk_puissance = p.id_puissance';
        $query .= ' INNER JOIN crz_motorisation m ON p.fk_motorisation = m.id_motorisation';
        $query .= ' WHERE v.id_voiture IN (SELECT fk_voiture FROM crz_groupe_voiture)';
        $query .= ' GROUP BY m.suralimentation';
        
        if ($result = $db->query($query)) {
        ?>
          <div style="width:300px; height: 300px; float: left;">
            <table class="border stats">
              <tr><th class="stats_label">Suralimentation</th><th class="stats_nb">Nb</th><th class="stats_taux">Taux</th></tr>
              <?php
              $labels2 = '';
              $valeurs2 = '';
              // PG : Fin pour les stats
              while ($stats3 = $result->fetch_object('Stats3')) {
                // PG : pour les stats
                $labels2 .= "\"" . ucfirst($stats3->suralimentation) . "\",";
                $valeurs2 .= $stats3->nb_sural . ",";
                echo '<tr><td>', ucfirst($stats3->suralimentation), '</td><td>', $stats3->nb_sural, '</td><td>', round($stats3->nb_sural * 100 / $stats1->ccnt, 2), '%</td></tr>';
              }
              ?>
            </table>
          </div>
          <div style="width:300px; height: 300px; float: left;">
            <canvas id="myChart2" width="300" height="300"></canvas>
          </div>
        <?php
        }
        $db->close();
        ?>
      </div>
    </section>
    
    <script>
    var data = {
      labels: [<?php echo $labels; ?>],
      datasets: [{
        label: '# of cars',
        data: [<?php echo $valeurs; ?>],
        backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
        hoverBackgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"]
      }]
    };
    
    var options = {
      title: {
        display: true,
        text: 'Energie',
        fullWidth: true
      }
    };
    
    var ctx = document.getElementById("myChart");
    var myPieChart = new Chart(ctx,{
      type: 'doughnut',
      data: data,
      options: options
    });
    
    // Graphique n°2
    var data2 = {
      labels: [<?php echo $labels2; ?>],
      datasets: [{
        label: '# of cars',
        data: [<?php echo $valeurs2; ?>],
        backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
        hoverBackgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"]
      }]
    };
    
    var options2 = {
      title: {
        display: true,
        text: 'Suralimentation',
        fullWidth: true
      }
    };
    
    var ctx2 = document.getElementById("myChart2");
    var myPieChart = new Chart(ctx2, {
      type: 'doughnut',
      data: data2 ,
      options: options2
    });
    
    // Graphique n°3 :
    var data3 = {
      labels: ["Puiss. Min.", "Puiss. Max.", "Puiss. Moy."],
      datasets: [{
        label: "Puissance des véhicules du groupe",
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)'
        ],
        borderColor: [
          'rgba(255,99,132,1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)'
        ],
        borderWidth: 1,
        data: [<?php echo $stats1->pmin ?>, <?php echo $stats1->pmax ?>, <?php echo $stats1->pavg ?>],
      }]
    };
    
    var ctx3 = document.getElementById("myChart3"); 
    var myBarChart = new Chart(ctx3, {
      type: 'bar',
      data: data3
    });
    </script>     

    <footer>
      <?php include "footer.inc.php"; ?>
    </footer>
  </body>
</html>