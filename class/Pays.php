<?php

class Pays extends Entite {
	
	public $id;
	public $idContinent;
	public $nom;
	public $urlCarte;
	public $urlCarteFrom;
	public $sensCarte;
	
	public $DB_table = 'pays';
	public $DB_equiv = array(
		'id' => 'idPays',
		'idContinent' => 'idContinent',
		'nom' => 'nom',
		'urlCarte' => 'urlCarte',
		'urlCarteFrom' => 'urlCarteFrom',
		'sensCarte' => 'sensCarte'
	);
	
	public function getId() {
		return $this->id;
	}
	
	public function getIdContinent() {
		return $this->idContinent;
	}
	
	public function getNom() {
		return $this->nom;
	}
	
	public function getUrlCarte() {
		return $this->urlCarte;
	}
	
	public function getUrlCarteFrom() {
		return $this->urlCarteFrom;
	}
	
	public function getSensCarte() {
		return $this->sensCarte;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function setIdContinent($id) {
		$this->idContinent = $id;
	}
	
	public function setNom($nom) {
		$this->nom = $nom;
	}
	
	public function setUrlCarte($url) {
		$this->urlCarte = $url;
	}
	
	public function setUrlCarteFrom($url) {
		$this->urlCarteFrom = $url;
	}
	
	public function setSensCarte($sens) {
		$this->sensCarte = $sens;
	}
}