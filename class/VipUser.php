<?php

class VipUser extends Entite {
	
	public $id;
	public $nom;
	public $prenom;
	public $password;
	public $mail;
	public $actif = 0;
	public $banni = 0;
	
	public $DB_table = 'vip_user';
	public $DB_equiv = array(
		'id' => 'id',
		'nom' => 'nom',
		'prenom' => 'prenom',
		'password' => 'password',
		'mail' => 'mail',
		'actif' => 'actif',
		'banni' => 'banni'
	);
	
	public function getId() {
		return $this->id;
	}
	
	public function getNom() {
		return $this->nom;
	}
	
	public function getPrenom() {
		return $this->prenom;
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function getMail() {
		return $this->mail;
	}
	
	public function isActif() {
		return $this->actif == 1;
	}
	
	public function isBanni() {
		return $this->banni == 1;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function setNom($nom) {
		$this->nom = $nom;
	}
	
	public function setPrenom($prenom) {
		$this->prenom = $prenom;
	}
	
	public function setPassword($pass) {
		$this->password = $pass;
	}
	
	public function setMail($mail) {
		$this->mail = $mail;
	}
	
	public function setActif($bool = true) {
		$this->actif = $bool?1:0;
	}
	
	public function setBanni($bool = true) {
		$this->banni = $bool?1:0;
	}
}