<?php

class Connexion extends Entite {
	
	public $id;
	public $ip;
	public $moment;
	
	public $DB_table = 'connexion';
	public $DB_equiv = array(
		'id' => 'id',
		'ip' => 'ip',
		'moment' => 'moment'
	);
	
	public function getId() {
		return $this->id;
	}
	
	public function getIp() {
		return $this->ip;
	}
	
	public function getMoment() {
		return $this->moment;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function setIp($ip) {
		$this->ip = $ip;
	}
	
	public function setMoment($moment) {
		$this->moment = $moment;
	}
}