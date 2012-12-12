<?php
class GestionPhotosVip {
	
	/**
	 * @var GestionPhotosVip
	 */
	private static $instance = null;
	
	private $photos = array();
	
	public static function getInstance() {
		if(is_null(self::$instance)) {
		self::$instance = new GestionPhotosVip();
		}
		return self::$instance;
	}
	
	/**
	 * @param integer $id
	 * @return VipPhoto
	 */
	public function getPhotoVip($id) {
		if(array_key_exists($id, $this->photos)) {
			return $this->photos[$id];
		}
		else {
			$photo = new VipPhoto($id);
			$this->photos[$id] = $photo;
			return $photo;
		}
	}
	
	public function getPhotosVip($orderby = 'id', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT id FROM vip_photo'.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$photos = array();
			foreach ($res as $row) {
				$photos[] = $this->getPhotoVip($row['id']);
			}
		}
		else {
			$photos = false;
		}
		return $photos;
	}
	
	public function getPhotosLieuVip($idLieuVip, $orderby = 'id', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT id FROM vip_photo WHERE idVipLieu = '.$idLieuVip.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$photos = array();
			foreach ($res as $row) {
				$photos[] = $this->getPhotoVip($row['id']);
			}
		}
		else {
			$photos = false;
		}
		return $photos;
	}
}