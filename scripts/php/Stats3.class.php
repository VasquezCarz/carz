<?php

class Stats3 {
	public $suralimentation;
	public $nb_sural;
	
	
	
	public function info(){
		return '#' . $this->suralimentation . " - libelle " . $this->nb_sural;
	}
}

?>