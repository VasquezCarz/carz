<?php
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Inscription</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="scripts/css/style.css" />
  </head>
  
  <body>
    <header>
      <h2>Inscription</h2>
      [<a href="index.php">Retour</a>]
      <?php
      if(isset($_SESSION['msg'])) echo $_SESSION['msg'];
      $_SESSION['msg'] = '';
      ?>
    </header>
    
    <nav></nav>
    
    <section>
      <br />
      <fieldset>
        <legend>Infos personnelles</legend>
        <form name="frmRegister" action="user_create.php" method="post">
          <table>
            <tr>
              <td>Login *</td>
              <td><input type="text" name="login" value="<?php if(isset($_SESSION['login'])) echo $_SESSION['login'] ?>" /></td>
            </tr>
            <tr>
              <td>Mot de passe *</td>
              <td><input type="password" name="password1" value="<?php if(isset($_SESSION['password1'])) echo $_SESSION['password1'] ?>" /></td>
            </tr>
            <tr>
              <td>Confirmer le mot de passe *</td>
              <td><input type="password" name="password2" value="<?php if(isset($_SESSION['password2'])) echo $_SESSION['password2'] ?>" /></td>
            </tr>
            <tr>
              <td>Nom *</td>
              <td><input type="text" name="nom" value="<?php if(isset($_SESSION['nom'])) echo $_SESSION['nom'] ?>" /></td>
            </tr>
            <tr>
              <td>Prénom *</td>
              <td><input type="text" name="prenom" value="<?php if(isset($_SESSION['prenom'])) echo $_SESSION['prenom'] ?>" /></td>
            </tr>
            <tr>
              <td>E-mail *</td>
              <td><input type="text" name="mail" value="<?php if(isset($_SESSION['mail'])) echo $_SESSION['mail'] ?>" /></td>
            </tr>
            <tr>
              <td colspan="2" align="right"><input type="submit" value="S'inscrire" /></td>
            </tr>
          </table>
        </form>
      </fieldset>
    </section>
  </body>
</html>