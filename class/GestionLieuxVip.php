<?php
class GestionLieuxVip {
	
	/**
	 * @var GestionLieuxVip
	 */
	private static $instance = null;
	
	private $lieux = array();
	
	public static function getInstance() {
		if(is_null(self::$instance)) {
		self::$instance = new GestionLieuxVip();
		}
		return self::$instance;
	}
	
	/**
	 * @param integer $id
	 * @return VipLieu
	 */
	public function getLieuVip($id) {
		if(array_key_exists($id, $this->lieux)) {
			return $this->lieux[$id];
		}
		else {
			$lieu = new VipLieu($id);
			$this->lieux[$id] = $lieu;
			return $lieu;
		}
	}
	
	public function getLieuxVip($orderby = 'id', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT id FROM vip_lieu'.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$lieux = array();
			foreach ($res as $row) {
				$lieux[] = $this->getLieuVip($row['id']);
			}
		}
		else {
			$lieux = false;
		}
		return $lieux;
	}
	
	
}