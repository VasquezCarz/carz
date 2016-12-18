<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Carz</title>
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

  <body>
    <?php
    include 'config/carz.conf.php';
    include PATH_SCRIPTS.'/php/Database.class.php';
	
    
    ?>
    <header>
      <?php include "header.inc.php"; ?>
    </header>
    
    <nav>
      
      <?php include 'nav.inc.php'; ?>
      <h2>Trombinoscope</h2>
    </nav>
    
    <section>
      <p>A venir...</p>
    </section>
		
    <footer>
      <?php include "footer.inc.php"; ?>
    </footer>
  </body>
</html>