<?php

class Lie extends Entite {
	
	public $id;
	public $idLieuParent;
	public $idLieuFils;
	
	public $DB_table = 'lie';
	public $DB_equiv = array(
		'id' => 'id',
		'idLieuParent' => 'idLieuParent',
		'idLieuFils' => 'idLieuFils'
	);
	
	public function getId() {
		return $this->id;
	}
	
	public function getIdLieuParent() {
		return $this->idLieuParent;
	}
	
	public function getIdLieuFils() {
		return $this->idLieuFils;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function setIdLieuParent($id) {
		$this->idLieuParent = $id;
	}
	
	public function setIdLieuFils($id) {
		$this->idLieuFils = $id;
	}
}