<?php

class Lieu extends Entite {
	
	public $id;
	public $idPays;
	public $nom;
	public $pronom = '';
	public $coordonnees = null;
	public $cx;
	public $cy;
	public $description = '';
	public $type;
	public $categorie = 3;
	public $note = 0;
	
	public $DB_table = 'lieu';
	public $DB_equiv = array(
		'id' => 'idLieu',
		'idPays' => 'idPays',
		'nom' => 'nom',
		'pronom' => 'pronom',
		'coordonnees' => 'coordonnees',
		'cx' => 'cx',
		'cy' => 'cy',
		'description' => 'description',
		'type' => 'type',
		'categorie' => 'categorie',
		'note' => 'note'
	);
	
	public function getId() {
		return $this->id;
	}
	
	public function getIdPays() {
		return $this->idPays;
	}
	
	public function getNom() {
		return $this->nom;
	}
	
	public function getPronom() {
		return $this->pronom;
	}
	
	public function getCoordonnees() {
		return $this->coordonnees;
	}
	
	public function getCx() {
		return $this->cx;
	}
	
	public function getCy() {
		return $this->cy;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function getCategorie() {
		return $this->categorie;
	}
	
	public function getNote() {
		return $this->note;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function setIdPays($id) {
		$this->idPays = $id;
	}
	
	public function setNom($nom) {
		$this->nom = $nom;
	}
	
	public function setPronom($pronom) {
		$this->pronom = $pronom;
	}
	
	public function setCoordonnees($coord) {
		$this->coordonnees = $coord;
	}
	
	public function setCx($cx) {
		$this->cx = $cx;
	}
	
	public function setCy($cy) {
		$this->cy = $cy;
	}
	
	public function setDescription($desc) {
		$this->description = $desc;
	}
	
	public function setType($type) {
		$this->type = $type;
	}
	
	public function setCategorie($categorie) {
		$this->categorie = $categorie;
	}
	
	public function setNote($note) {
		$this->note = $note;
	}
	
	/**
	 * retourne vrai si le lieu contient des lieux lies
	 * @return boolean
	 */
	public function hasLieuxLies () {
		$lieuxLies = GestionLieux::getInstance()->getLieuxLies($this->id);
		return is_array($lieuxLies);
	}
}