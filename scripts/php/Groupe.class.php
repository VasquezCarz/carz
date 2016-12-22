<?php

class Groupe {
  public $id_groupe;
  public $lib_groupe;
  public $fk_groupe;  
  
  public function info() {
    return '#' . $this->id_groupe . " - libelle " . $this->lib_groupe;
  }
}

?>