<!DOCTYPE html>
<html>
  <head>
    <title>Carz - Mot de passe oublié</title>
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
      <h2>Mot de passe oublié</h2>
    </nav>
  
    <section>
      <fieldset>
        <legend>Mot de passe oublié</legend>
        <form name="frmForgottenPassword" action="user_send_password_reset.do.php" method="post">
          E-mail :
          <input type="text" name="txtMail" size="30" />
          <input type="submit" value="Envoyer" />
        </form>
        <div class="info">Vous recevrez un mail de réinitialisation de vos identifiants de connexion.</div>
      </fieldset>
    </section>
    
    <footer>
      <?php include 'footer.inc.php'; ?>
    </footer>
  </body>
</html>