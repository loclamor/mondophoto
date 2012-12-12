<?php
class GestionPaysSVG {
	
	/**
	 * @var GestionPaysSVG
	 */
	private static $instance = null;
	
	private $paysSVG = array();
	
	public static function getInstance() {
		if(is_null(self::$instance)) {
		self::$instance = new GestionPaysSVG();
		}
		return self::$instance;
	}
	
	/**
	 * @param integer $id
	 * @return PaysSVG
	 */
	public function getPaysSVG($id) {
		if(array_key_exists($id, $this->paysSVG)) {
			return $this->paysSVG[$id];
		}
		else {
			$paysSVG = new PaysSVG($id);
			$this->paysSVG[$id] = $paysSVG;
			return $paysSVG;
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $idPays
	 * @param unknown_type $orderby
	 * @param unknown_type $desc
	 * @return Array of LacPaysSVG
	 */
	public function getLacsPaysSVG($idPays, $orderby = 'id', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT id FROM pays_svg_lac WHERE idPays = '.$idPays.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$paysLacsSVG = array();
			foreach ($res as $row) {
				$paysLacsSVG[] = new LacPaysSVG($row['id']);
			}
		}
		else {
			$paysLacsSVG = false;
		}
		return $paysLacsSVG;
	}
	
	public function getPaysSVGPays($idPays, $orderby = 'id', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT id FROM pays_svg WHERE idPays = '.$idPays.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$paysSVG = array();
			foreach ($res as $row) {
				$paysSVG[] = $this->getPaysSVG($row['id']);
			}
		}
		else {
			$paysSVG = false;
		}
		return $paysSVG;
	}
}