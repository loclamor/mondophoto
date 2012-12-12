<?php
class Url {
	
	public $params = array();
	public $file = '';
	public $rwfile = '';
	
	/**
	 * @param $clean bool url "propre"
	 * @param $file String [optional] le nom du fichier sur lequel pointera l'url (par défaut chaine vide, soit l'allias d'index)
	 */
	public function __construct($clean = false, $file = '') {
		if($file == '') {
			switch (SITE) {
				case 'site':
					$file = 'index.php';
					break;
				case 'adm':
					$file = 'administration.php';
					break;
				case 'vip':
					$file = 'vip.php';
					break;
			}
		}
		if(!$clean) {
			$tmp = explode('&',$_SERVER['QUERY_STRING']);
			foreach ($tmp as $var){
				$tmp2 = explode('=',$var);
				if(count($tmp2)>1){
					$this->params[$tmp2[0]] = $tmp2[1];
				}
				else {
					$this->params[$tmp2[0]] = '';
				}
			}
		}
		$this->file = $file;
	}
	
	/**
	 * @return string l' url
	 */
	public function getUrl() {
		//$this->rwfile = '';
		if(URL_REWRITING == 'true' and isset($this->params['page']) and SITE == 'site'){
			if(!isset($this->params['id'])) {
				$this->addParam('id', 0);
			}
			$this->file = $this->params['page'].'-'.$this->params['id'].'.html';
			if(NEW_REWRITE_MODE == 'on') {
				$file = 'index.php';
				switch ($this->params['page']) {
					case 'continent' :
						$continent = GestionContinents::getInstance()->getContinent($this->params['id']);
						if($continent and $continent instanceof Continent) {
							$file = noSpecialChar2($continent->getNom()).'.html';
						}
						break;
					case 'pays' :
						$pays = GestionPays::getInstance()->getPays($this->params['id']);
						if($pays and $pays instanceof Pays) {
							$continent = GestionContinents::getInstance()->getContinent($pays->getIdContinent());
							if($continent and $continent instanceof Continent) {
								$file = noSpecialChar2($continent->getNom()).'-'.noSpecialChar2($pays->getNom()).'.html';
							}
						}
						break;
					case 'ville' :
					case 'merveille' :
					case 'monument' :
						$ville = GestionLieux::getInstance()->getLieu($this->params['id']);
						if($ville and $ville instanceof Lieu) {
							$pays = GestionPays::getInstance()->getPays($ville->getIdPays());
							if($pays and $pays instanceof Pays) {
								$continent = GestionContinents::getInstance()->getContinent($pays->getIdContinent());
								if($continent and $continent instanceof Continent) {
									$file = noSpecialChar2($continent->getNom()).'-'.noSpecialChar2($pays->getNom()).'-'.noSpecialChar2($ville->getNom()).'.html';
								}
							}
						}
						break;
					case 'plansmetros' :
						$file = 'plansmetros.html';
						if($this->params['id'] != 0) {
							$pays = GestionPays::getInstance()->getPays($this->params['id']);
							if($pays and $pays instanceof Pays) {
								$file = 'plansmetros-'.noSpecialChar2($pays->getNom()).'.html';
							}
						}
						break;
					case 'recherche' :
						$file = 'recherche.html';
					break;
				}
				$this->file = $file;
			}
			
			
			$this->removeParam('page');
			$this->removeParam('id');
		}
		$url = array();
		$paramsKeyForbid = array('continent', 'pays', 'lieu');
		foreach ($this->params as $key => $value){
			if(!empty($key) && !is_null($key) && !in_array($key, $paramsKeyForbid)) {
				$url[] = $key.'='.$value;
			} 
		}
		$baseUrl = implode('&', $url);
		if(count($url)>0) {
			return $this->file.'?'.$baseUrl;
		}
		else {
			return $this->file;
		}
	}
	
	/**
	 * ajoute un paramètre à l'url
	 */
	public function addParam($key, $value) {
		$this->params[$key] = $value;
	}
	
	/**
	 * ajoute des paramètres à l'url
	 */
	public function addParams(array $params){
		foreach ($params as $key => $value) {
			$this->addParam($key, $value);
		}
	}
	
	/**
	 * enlève un paramètre à l'url
	 */
	public function removeParam($key) {
		unset($this->params[$key]);
	}
}