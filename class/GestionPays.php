<?php
class GestionPays {
	
	/**
	 * @var GestionPays
	 */
	private static $instance = null;
	
	private $pays = array();
	
	public static function getInstance() {
		if(is_null(self::$instance)) {
		self::$instance = new GestionPays();
		}
		return self::$instance;
	}
	
	/**
	 * @param integer $id
	 * @return Pays
	 */
	public function getPays($id) {
		if(array_key_exists($id, $this->pays)) {
			return $this->pays[$id];
		}
		else {
			$pays = new Pays($id);
			$this->pays[$id] = $pays;
			return $pays;
		}
	}
	
	public function getAllPays($orderby = 'idPays', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT idPays FROM pays'.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$pays = array();
			foreach ($res as $row) {
				$pays[] = $this->getPays($row['idPays']);
			}
		}
		else {
			$pays = false;
		}
		return $pays;
	}
	
	public function getPaysContinent($idContinent, $orderby = 'idPays', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT idPays FROM pays WHERE idContinent='.$idContinent.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$pays = array();
			foreach ($res as $row) {
				$pays[] = $this->getPays($row['idPays']);
			}
		}
		else {
			$pays = false;
		}
		return $pays;
	}
}