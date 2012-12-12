<?php
class GestionMaps {
	
	/**
	 * @var GestionMaps
	 */
	private static $instance = null;
	
	private $maps = array();
	
	public static function getInstance() {
		if(is_null(self::$instance)) {
		self::$instance = new GestionMaps();
		}
		return self::$instance;
	}
	
	/**
	 * @param integer $id
	 * @return Map
	 */
	public function getMap($id) {
		if(array_key_exists($id, $this->maps)) {
			return $this->maps[$id];
		}
		else {
			$map = new Map($id);
			$this->maps[$id] = $map;
			return $map;
		}
	}
	
	public function getMaps($orderby = 'idMap', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT idMap FROM map'.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$maps = array();
			foreach ($res as $row) {
				$maps[] = $this->getMap($row['idMap']);
			}
		}
		else {
			$maps = false;
		}
		return $maps;
	}
	
	public function getContinentMaps($idContinent,$orderby = 'idMap', $desc = false){
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT idMap FROM map WHERE idContinent = '.((int)$idContinent).$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$maps = array();
			foreach ($res as $row) {
				$maps[] = $this->getMap($row['idMap']);
			}
		}
		else {
			$maps = false;
		}
		return $maps;
	}
	
	public function getPaysMaps($idPays,$orderby = 'idMap', $desc = false){
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT idMap FROM map WHERE idPays = '.((int)$idPays).$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$maps = array();
			foreach ($res as $row) {
				$maps[] = $this->getMap($row['idMap']);
			}
		}
		else {
			$maps = false;
		}
		return $maps;
	}
}