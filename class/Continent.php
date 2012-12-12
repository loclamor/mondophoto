<?php

class Continent extends Entite {
	
	public $id;
	public $nom;
	public $urlCarte;
	public $urlCarteFrom;
	public $couleur;
	public $couleurOcean;
	public $sensCarte;
	
	public $DB_table = 'continent';
	public $DB_equiv = array(
		'id' => 'idContinent',
		'nom' => 'nom',
		'couleur' => 'couleur',
		'couleurOcean' => 'couleurOcean',
		'urlCarte' => 'urlCarte',
		'urlCarteFrom' => 'urlCarteFrom',
		'sensCarte' => 'sensCarte'
	);
	
	public function getId() {
		return $this->id;
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
	
	public function getCouleur() {
		return $this->couleur;
	}
	
	public function getCouleurOcean() {
		return $this->couleurOcean;
	}
	
	public function getSensCarte() {
		return $this->sensCarte;
	}
	
	public function setId($id) {
		$this->id = $id;
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
	
	public function setCouleur($couleur) {
		$this->couleur = $couleur;
	}
	
	public function setCouleurOcean($couleurOcean) {
		$this->couleurOcean = $couleurOcean;
	}
	
	public function setSensCarte($sens) {
		$this->sensCarte = $sens;
	}
}