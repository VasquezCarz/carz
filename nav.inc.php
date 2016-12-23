<div id="nav">

  <div>
    <?php
    // Contrôle de la variable de session 'login'
    // Si elle est vide alors on affiche le formulaire d'authentification
    if (empty($_SESSION['id_utilisateur'])) {
    ?>
      <form name="login" action="login.do.php" method="post">
        Login <input type="text" name="login" />
        Mot de passe <input type="password" name="password" />
        <input type="submit" value="Connexion" />&nbsp;
        [<a href="user_registration.php">S'inscrire</a>]
        [<a href="user_forgotten_password.php">Mot de passe oublié</a>]
      </form>
    <?php
    }
    // Sinon on affiche le lien de déconnexion
    else {
    ?>
    <div style="float: right;">
      <a href="profile.php"><img src="graphics/user.png" title="Profil" /> Profil [<?php echo $_SESSION['login'] ?>]</a>
      <a href="admin.php"><img src="graphics/cog.png" title="Administration" /> Administration</a>
      <a href="logout.do.php"><img src="graphics/cancel.png" title="Déconnexion" /> Déconnexion</a>
    </div>
    <?php
    }
    // Affichage d'un éventuel message
    $msg = empty($_SESSION['msg']) ? '' : $_SESSION['msg'];
    echo $msg;
    $_SESSION['msg'] = '';
    ?>
  </div>
  
  <ul class="tabs">
    <li id="home_nav"><a href="index.php">Accueil</a></li>
    <li id="stats_nav"><a href="stats.php">Statistiques</a></li>
    <li id="trombi_nav"><a href="trombi.php">Trombinoscope</a></li>
  </ul>
</div>