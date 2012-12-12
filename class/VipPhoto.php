<?php

class VipPhoto extends Entite {
	
	public $id;
	public $idVipLieu;
	public $urlImage;
	
	public $DB_table = 'vip_photo';
	public $DB_equiv = array(
		'id' => 'id',
		'idVipLieu' => 'idVipLieu',
		'urlImage' => 'urlImage'
	);
	
	public function getId() {
		return $this->id;
	}
	
	public function getIdVipLieu() {
		return $this->idVipLieu;
	}
	
	public function getUrlImage() {
		return $this->urlImage;
	}
		
	public function setId($id) {
		$this->id = $id;
	}
	
	public function setIdVipLieu($id) {
		$this->idVipLieu = $id;
	}
	
	public function setUrlImage($url) {
		$this->urlImage = $url;
	}
}