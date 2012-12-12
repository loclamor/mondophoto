<?php

class PaysSVG extends Entite {
	
	public $id;
	public $idPays;
	public $idStranger = null;
	public $coordonnees;
	
	public $DB_table = 'pays_svg';
	public $DB_equiv = array(
		'id' => 'id',
		'idPays' => 'idPays',
		'idStranger' => 'idStranger',
		'coordonnees' => 'coordonnees'
	);
	
	public function getId() {
		return $this->id;
	}
	
	public function getIdPays() {
		return $this->idPays;
	}
	
	public function getIdPaysStranger() {
		return $this->idStranger;
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
	
	public function setIdPaysStranger($id) {
		$this->idStranger = $id;
	}
	
	public function setCoordonnees($coord) {
		$this->coordonnees = $coord;
	}
}