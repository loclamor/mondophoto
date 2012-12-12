<?php
class GestionPhotos {
	
	/**
	 * @var GestionPhotos
	 */
	private static $instance = null;
	
	private $photos = array();
	
	public static function getInstance() {
		if(is_null(self::$instance)) {
		self::$instance = new GestionPhotos();
		}
		return self::$instance;
	}
	
	/**
	 * @param integer $id
	 * @return Photo
	 */
	public function getPhoto($id) {
		if(array_key_exists($id, $this->photos)) {
			return $this->photos[$id];
		}
		else {
			$photo = new Photo($id);
			$this->photos[$id] = $photo;
			return $photo;
		}
	}
	
	public function getPhotos($orderby = 'idPhoto', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT idPhoto FROM photo'.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$photos = array();
			foreach ($res as $row) {
				$photos[] = $this->getPhoto($row['idPhoto']);
			}
		}
		else {
			$photos = false;
		}
		return $photos;
	}
	
	/**
	 * Ne retourne qu'une seule photo
	 * TODO
	 * Si le lieu est une ville, on prend un lieu de la ville au hasard.
	 * 
	 * @param integer $idLieu le lieu dont on veut la photo principale
	 * @return Photo
	 */
	public function getLieuPhoto($idLieu){
		$lieu = new Lieu($idLieu);
		if($lieu->getType() == 'ville'){
			$res = SQL::getInstance()->exec('SELECT idLieuFils FROM lie WHERE idLieuParent ='.$idLieu.' ORDER BY RAND() LIMIT 0, 1');
			if($res) {
				$idLieu = $res[0]['idLieuFils'];
			}
		}
		$res = SQL::getInstance()->exec('SELECT idPhoto FROM photo WHERE idLieu='.$idLieu.' ORDER BY img_ppal DESC LIMIT 0, 1');
		if($res) {
			return $this->getPhoto($res[0]['idPhoto']);
		}
		else {
			$photo = new Photo();
			$photo->setUrlPhoto('style/appareil-photo.jpg');
			return $photo;
		}
	}
	
	/**
	 * 
	 * Retourne toutes les photos d'un lieu
	 * @param integer $idLieu le lieu dont on veut les photos
	 * @param String $orderby la colonne sur laquelle on veut trier (idPhoto par défaut)
	 * @param boolean $desc le sens du trie (false, soit asc par défaut)
	 * @return Array
	 */
	public function getLieuPhotos($idLieu, $orderby = 'idPhoto', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT idPhoto FROM photo WHERE idLieu='.$idLieu.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$photos = array();
			foreach ($res as $row) {
				$photos[] = $this->getPhoto($row['idPhoto']);
			}
		}
		else {
			$photos = false;
		}
		return $photos;
	}
	
	/**
	 * 
	 * Retourne le nombre de photos d'un lieu
	 * @param integer $idLieu le lieu dont on veut le nombre de photos
	 * @return Integer
	 */
	public function getNombreLieuPhoto ($idLieu) {
		$res = SQL::getInstance()->exec('SELECT idPhoto FROM photo WHERE idLieu='.$idLieu);
		if($res) {
			return count($res);
		}
		return 0;
	}
}