<?php
class GestionLiens {
	
	/**
	 * @var GestionLiens
	 */
	private static $instance = null;
	
	private $liens = array();
	
	public static function getInstance() {
		if(is_null(self::$instance)) {
		self::$instance = new GestionLiens();
		}
		return self::$instance;
	}
	
	/**
	 * @param integer $id
	 * @return Lien
	 */
	public function getLien($id) {
		if(array_key_exists($id, $this->liens)) {
			return $this->liens[$id];
		}
		else {
			$lien = new Lien($id);
			$this->liens[$id] = $lien;
			return $lien;
		}
	}
	
	public function getLiens($orderby = 'id', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT id FROM lien'.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$liens = array();
			foreach ($res as $row) {
				$liens[] = $this->getLien($row['id']);
			}
		}
		else {
			$liens = false;
		}
		return $liens;
	}
	
	public function getLiensLieu($idLieu, $orderby = 'id', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT id FROM lien WHERE idLieu = '.$idLieu.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$liens = array();
			foreach ($res as $row) {
				$liens[] = $this->getLien($row['id']);
			}
		}
		else {
			$liens = false;
		}
		return $liens;
	}
	
	public function getLiensInactifsLieu($idLieu, $orderby = 'id', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT id FROM lien WHERE valide = 0 and idLieu = '.$idLieu.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$liens = array();
			foreach ($res as $row) {
				$liens[] = $this->getLien($row['id']);
			}
		}
		else {
			$liens = false;
		}
		return $liens;
	}
	
	public function getAllLiensInactifs( $orderby = 'id', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT id FROM lien WHERE valide = 0 '.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$liens = array();
			foreach ($res as $row) {
				$liens[] = $this->getLien($row['id']);
			}
		}
		else {
			$liens = false;
		}
		return $liens;
	}
	
	public function getLiensLieuByType($idLieu, $type, $orderby = 'id', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT id FROM lien WHERE idLieu = '.$idLieu.' and typeLien=\''.$type.'\''.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$liens = array();
			foreach ($res as $row) {
				$liens[] = $this->getLien($row['id']);
			}
		}
		else {
			$liens = false;
		}
		return $liens;
	}
}