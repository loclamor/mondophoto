<?php
class GestionContinents {
	
	/**
	 * @var GestionContinents
	 */
	private static $instance = null;
	
	private $continents = array();
	
	public static function getInstance() {
		if(is_null(self::$instance)) {
		self::$instance = new GestionContinents();
		}
		return self::$instance;
	}
	
	/**
	 * @param integer $id
	 * @return Continent
	 */
	public function getContinent($id) {
		if(array_key_exists($id, $this->continents)) {
			return $this->continents[$id];
		}
		else {
			$continent = new Continent($id);
			$this->continents[$id] = $continent;
			return $continent;
		}
	}
	
	public function getContinents($orderby = 'idContinent', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT idContinent FROM continent'.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$continents = array();
			foreach ($res as $row) {
				$continents[] = $this->getContinent($row['idContinent']);
			}
		}
		else {
			$continents = false;
		}
		return $continents;
	}
	
	public function getIdPaysContinent($idContinent){
		$res = SQL::getInstance()->exec('SELECT idPays FROM pays WHERE idContinent='.$idContinent);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$pays = array();
			foreach ($res as $row) {
				$pays[] = $row['idPays'];
			}
		}
		else {
			$pays = false;
		}
		return $pays;
	}
}