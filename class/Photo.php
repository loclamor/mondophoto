<?php

class Photo extends Entite {
	
	public $id;
	public $idLieu;
	public $urlPhoto;
	public $nom;
	public $proprietaire;
	public $datePriseVue; //aaaa-mm-jj
	public $img_ppal;
	public $affiche;
	
	public $DB_table = 'photo';
	public $DB_equiv = array(
		'id' => 'idPhoto',
		'idLieu' => 'idLieu',
		'urlPhoto' => 'urlPhoto',
		'nom' => 'nom',
		'proprietaire' => 'proprietaire',
		'datePriseVue' => 'datePV',
		'img_ppal' => 'img_ppal',
		'affiche' => 'affiche'
	);
	
	public function getId() {
		return $this->id;
	}
	
	public function getIdLieu() {
		return $this->idLieu;
	}
	
	public function getNom() {
		return $this->nom;
	}
	
	public function getUrlPhoto() {
		return $this->urlPhoto;
	}
	
	/**
	 * Retourne l'adresse de la miniature
	 * Si la miniature n'existe pas, celle-ci est créée.
	 */
	public function getUrlPhotoMiniature($resizeSize = 150, $resizeType = 'H') {
		$path_parts = pathinfo($this->getUrlPhoto());
		
		$minFile = $path_parts['dirname'].'/'.$path_parts['filename'].'.min'.$resizeType.$resizeSize.'.'.$path_parts['extension'];
		if(!file_exists($minFile)) {
			return redimJPEG($this->getUrlPhoto(), $resizeSize, $resizeType, $minFile);
		}
		else {
			return $minFile;
		}
	}
	
	public function getProprietaire() {
		return $this->proprietaire;
	}
	
	public function getDatePriseVue(){
		return $this->datePriseVue;
	}
	
	public function isImagePrincipale(){
		return $this->img_ppal == 1;
	}
	
	public function isAffiche(){
		return $this->affiche == 1;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function setIdLieu($id) {
		$this->idLieu = $id;
	}
	
	public function setNom($nom) {
		$this->nom = $nom;
	}
	
	public function setUrlPhoto($url) {
		$this->urlPhoto = $url;
	}
	
	public function setProprietaire($prop) {
		$this->proprietaire = $prop;
	}
	
	public function setDatePriseVue($date) {
		$this->datePriseVue = $date;
	}
	
	public function setImagePrincipale($bool = true) {
		$this->img_ppal = ($bool?1:0);
	}
	
	public function setAffiche($bool = true) {
		$this->affiche = ($bool?1:0);
	}
}