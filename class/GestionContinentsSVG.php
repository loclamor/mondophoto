<?php
class GestionContinentsSVG {
	
	/**
	 * @var GestionContinentsSVG
	 */
	private static $instance = null;
	
	private $continentsSVG = array();
	
	public static function getInstance() {
		if(is_null(self::$instance)) {
		self::$instance = new GestionContinentsSVG();
		}
		return self::$instance;
	}
	
	/**
	 * @param integer $id
	 * @return ContinentSVG
	 */
	public function getContinentSVG($id) {
		if(array_key_exists($id, $this->continentsSVG)) {
			return $this->continentsSVG[$id];
		}
		else {
			$continentSVG = new ContinentSVG($id);
			$this->continentsSVG[$id] = $continentSVG;
			return $continentSVG;
		}
	}
	
	public function getPaysContinentSVG($idContinent, $orderby = 'id', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT id FROM continent_svg WHERE idContinent = '.$idContinent.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$continentsSVG = array();
			foreach ($res as $row) {
				$continentsSVG[] = $this->getContinentSVG($row['id']);
			}
		}
		else {
			$continentsSVG = false;
		}
		return $continentsSVG;
	}
}