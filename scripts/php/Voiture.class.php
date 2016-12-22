<?php

class Voiture {
  public $id_voiture;
  public $lib_voiture;
  public $annee;
  public $lib_marque;
  public $lib_modele;
  public $lib_code;
  public $lib_motorisation;
  public $energie;
  public $puissance;
  public $couple;
  public $lib_boite;
  public $id_utilisateur;
  public $login;
  public $id_marque;
  public $id_modele;
  public $id_code;
  public $id_motorisation;
  public $id_puissance;
  public $id_boite;
  public $fk_voiture;
  public $fk_groupe;
  
  public function info() {
    return '#' . $this->id_voiture . " - libelle " . $this->lib_voiture;
  }
}

?>