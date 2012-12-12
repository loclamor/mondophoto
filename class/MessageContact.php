<?php

class MessageContact extends Entite {
	
	public $id;
	public $date = null; //2012-06-18 21:58:00
	public $nom;
	public $mail;
	public $sujet;
	public $message;
	public $lu = 0;
	public $idUser = null;
	
	public $DB_table = 'message_contact';
	public $DB_equiv = array(
		'id' => 'id',
		'date' => 'dateMessage',
		'nom' => 'nom',
		'mail' => 'adresseMail',
		'sujet' => 'sujet',
		'message' => 'message',
		'lu' => 'estLu',
		'idUser' => 'idUtilisateur'
	);
	
	public function getId() {
		return $this->id;
	}
	
	public function getDate() {
		return $this->date;
	}
	
	public function getNom() {
		return $this->nom;
	}
	
	public function getMail() {
		return $this->mail;
	}
	
	public function getSujet() {
		return $this->sujet;
	}
	
	public function getMessage() {
		return $this->message;
	}
	
	public function isLu() {
		return $this->lu == 1;
	}
	
	public function getIdUser() {
		return $this->idUser;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function setDate($date) {
		$this->date = $date;
	}
	
	public function setNom($nom) {
		$this->nom = $nom;
	}
	
	public function setMail($mail) {
		$this->mail = $mail;
	}
	
	public function setSujet($sujet) {
		$this->sujet = $sujet;
	}
	
	public function setMessage($msg) {
		$this->message = $msg;
	}
	
	public function setLu($estLu = true) {
		$this->lu = $estLu?1:0;
	}
	
	public function setIdUser($id) {
		$this->idUser = $id;
	}
}