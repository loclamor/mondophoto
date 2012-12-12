<?php

class ContinentSVG extends Entite {
	
	public $id;
	public $idContinent;
	public $idPays;
	public $coordonnees;
	
	public $DB_table = 'continent_svg';
	public $DB_equiv = array(
		'id' => 'id',
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
	
	public function setCoordonnees($coord) {
		$this->coordonnees = $coord;
	}
}