<?php

class Lien extends Entite {
	
	public $id;
	public $idLieu;
	public $nom;
	public $url;
	public $type = null;
	public $valide = 1;
	
	public $DB_table = 'lien';
	public $DB_equiv = array(
		'id' => 'id',
		'idLieu' => 'idLieu',
		'nom' => 'nom',
		'url' => 'url',
		'type' => 'typeLien',
		'valide' => 'valide'
	);
	
	public function getId() {
		return $this->id;
	}
	
	public function getIdLieu() {
		return $this->idLieu;
	}
	
	public function getNom() {
		return $this->nom;
	}
	
	public function getUrl() {
		return $this->url;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function isValide() {
		return $this->valide == 1;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function setIdLieu($id) {
		$this->idLieu = $id;
	}
	
	public function setNom($nom) {
		$this->nom = $nom;
	}
	
	public function setUrl($url) {
		$this->url = $url;
	}
	
	public function setType($type) {
		$this->type = $type;
	}
	
	public function setValide($bool) {
		$this->valide = $bool?1:0;
	}
}