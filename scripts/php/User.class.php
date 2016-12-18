<?php

class User {
	public $id_utilisateur;
	public $login;
	public $password;
	public $mail;
	public $admin;
	public $nom;
	public $prenom;
	public $hash_activation;
	
	
	
	public function info(){
		return '#' . $this->id_utilisateur . " - libelle " . $this->login;
	}
}

?>