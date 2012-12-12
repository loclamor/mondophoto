<?php

class VipLieu extends Entite {
	
	public $id;
	public $idVip;
	public $nomPays = null;
	public $nomVille = null;
	public $nomLieu = null;
	
	public $DB_table = 'vip_lieu';
	public $DB_equiv = array(
		'id' => 'id',
		'idVip' => 'idVip',
		'nomPays' => 'nomPays',
		'nomVille' => 'nomVille',
		'nomLieu' => 'nomLieu'
	);
	
	public function getId() {
		return $this->id;
	}
	
	public function getIdVip() {
		return $this->idVip;
	}
	
	public function getNomPays() {
		return $this->nomPays;
	}
	
	public function getNomVille() {
		return $this->nomVille;
	}
	
	public function getNomLieu() {
		return $this->nomLieu;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function setIdVip($id) {
		$this->idVip = $id;
	}
	
	public function setNomPays($nom) {
		$this->nomPays = $nom;
	}
	
	public function setNomVille($nom) {
		$this->nomVille = $nom;
	}
	
	public function setNomLieu($nom) {
		$this->nomLieu = $nom;
	}
}