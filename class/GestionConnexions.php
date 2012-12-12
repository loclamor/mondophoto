<?php
class GestionConnexions {
	
	/**
	 * @var GestionConnexions
	 */
	private static $instance = null;
	
	private $connexions = array();
	
	public static function getInstance() {
		if(is_null(self::$instance)) {
		self::$instance = new GestionConnexions();
		}
		return self::$instance;
	}
	
	/**
	 * @param integer $id
	 * @return Connexion
	 */
	public function getConnexion($id) {
		if(array_key_exists($id, $this->connexions)) {
			return $this->connexions[$id];
		}
		else {
			$connexion = new Connexion($id);
			$this->connexions[$id] = $connexion;
			return $connexion;
		}
	}
	
	public function getConnexions($orderby = 'id', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT id FROM connexion'.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$connexions = array();
			foreach ($res as $row) {
				$connexions[] = $this->getConnexion($row['id']);
			}
		}
		else {
			$connexions = false;
		}
		return $connexions;
	}
	/**
	 * 
	 * Retourne la dernière date de connexion de l'ip $ip
	 * @param ip $ip
	 * @return Connexion
	 */
	public function getLastIpConnexions($ip) {
		$res = SQL::getInstance()->exec('SELECT id FROM connexion WHERE ip=\''.$ip.'\' ORDER BY moment ASC LIMIT 0, 1');
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$connexions = array();
			foreach ($res as $row) {
				$connexions[] = $this->getConnexion($row['id']);
			}
		}
		else {
			$connexions = false;
		}
		return $connexions[0];
	}
}