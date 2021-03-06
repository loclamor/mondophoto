<?php
class SQL {
	/**
	 * @var SQL
	 */
	private static $instance = null;
	private $last_sql_query = null;
	private $last_sql_error = null;
	private $nb_query = 0;
	private $nb_adm_query = 0;
	private $sql_connect = false;
	
	public static function getInstance() {
		if(is_null(self::$instance)) {
		self::$instance = new SQL();
		}
		return self::$instance;
	}
	
	public function BDDConnect() {
		$this->sql_connect = mysql_connect(MYSQL_SERVER,  MYSQL_USER, MYSQL_PWD);
		mysql_select_db(MYSQL_DB);
	}
	
	public function BDDUnconnect() {
		if($this->sql_connect) {
			mysql_close();
			$this->sql_connect = false;
		}
	}
	
	/**
	 * 
	 * @param string $requete
	 * @return array or false if no result
	 */
	public function exec($requete){
		$this->setLastQuery($requete);
		$this->nb_query++;
		
		if(!$this->sql_connect) {
			$this->BDDConnect();
		}
		
		$this->setLastError();
		//on fait la requete
		$rep = mysql_query($requete);
		//debug($rep);
		$this->setLastError(mysql_error());
		
		echo $this->last_sql_error;
		
		$row = false;
		if(strtoupper(substr($requete, 0, 6)) == 'SELECT') {
			if(!is_null($rep) && !empty($rep)) {
			/*	if(mysql_num_rows($rep) > 1) {
					while($res = mysql_fetch_assoc($rep)){
						$row[] = $res;
					}
				}
				else {
					$row = mysql_fetch_assoc($rep);
				}
				*/
				while($res = mysql_fetch_assoc($rep)){
					$row[] = $res;
				}
			}
		}
		elseif(strtoupper(substr($requete, 0, 6)) == 'INSERT') {
			$row = mysql_insert_id();
		}

		//on se déconnecte
	//	mysql_close();
		//on retourne le tableau de résultat
		return $row;
	}
	
	//execution de requete SQL reserve � l'administration
	public function exec2($requete){
		$this->setLastQuery($requete);
		$this->nb_adm_query++;
		
		if(!$this->sql_connect) {
			$this->BDDConnect();
		}
		
		$this->setLastError();
		//on fait la requete
		$rep = mysql_query($requete);
		//debug($rep);
		$this->setLastError(mysql_error());
		
		echo $this->last_sql_error;
		
		$row = false;
		if(strtoupper(substr($requete, 0, 6)) == 'SELECT') {
			if(!is_null($rep) && !empty($rep)) {
				if(mysql_num_rows($rep) > 1) {
					while($res = mysql_fetch_assoc($rep)){
						$row[] = $res;
					}
				}
				else {
					$row = mysql_fetch_assoc($rep);
				}
			}
		}
		elseif(strtoupper(substr($requete, 0, 6)) == 'INSERT') {
			$row = mysql_insert_id();
		}

		//on se déconnecte
	//	mysql_close();
		//on retourne le tableau de résultat
		return $row;
	}
	
	public function setLastError($err = ''){
		$this->last_sql_error = $err;
	}
	
	public function getLastError() {
		return $this->last_sql_error;
	}
	
	public function setLastQuery($q = null){
		$this->last_sql_query = $q;
	}
	
	public function getLastQuery() {
		return $this->last_sql_query;
	}
	
	public function getNbQuery() {
		return $this->nb_query;
	}
	
	public function getNbAdmQuery() {
		return $this->nb_adm_query;
	}
}