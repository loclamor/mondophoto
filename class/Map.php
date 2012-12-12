<?php

class Map extends Entite {
	
	public $id;
	public $idContinent;
	public $idPays;
	public $coordonnees;
	
	public $DB_table = 'map';
	public $DB_equiv = array(
		'id' => 'idMap',
		'idContinent' => 'idContinent',
		'idPays' => 'idPays',
		'coordonnees' => 'coordonnees'
	);
	
	public function getId() {
		return $this->id;
	}
	
	public function getIdContinent() {
		return $this->idContinent;
	}
	
	public function getIdPays() {
		return $this->idPays;
	}
	
	public function getCoordonnees() {
		return $this->coordonnees;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function setIdContinent($id) {
		$this->idContinent = $id;
	}
	
	public function setIdPays($id) {
		$this->idPays = $id;
	}
	
	public function setCoordonnees($coords) {
		$this->coordonnees = $coords;
	}
	
}