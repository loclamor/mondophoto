<?php

class LacPaysSVG extends Entite {
	
	public $id;
	public $idPays;
	public $coordonnees;
	
	public $DB_table = 'pays_svg_lac';
	public $DB_equiv = array(
		'id' => 'id',
		'idPays' => 'idPays',
		'coordonnees' => 'coordonnees'
	);
	
	public function getId() {
		return $this->id;
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
	
	public function setIdPays($id) {
		$this->idPays = $id;
	}
	
	public function setCoordonnees($coord) {
		$this->coordonnees = $coord;
	}
}