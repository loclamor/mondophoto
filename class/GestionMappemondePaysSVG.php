<?php
class GestionMappemondePaysSVG {
	
	/**
	 * @var GestionMappemondePaysSVG
	 */
	private static $instance = null;
	
	private $paysSVG = array();
	
	public static function getInstance() {
		if(is_null(self::$instance)) {
		self::$instance = new GestionMappemondePaysSVG();
		}
		return self::$instance;
	}
	
	/**
	 * @param integer $id
	 * @return MappemondePaysSVG
	 */
	public function getPaysSVG($id) {
		if(array_key_exists($id, $this->paysSVG)) {
			return $this->paysSVG[$id];
		}
		else {
			$paysSVG = new MappemondePaysSVG($id);
			$this->paysSVG[$id] = $paysSVG;
			return $paysSVG;
		}
	}
	
	
	
	public function getMappemondePaysSVG($orderby = 'id', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT id FROM mappemonde_svg '.$orderby.$desc);
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
	
	public function countMappemondePaysSVG() {
		$res = SQL::getInstance()->exec('SELECT COUNT(*) as nbMappemondePays FROM mappemonde_svg');
		
		if($res) { //cas ou aucun retour requete (retour 0)
			$count = $res[0]['nbMappemondePays'];
		}
		else {
			$count = 0;
		}
		return $count;
	}
}