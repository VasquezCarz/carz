<?php

class Pays {
  public $id_pays;
  public $lib_pays;
  public $code_pays;
  
  public function info() {
    return '#' . $this->id_pays . " - libelle " . $this->lib_pays;
  }
}

?>