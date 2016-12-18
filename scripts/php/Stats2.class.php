<?php

class Stats2 {
	public $energie;
	public $nb_energie;
	
	
	
	public function info(){
		return '#' . $this->energie . " - libelle " . $this->nb_energie;
	}
}

?>