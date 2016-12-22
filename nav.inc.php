<div id="nav">
  <div id="nav_links">
    <a href="index.php">Accueil</a>
    <a href="stats.php">Statistiques</a>
	<a href="trombi.php">Trombinoscope</a>
  </div>
  <div id="nav_auth">
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
    <div style="width:150px; float:right; ">
      <a href="profile.php"><img src="graphics/user.png" title="Mon compte" />[<?php echo $_SESSION['login'] ?>]</a>
      <a href="admin.php"><img src="graphics/cog.png" title="Administration" /></a>
      <a href="logout.do.php"><img src="graphics/cancel.png" title="Se déconnecter" /></a>
    </div>
    <?php
    }
    // Affichage d'un éventuel message
    $msg = empty($_SESSION['msg']) ? '' : $_SESSION['msg'];
    echo $msg;
    $_SESSION['msg'] = '';
    ?>
  </div>
</div>