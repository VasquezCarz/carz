<div>
  [<a href="index.php">Index</a>]
  [<a href="stats.php">Stats</a>]
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
    [<a href="profile.php">Mon compte (<?php echo $_SESSION['login'] ?>)</a>]
    [<a href="admin.php">Administration</a>]
    [<a href="logout.do.php">Me déconnecter</a>]
  <?php
  }
  // Affichage d'un éventuel message
  $msg = empty($_SESSION['msg']) ? '' : $_SESSION['msg'];
  echo $msg;
  $_SESSION['msg'] = '';
  ?>
</div>