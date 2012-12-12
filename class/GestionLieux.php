<?php
class GestionLieux {
	
	/**
	 * @var GestionLieux
	 */
	private static $instance = null;
	
	private $lieux = array();
	
	public static function getInstance() {
		if(is_null(self::$instance)) {
		self::$instance = new GestionLieux();
		}
		return self::$instance;
	}
	
	/**
	 * @param integer $id
	 * @return Lieu
	 */
	public function getLieu($id) {
		if(array_key_exists($id, $this->lieux)) {
			return $this->lieux[$id];
		}
		else {
			$lieu = new Lieu($id);
			$this->lieux[$id] = $lieu;
			return $lieu;
		}
	}
	
	public function getLieux($orderby = 'idLieu', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT idLieu FROM lieu'.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$lieux = array();
			foreach ($res as $row) {
				$lieux[] = $this->getLieu($row['idLieu']);
			}
		}
		else {
			$lieux = false;
		}
		return $lieux;
	}
	
	public function getLieuxByType($type, $orderby = 'idLieu', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT idLieu FROM lieu WHERE type=\''.$type.'\''.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$lieux = array();
			foreach ($res as $row) {
				$lieux[] = $this->getLieu($row['idLieu']);
			}
		}
		else {
			$lieux = false;
		}
		return $lieux;
	}
	
	public function getPaysLieux($idPays,$orderby = 'idLieu', $desc = false){
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT idLieu FROM lieu WHERE idPays = '.((int)$idPays).$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$lieux = array();
			foreach ($res as $row) {
				$lieux[] = $this->getLieu($row['idLieu']);
			}
		}
		else {
			$lieux = false;
		}
		return $lieux;
	}
	
	public function getPaysLieuxByType($idPays,$type,$orderby = 'idLieu', $desc = false){
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT idLieu FROM lieu WHERE idPays = '.((int)$idPays).' and type=\''.$type.'\''.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$lieux = array();
			foreach ($res as $row) {
				$lieux[] = $this->getLieu($row['idLieu']);
			}
		}
		else {
			$lieux = false;
		}
		return $lieux;
	}
	
	public function getLieuxLies($idLieu,$orderby = 'idLieu', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		
		$res = SQL::getInstance()->exec('SELECT idLieu FROM lieu WHERE idLieu IN (SELECT idLieuFils FROM lie WHERE idLieuParent='.$idLieu.') '.$orderby.$desc);
		
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$lieux = array();
			foreach ($res as $row) {
				$lieux[] = $this->getLieu($row['idLieu']);
			}
		}
		else {
			$lieux = false;
		}
		return $lieux;
	}
	
	public function getLieuParent($idLieu) {
		$res = SQL::getInstance()->exec('SELECT idLieuParent FROM lie WHERE idLieuFils = '.$idLieu);
		
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$lieux = array();
			foreach ($res as $row) {
				$lieux[] = $this->getLieu($row['idLieuParent']);
			}
		}
		else {
			$lieux = false;
		}
		if(is_array($lieux)) {
			return $lieux[0];
		}
		return $lieux;
	}
	
	public function getLieuxAleatoire($nbLieux) {
				
		$res = SQL::getInstance()->exec('SELECT idLieu FROM lieu WHERE idLieu IN (SELECT idLieu FROM photo) ORDER BY RAND() LIMIT 0, '.$nbLieux);
		
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$lieux = array();
			foreach ($res as $row) {
				$lieux[] = $this->getLieu($row['idLieu']);
			}
		}
		else {
			$lieux = false;
		}
		return $lieux;
	}
	
	public function getLieuxLike($like, $orderby = 'nom', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		
		$res = SQL::getInstance()->exec('SELECT idLieu FROM lieu WHERE nom LIKE "%'.$like.'%"'.$orderby.$desc);
		
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$lieux = array();
			foreach ($res as $row) {
				$lieux[] = $this->getLieu($row['idLieu']);
			}
		}
		else {
			$lieux = false;
		}
		return $lieux;
	}
}