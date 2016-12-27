<?php
session_start();
if (empty($_SESSION['id_utilisateur'])) {
  $_SESSION['msg'] = '<span class="error">La session a expiré !</span>';
  header('Location: profile.php');
  exit();
}

$target_dir = "uploads/".str_pad($_SESSION['id_utilisateur'], 10, '0', STR_PAD_LEFT);
if (!file_exists($target_dir)) mkdir($target_dir);
$target_file = $target_dir."/".basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

// Check if image file is an actual image or fake image
if (isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    $_SESSION['msg'] = '<span class="success">Le fichier est bien une image : '.$check['mime'].'.</span>';
    $uploadOk = 1;
  }
  else {
    $_SESSION['msg'] = '<span class="error">Le fichier n\'est pas une image !</span>';
    $uploadOk = 0;
  }
}

// Check if file already exists
if (file_exists($target_file)) {
  $_SESSION['msg'] = '<span class="error">Désolé, le fichier existe déjà !</span>';
  $uploadOk = 0;
}

// Check file size (<= 1 MB)
if ($_FILES["fileToUpload"]["size"] > 2097152) {
  $_SESSION['msg'] = '<span class="error">Désolé, votre fichier est trop grand ! (Taille max : 2 Mo)</span>';
  $uploadOk = 0;
}

// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "jpeg") {
  $_SESSION['msg'] = '<span class="error">Désolé, seuls les fichiers JPG et JPEG sont autorisés !</span>';
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  $_SESSION['msg'] = '<span class="error">Désolé, votre fichier n\'a pas été téléchargé !</span>';
  // if everything is ok, try to upload file
}
else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    $_SESSION['msg'] = '<span class="success">Le fichier '.basename( $_FILES["fileToUpload"]["name"]).' a bien été téléchargé.</span>';
    include('config/carz.conf.php');
    include(PATH_SCRIPTS.'/php/Image.class.php');
    $img = new Image();
    $img->open($target_file);
    $height = min(720, $img->getHeight());
    $name = empty($_POST['picture']) ? 'avatar' : $_POST['picture'];
    $img->saveAsCustom(pathinfo($target_file, PATHINFO_DIRNAME).'/'.$name.'.jpg', 0, $height);
    unlink($target_file);
  }
  else {
    $_SESSION['msg'] = '<span class="error">Désolé, il y a eu une erreur lors du téléchargement de votre fichier !</span>';
  }
}

header('Location: profile.php');
exit();
?>