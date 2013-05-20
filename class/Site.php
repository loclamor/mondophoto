<?php

class Site {
	
	private $title = array();
	private $head = array();
	private $menu = array();
	private $filariane = array();
	private $content = array();
	private $foot = array();
	
	private $microtimeStart = 0;
	private $microtimeEnd = 0;
	
	public $page = null;
	public $id = null;
	public $couleur;
	
	public function __construct($admin = false) {
		$temps = microtime();
		$temps = explode(' ', $temps);
		$this->microtimeStart = $temps[1] + $temps[0];
		
		if(NEW_REWRITE_MODE == 'on') {
			if(isset($_GET['continent'])) {
				if(isset($_GET['pays'])){
					if(isset($_GET['lieu'])) {
						//il faut retrouver l'id du lieu
						$query = "SELECT idLieu FROM lieu WHERE nom LIKE '".noSpecialChar2($_GET['lieu'])."'";
						$res = SQL::getInstance()->exec($query);
						$_GET['id'] = $res[0]['idLieu'];
						//il faut retrouver le type de la page (ville, merveille, monument)
						$lieu = GestionLieux::getInstance()->getLieu($_GET['id']);
						if($lieu and $lieu instanceof Lieu)
							$_GET['page'] = $lieu->getType();
					}
					else {
						$_GET['page'] = 'pays';
						if($_GET['continent'] == 'plansmetros') {
							$_GET['page'] = 'plansmetros';
						}
						if($_GET['continent'] == 'recherche') {
							$_GET['page'] = 'recherche';
						}
						
						//il faut retrouver l'id du pays
						$query = "SELECT idPays FROM pays WHERE nom LIKE '".noSpecialChar2($_GET['pays'])."'";
						$res = SQL::getInstance()->exec($query);
						$_GET['id'] = $res[0]['idPays'];
						
					}
				}
				else {
					//il faut retrouver l'id du continent
					$query = "SELECT idContinent FROM continent WHERE nom LIKE '".noSpecialChar2($_GET['continent'])."'";
					$res = SQL::getInstance()->exec($query);
					$_GET['id'] = $res[0]['idContinent'];
					
					$_GET['page'] = 'continent';
					if($_GET['continent'] == 'plansmetros') {
						$_GET['page'] = 'plansmetros';
					}
					if($_GET['continent'] == 'recherche') {
						$_GET['page'] = 'recherche';
					}
				}
			}
			else {
				//il faudra surement faire ici une v�rif si on demande pas une page sp�ciale (ie. plansmetros) ou alors dans le else pays
				$_GET['page'] = 'monde';
			}
		}
		//debug($_GET,true);
		if(isset($_GET['continent']))
			unset($_GET['continent']);
		if(isset($_GET['pays']))
			unset($_GET['pays']);
		if(isset($_GET['ville']))
			unset($_GET['ville']);
		
		$this->couleur = '#D1D1FF'; //couleur par d�fault
		
		if(!$admin) {
			//on g�re l'enregistrement de la connexion
			$ip = $_SERVER["REMOTE_ADDR"];
			$lastConnexion = GestionConnexions::getInstance()->getLastIpConnexions($ip);
			if ($lastConnexion and ($lastConnexion->getMoment()+5*60) > time()) {
				$lastConnexion->setMoment(time());
			}
			else {
				$lastConnexion = new Connexion();
				$lastConnexion->setIp($ip);
				$lastConnexion->setMoment(time());
			}
			$lastConnexion->enregistrer();
			
			$this->addElement('title', SITE_NAME);
			//creation du menu
			$url = new Url(true);
			//$this->addElement('menu', "<li><a href='".$url->getUrl()."'>Accueil</a></li>");
			
			$listeContinents = '<ul id="menuListeContinents" class="dropdown-menu">';
			$continents = GestionContinents::getInstance()->getContinents('nom');
			if($continents) {
				$i = 0;
				$nbContinents = count($continents);
				foreach ($continents as $continent) {
					if($continent instanceof Continent) {
						
						$listeContinentPays = '<ul class="menuListePays dropdown-menu">';
						$listePays = GestionPays::getInstance()->getPaysContinent($continent->getId(),'nom');
						if($listePays) {
							$j = 0;
							$nbPays = count($listePays);
							foreach ($listePays as $pays) {
								if($pays instanceof Pays) {
									$urlPays = new Url();
									$urlPays->addParam('page', 'pays');
									$urlPays->addParam('id', $pays->getId());
									$class = '';
									if($j == 0){
										$class .= ' menuListePays_Top';
									}
									if($j == $nbPays - 1) {
										$class .= ' menuListePays_Bottom';
									}
									$listeContinentPays .= '<li class="'.$class.' dropdown"><a href="'.$urlPays->getUrl().'">'.$pays->getNom().'</a></li>';
								}
								$j++;
							}
						}
						else {
							$listeContinentPays .= '<li class="menuListePays_Top menuListePays_Bottom dropdown">&nbsp;Pas de pays</li>';
						}
						$listeContinentPays .= '</ul>';
						$class = '';
						if($i == 0){
							$class .= ' menuListeContinents_Top';
						}
						if($i == $nbContinents - 1) {
							$class .= ' menuListeContinents_Bottom';
						}
						$urlCont = new Url();
						$urlCont->addParam('page', 'continent');
						$urlCont->addParam('id', $continent->getId());
						$listeContinents .= '<li class="'.$class.' dropdown-submenu"><a href="'.$urlCont->getUrl().'">'.$continent->getNom().'</a>'.$listeContinentPays.'</li>';
					}
					$i++;
				}
			}
			$listeContinents .= '</ul>';
			$urlAccueil = new Url(true);
			$this->addElement('menu', "<li id='menuContinents' class=' btn-group'><div class='btn'><a href='".$urlAccueil->getUrl()."' >Monde </a></div><div class='btn dropdown-toggle btn-dropdown-continents' data-toggle='dropdown'><span class='caret'></span></div>".$listeContinents."</li>");
			$this->addElement('menu', "<li class='divider-vertical'></li>");
			$url->addParam('page', 'plansmetros');
			$this->addElement('menu', "<li ><div class='btn'><a href='".$url->getUrl()."'>Plans des Métros</a></div></li>");
			
			
			
			
			//on cr�e les �l�ments g�n�raux du footer
			$urlContact = new Url(true);
			$urlContact->addParam('page', 'contact');
			$this->addElement('foot', '<a class="contact" href="'.$urlContact->getUrl().'" target="_blank">Contact</a>');
			$urlVip = new Url(true,'vip.php');
			$urlVip->addParam('page', 'accueil');
			$this->addElement('foot', '<a href="./vip.php" target="_blank">Espace VIP</a>');
			$this->addElement('foot', "<a href='./CGU_MondoPhoto.pdf' target='_blank'>CGU</a>");
			$this->addElement('foot', '&copy; 2012 MondoPhoto');
			$this->addElement('foot', '<a href="http://www.d-maps.com" target="_blank">Cartes &copy; Daniel Dalet / d-maps.com</a>');
			
			//on v�rifie que on a bien une page de demand�e
			if(isset($_GET['page']) and !empty($_GET['page'])){
				$this->page = $_GET['page'];
			}
			else {
				$this->page = 'monde';
			}
			//on v�rifie ensuite que on a bien un ID si on affiche pas la liste des metros
			$noId = array('contact', 'plansmetros', 'recherche'); //liste des pages sans ID
			if(!in_array($this->page, $noId) or isset($_GET['id'])) {
				if(isset($_GET['id']) and $_GET['id'] != ''){
					$this->id = $_GET['id'];
					
				}
				else {
					//si pas de ID en param, retour page monde
					$this->page = 'monde';
				}
			}

			$urlMonde = new Url(true);
			$this->addElement('filariane', '<a href="'.$urlMonde->getUrl().'">Monde</a>');
			
			switch ($this->page) {
				case 'monde' :
					$this->getMonde();
					break;
				case 'continent' :
					$this->getContinent($this->id);
					break;
				case 'continentSVG' :
					$this->getContinentSVG($this->id);
					break;
				case 'pays':
					$this->getPays($this->id);
					break;
				case 'ville':
					$this->getVille($this->id);
					break;
				case 'monument':
				case 'merveille':
					$this->getMerveille($this->id);
					break;
				case 'plansmetros':
					$this->getListePlansMetros();
					break;
				case 'contact':
					$this->getFormulaireContact();
					break;
				case 'recherche':
					$this->getRecherche($_POST['query']);
					break;
				default:
					//par d�faut, on affiche l'accueil
					$this->getMonde();
			}
			
			$lieux = GestionLieux::getInstance()->getLieuxAleatoire(1);
			$photo = GestionPhotos::getInstance()->getLieuPhoto($lieux[0]->getId());
			$tailles = getimagesize($photo->getUrlPhoto());
			if($tailles[1] > $tailles[0]) {
				//H > L
				$tailleOK = false;
				while (!$tailleOK) {
					$lieu = GestionLieux::getInstance()->getLieuxAleatoire(1);
					$photo = GestionPhotos::getInstance()->getLieuPhoto($lieu[0]->getId());
					$tailles = getimagesize($photo->getUrlPhoto());
			//		echo 'plop!&nbsp;';
					if($tailles[1] < $tailles[0]) {
						$tailleOK = true;
					}
				}
			}
			
			$this->getFormulaireContact(false);
			
			$this->addElement('head', '<div id="logo" style="float:right; background-image: url('.$photo->getUrlPhoto().'); padding: 0px; background-position: -200px -200px;">');
				$this->addElement('head', '<img src="style/logo_'.strtoupper(substr($this->couleur,1)).'.png" height="75px" />');
				
			$this->addElement('head', '</div>');
//			$this->addElement('head', '<div class="fb-like" style="position: absolute; top: 63px; right: 8.25%;" data-href="http://www.mondophoto.fr" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>');
			$nbAdmQuery = SQL::getInstance()->getNbAdmQuery();
			$nbQuery = SQL::getInstance()->getNbQuery();
			
		//	$this->addElement('foot', $nbAdmQuery.'/'.$nbQuery.' requ�tes');
			
			$temps = microtime();
			$temps = explode(' ', $temps);
			$this->microtimeEnd = $temps[1] + $temps[0];
			
			$this->addElement('foot', 'page générée en '.round(($this->microtimeEnd - $this->microtimeStart),3).' secondes');
			
		}
		
	}
	
	public function getTitle($impl = ' - ') {
		return implode($impl,$this->title);
	}
	
	public function getHead($impl = ' ') {
		return implode($impl,$this->head);
	}
	
	public function getMenu($impl = '') {
		return implode($impl, $this->menu);
	}
	
	public function getFilAriane($impl = ' > ') {
		return implode($impl,$this->filariane);
	}
	
	public function getContent($impl = '') {
		return implode($impl,$this->content);
	}
	
	public function getFoot($impl = ' - ') {
		return implode($impl,$this->foot);
	}
	
	/**
	 * @param string $var (title, head, content, foot, menu)
	 * @param string $content
	 * @param boolean $end
	 */
	public function addElement($var,$content,$end=true) {
		if($end) {
			array_push($this->$var, $content);
		}
		else {
			array_unshift($this->$var,$content);
		}
	}
	
	public function getMonde(){
		$this->couleur = '#D1D1FF';
	
		
		$this->addElement('content', '<div id="mapmonde" class="span12">');
			/*
			$this->addElement('content', '<img src="images/carte-monde.png" usemap="#cartemonde" />');
			$this->addElement('content', '<map name="cartemonde" >');
				$url = new Url();
				$url->addParam('page', 'continent');
				$url->addParam('id', '1');
				$this->addElement('content', '<area shape="poly" coords="351,44,348,46,427,5,469,4,551,14,553,37,543,89,535,88,532,91,528,92,527,97,538,106,537,120,512,114,486,115,474,125,480,139,502,138,500,145,466,143,432,134,404,136,373,137,349,47" href="'.$url->getUrl().'" alt="Europe">');
				$url = new Url();
				$url->addParam('page', 'continent');
				$url->addParam('id', '2');
				$this->addElement('content', '<area shape="poly" coords="806,140,783,159,759,191,736,227,714,257,692,234,683,229,636,243,603,195,583,191,536,221,525,207,496,150,502,138,489,140,472,125,487,118,512,120,538,120,534,105,526,98,537,89,554,37,547,32,553,24,573,14,580,2,607,4,838,33,847,56,813,100" href="'.$url->getUrl().'" alt="Asie">');
				$url = new Url();
				$url->addParam('page', 'continent');
				$url->addParam('id', '3');
				$this->addElement('content', '<area shape="poly" coords="335,151,381,138,429,133,500,155,531,217,558,218,560,311,538,345,465,376,444,370,428,314,416,251,364,244,336,214,321,205,323,198,332,160" href="'.$url->getUrl().'" alt="Afrique">');
				$url = new Url();
				$url->addParam('page', 'continent');
				$url->addParam('id', '4');
				$this->addElement('content', '<area shape="poly" coords="762,190,748,198,737,235,716,258,687,230,679,233,707,285,767,301,729,324,725,385,780,377,796,387,800,415,867,421,912,386,910,308,857,259,802,250,786,213" href="'.$url->getUrl().'" alt="Oceanie">');
				$url = new Url();
				$url->addParam('page', 'continent');
				$url->addParam('id', '5');
				$this->addElement('content', '<area shape="poly" coords="117,216,146,197,145,180,164,172,165,167,267,98,390,2,209,1,181,21,147,19,130,31,46,33,21,44,1,75,6,81,52,67,62,77,57,107,50,139,62,175,95,209" href="'.$url->getUrl().'" alt="Amerique du Nord">');
				$url = new Url();
				$url->addParam('page', 'continent');
				$url->addParam('id', '6');
				$this->addElement('content', '<area shape="poly" coords="123,218,143,199,146,181,166,177,167,163,181,167,186,178,217,194,232,220,193,208,172,218,161,239,139,233" href="'.$url->getUrl().'" alt="Amerique Centrale">');
				$url = new Url();
				$url->addParam('page', 'continent');
				$url->addParam('id', '7');
				$this->addElement('content', '<area shape="poly" coords="158,240,171,221,184,211,222,221,257,253,304,275,249,387,238,417,257,424,254,440,194,419,179,326,142,274,148,257" href="'.$url->getUrl().'" alt="Amerique du Sud">');
			$this->addElement('content', '</map>');
			*/
		
			$paysMappemonde = GestionMappemondePaysSVG::getInstance()->getMappemondePaysSVG();
			$height = "100%";
			$width = "100%";
			$viewbox = "10 10 297 170";
			$this->addElement('content', '<svg id="svg_mappemonde" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="'.$viewbox.'" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" height="'.$height.'" width="'.$width.'" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">');
				$this->addElement('content', "<g>");
				foreach ($paysMappemonde as $paysM){
					if($paysM instanceof MappemondePaysSVG){
						$pays = ($paysM->getIdPays()!=null)?(GestionPays::getInstance()->getPays( $paysM->getIdPays())):null;
						if( $pays instanceof Pays ){
							$fillColor = "white";
							$url = new Url();
							$url->addParam('page', 'pays');
							$url->addParam('id', $pays->getId());
							
							$tooltip = "<span class='pays' style='color:".GestionContinents::getInstance()->getContinent( $pays->getIdContinent() )->getCouleur().";'>";
							$tooltip .= $pays->getNom();
							$tooltip .= '</span>';
							
							$this->addElement('content','<a xlink:href="'.$url->getUrl().'">');
								$this->addElement('content', '<path class="svg_mappemonde_pays" fill="'.$fillColor.'" stroke="black" stroke-width="0.1px" class="paysMappemonde" d="'.$paysM->getCoordonnees().'" name="'.$tooltip.'" data-id-pays="'.$pays->getId().'" />');
							$this->addElement('content','</a>');
						}
						else {
							$fillColor = GestionContinents::getInstance()->getContinent(1)->getCouleur();
							$this->addElement('content', '<path class="svg_mappemonde_pays" fill="'.$fillColor.'" stroke="black" stroke-width="0.1px" class="paysMappemonde" d="'.$paysM->getCoordonnees().'" name=""/>');
						}
					}
				}
				$this->addElement('content', "</g>");
			$this->addElement('content', "</svg>");
		
		$this->addElement('content', "</div>");
		// les photos aleatoires
		$this->addElement('content', '<ul id="alealieux" class="thumbnails">');
			$lieux = GestionLieux::getInstance()->getLieuxAleatoire(4);
			if($lieux) {
				
				foreach ($lieux as $lieu) {
					if($lieu instanceof Lieu) {
						$photo = GestionPhotos::getInstance()->getLieuPhoto($lieu->getId());
						
						$tailles = getimagesize($photo->getUrlPhoto());
						if($tailles[1] > $tailles[0]) {
							//H > L
							$tailleOK = false;
							while (!$tailleOK) {
								$lieu = GestionLieux::getInstance()->getLieuxAleatoire(1);
								$lieu = $lieu[0];
								$photo = GestionPhotos::getInstance()->getLieuPhoto($lieu->getId());
								$tailles = getimagesize($photo->getUrlPhoto());
						//		echo 'plop!&nbsp;';
								if($tailles[1] < $tailles[0]) {
									$tailleOK = true;
								}
							}
						}
						$paysLieu = GestionPays::getInstance()->getPays($lieu->getIdPays());
						$continentPaysLieu = GestionContinents::getInstance()->getContinent($paysLieu->getIdContinent());
						
						$url = new Url(true);
						$url->addParam('page', 'merveille');
						$url->addParam('id', $lieu->getId());
						
						$villeLieu = GestionLieux::getInstance()->getLieuParent($lieu->getId());
						$ville = '';
						if($villeLieu) {
							if($villeLieu instanceof Lieu) {
								$ville = " - <span style='color:".$continentPaysLieu->getCouleurOcean().";'>".$villeLieu->getNom()."</span>";
								$url->addParam('page', 'ville');
								$url->addParam('id', $villeLieu->getId());
							}
						}
						
						$tooltip = "<span class='pays' style='color:".$continentPaysLieu->getCouleur().";'>";
						$tooltip .= $paysLieu->getNom();
						$tooltip .= $ville.'</span>'.' - '.$lieu->getPronom().' '.$lieu->getNom();
						
						$this->addElement('content', '<li class="span3"><div class="conteneurPhotoPub thumbnail"><a href="'.$url->getUrl().'">');
							$this->addElement('content', '<img class="photoPub" src="'.$photo->getUrlPhotoMiniature(150,'H').'" height="150px" tooltip="'.$tooltip.'" alt="'.$lieu->getNom().'" />');
						$this->addElement('content', '</a></div></li>');
					}
					else {
						$lieu = GestionLieux::getInstance()->getLieuxAleatoire(1);
						$lieux[] = $lieu[0];
					}
				}
				
				$this->addElement('content','<script>'
				. '$(".photoPub").mouseenter(function(){'
				. '    $("#tooltip").addClass("tooltipShow");'
				. '    $("#tooltip").html($(this).attr("tooltip"));'
				. '});'
				. '$(".photoPub").mouseleave(function(){'
				. '    $("#tooltip").removeClass("tooltipShow");'
				. '});'
				. '$(".photoPub").mousemove(function(event) {'
				. '    $("#tooltip").css("left",event.pageX + 15);'
				. '    $("#tooltip").css("top",event.pageY + 25);'
				. '});'
				. '</script>');
				
				
			}
		$this->addElement('content', "</div>");
		$urlContact = new Url(true);
		$urlContact->addParam('page', 'contact');
		$lienContact = '<a class="contact" href="'.$urlContact->getUrl().'" target="_blank">';
		
		$urlVip = new Url();
		$lienVip = "";
		
		$texteAccueil = "<p>Ce site a pour but de réunir des photographies du monde entier sur une carte. Cela vous permettra, nous  l'espérons,  de voir en un clic ce qu'il y a à visiter dans un pays, dans une ville ou dans une région. Et pour vous aider dans vos futurs voyages, ce site réunit tous les métros des pays présents sur le site.</p>"
			. "<p class='accueil-nouveau'><a href='./vip.php' target='_blank'>Devenez membre VIP</a> et contribuez à enrichir MondoPhoto !"
			. "<p>N'hésitez pas à faire des remarques sur le site en cliquant sur le lien ".$lienContact."contact</a> et si vous le souhaitez vous pouvez devenir membre VIP  et ajouter vos photos sur le site tout en respectant les <a href='./CGU_MondoPhoto.pdf' target='_blank'>CGU</a>.</p>"
			. "<p>Vous pouvez aussi nous ".$lienContact."contacter</a> pour demander de plus amples renseignements sur une ville, un monument, un plan de métro... et nous vous aiderons du mieux possible.</p>";
		$this->addElement('content', '<div id="textAccueil">'.$texteAccueil.'</div>');
	}
	
	public function getContinent_Old($idCont){
		$continent = GestionContinents::getInstance()->getContinent($idCont);
		$this->couleur = $continent->getCouleur();
		$this->addElement('title', $continent->getNom());
		$this->addElement('content', "<img id='contCarte' src='".$continent->getUrlCarte()."' usemap='#mapCont'/>");
		
		$maps = GestionMaps::getInstance()->getContinentMaps($continent->getId());
		if($maps) {
			$i = 0;
			$this->addElement('content', '<map name="mapCont" id="mapCont" >');
			foreach ($maps as $map){
				if($map instanceof Map){
					$url = new Url();
					$url->addParam('page', 'pays');
					$url->addParam('id', $map->getIdPays());
					$this->addElement('content', '<area   shape="poly" coords="'.$map->getCoordonnees().'" href="'.$url->getUrl().'" alt="'.$map->getIdPays().'">');
					
					
					$i++;
				}
			}
			$this->addElement('content', '</map>');
			
		}
		$this->addElement('content', "<br/><span id='copyright'><a href='".$continent->getUrlCarteFrom()."'>&copy; Daniel Dalet / d-maps.com</a></span>");
		$this->addElement('content','<input type="hidden" id="current" value="0"/>');
	}
	
	public function getContinent($idCont){
		$continent = GestionContinents::getInstance()->getContinent($idCont);
		$this->couleur = $continent->getCouleur();
		$this->addElement('title', $continent->getNom());
		
		$urlContinent = new Url(true);
		$urlContinent->addParams(array('page' => 'continent', 'id' => $continent->getId()));
		$this->addElement('filariane', '<a href="'.$urlContinent->getUrl().'">'.$continent->getNom().'</a>');
		
		$pays_svg = GestionContinentsSVG::getInstance()->getPaysContinentSVG($continent->getId());
		
		//construction de l'image SVG
		if($pays_svg) {
			
			if($continent->getSensCarte() == 0) {
				$viewBox = '0 0 297 210';
				$height = '700';
			}
			else {
				$viewBox = '0 0 210 297';
				$height = '1400';
			}
		
			$this->addElement('content','<div class="conteneurSvg" style="max-height: 1250px; background-color: '.$continent->getCouleurOcean().';"><svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" '
			. 'width="990px" height="'.$height.'px" style="shape-rendering:geometricPrecision; '
			. 'text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; '
			. 'clip-rule:evenodd" viewBox="'.$viewBox.'" xmlns:xlink="http://www.w3.org/1999/xlink" '
			. ' >');
			
			//transform="translate(-centreX*(n-1), -centreY*(n-1)) scale(n)"
			$n = 1.2;
			$centreX = 400/2;
			$centreY = 637/2;
			
			$transX = 0;//-$centreX * ($n - 1);
			$transY = -$centreY * ($n - 1);
			
			$this->addElement('content','<g >');
		//	$this->addElement('content','<rect fill="blue" x="10" y="10.1486" width="228.023" height="189.851"/>');
			$pays = GestionContinents::getInstance()->getIdPaysContinent($continent->getId());
			if(!$pays){
				$pays = array();
			}
			
			foreach ($pays_svg as $ps) {
				if($ps instanceof ContinentSVG) {
					if($ps->getIdPays()) {
						$url = new Url();
						$url->addParam('page', 'pays');
						$url->addParam('id', $ps->getIdPays());
						
						$fill = 'white';
						if(!in_array($ps->getIdPays(),$pays)){
							$fill = eclaircir($continent->getCouleur(),27);
						}
						
						$cur_pays = GestionPays::getInstance()->getPays($ps->getIdPays());
						$cur_continent = GestionContinents::getInstance()->getContinent($cur_pays->getIdContinent());
						$name = "<span class='pays' style='color:".$cur_continent->getCouleur().";'>";
						$name .= $cur_pays->getNom();
						$name .= '</span>';
						
					//	$name = json_encode($name);
						
						$this->addElement('content','<a xlink:href="'.$url->getUrl().'"><path fill="'.$fill.'" stroke="black" name="'.$name.'" alt="'.$ps->getIdPays().'" stroke-width="0.1" d="'.$ps->getCoordonnees().'" value="'.$ps->getId().'" /></a>');
					}
					else {
						$this->addElement('content','<path fill="'.$continent->getCouleur().'" stroke="black" name="" alt="'.$ps->getIdPays().'" stroke-width="0.1" d="'.$ps->getCoordonnees().'" value="'.$ps->getId().'"/>');
					}
				}
			}
			
			$this->addElement('content','</g>');
			$this->addElement('content',"</svg>"
			. "</div>");
		}
		
		$this->addElement('content', "<span id='copyright'><a href='".$continent->getUrlCarteFrom()."'>&copy; Daniel Dalet / d-maps.com</a></span>");
		$this->addElement('content','<input type="hidden" id="current" value="0"/>');
	}
	
	public function getPays($idPays){
		$pays = GestionPays::getInstance()->getPays($idPays);
		$continent = GestionContinents::getInstance()->getContinent($pays->getIdContinent());
		$this->couleur = $continent->getCouleur();
		$this->addElement('title', $continent->getNom());
		$this->addElement('title', $pays->getNom());
		
		$urlContinent = new Url(true);
		$urlContinent->addParams(array('page' => 'continent', 'id' => $continent->getId()));
		$this->addElement('filariane', '<a href="'.$urlContinent->getUrl().'">'.$continent->getNom().'</a>');
		$urlPays = new Url(true);
		$urlPays->addParams(array('page' => 'pays', 'id' => $pays->getId()));
		$this->addElement('filariane', '<a href="'.$urlPays->getUrl().'">'.$pays->getNom().'</a>');
		
		
		$paysSVG = GestionPaysSVG::getInstance()->getPaysSVGPays($pays->getId());
		if($paysSVG){
			$this->addElement('content','<div class="conteneurSvg span12" style="background-color: '.$continent->getCouleurOcean().';">');
		//	$this->addElement('content','<div id="conteneurOptionAffichageSvg" class="row-fluid">');
		//		$this->addElement('content','<span class="span12">Afficher :</span><br/>');
				$this->addElement('content','<div id="optionAffichageSvg" class="" data-toggle="buttons-checkbox">Afficher :');
				
				
				$this->addElement('content','<button id="affNomGdVilles" type="button" class="btn active optionAffichagePays">Noms grandes villes</button>');
				$this->addElement('content','<button id="affGdVilles" type="button" class="btn active optionAffichagePays">Points grandes villes<//button>');
				$this->addElement('content','<button id="affAutresVilles" type="button" class="btn active optionAffichagePays">Points autres villes</button>');
				$this->addElement('content','<button id="affMerveilles" type="button" class="btn active optionAffichagePays">Points merveilles</button>');
				$this->addElement('content','<button id="affMonuments" type="button" class="btn active optionAffichagePays">Points monuments</button>');
				
				
				/*
				
				$this->addElement('content','<span class="span3"><label for="affNomGdVilles" class="checkbox"><input type="checkbox" id="affNomGdVilles" checked="checked">&nbsp;les noms des grandes villes</label></span>');
				$this->addElement('content','<span class="span2"><label for="affGdVilles" class="checkbox"><input type="checkbox" id="affGdVilles" checked="checked">&nbsp;les grandes villes</label></span>');
				$this->addElement('content','<span class="span2"><label for="affAutresVilles" class="checkbox"><input type="checkbox" id="affAutresVilles" checked="checked">&nbsp;les autres villes</label></span>');
				$this->addElement('content','<span class="span2"><label for="affMerveilles" class="checkbox"><input type="checkbox" id="affMerveilles" checked="checked">&nbsp;les merveilles</label></span>');
				$this->addElement('content','<span class="span2"><label for="affMonuments" class="checkbox"><input type="checkbox" id="affMonuments" checked="checked">&nbsp;les monuments</label></span>');
				*/
				
				$this->addElement('content','</div>');
				$script = '<script>'
					. '$(document).ready(function(){'
					
					. '	$("#affNomGdVilles").click(function(){'
					. '		if($("#affNomGdVilles").hasClass("active")){'
					. '			$("text.ville_1").css("visibility","hidden");'
					. '			$("text.ville_2").css("visibility","hidden");'
					. '		}'
					. '		else{'
					. '			if($("#affGdVilles").hasClass("active")){'
					. '				$("text.ville_1").css("visibility","visible");'
					. '				$("text.ville_2").css("visibility","visible");'
					. '			}'
					. '		}'
					. '	});'
					. '	$("#affGdVilles").click(function(){'
					. '		if($("#affGdVilles").hasClass("active")){'
					. '			$(".ville_1").css("visibility","hidden");'
					. '			$(".ville_2").css("visibility","hidden");'
					. '		}'
					. '		else{'
					. '			$("circle.ville_1").css("visibility","visible");'
					. '			$("circle.ville_2").css("visibility","visible");'
					. '			if($("#affNomGdVilles").hasClass("active")){'
					. '				$("text.ville_1").css("visibility","visible");'
					. '				$("text.ville_2").css("visibility","visible");'
					. '			}'
					. '		}'
					. '	});'
					. '});'
					. '	$("#affAutresVilles").click(function(){'
					. '		if($("#affAutresVilles").hasClass("active")){'
					. '			$(".ville_3").css("visibility","hidden");'
					. '			$(".ville_4").css("visibility","hidden");'
					. '			$(".ville_5").css("visibility","hidden");'
					. '		}'
					. '		else{'
					. '			$(".ville_3").css("visibility","visible");'
					. '			$(".ville_4").css("visibility","visible");'
					. '			$(".ville_5").css("visibility","visible");'
					. '		}'
					. '	});'
					. '	$("#affMerveilles").click(function(){'
					. '		if($("#affMerveilles").hasClass("active")){'
					. '			$(".merveille").css("visibility","hidden");'
					. '		}'
					. '		else{'
					. '			$(".merveille").css("visibility","visible");'
					. '		}'
					. '	});'
					. '	$("#affMonuments").click(function(){'
					. '		if($("#affMonuments").hasClass("active")){'
					. '			$(".monument").css("visibility","hidden");'
					. '		}'
					. '		else{'
					. '			$(".monument").css("visibility","visible");'
					. '		}'
					. '	});'
					. '</script>';
				
				$this->addElement('content', $script);
				
		//	$this->addElement('content','</div>');
			
			
			$this->getCartePaysSVG($pays);
			$this->addElement('content','</div>');
		}
		else {
			// si pas de SVG on affiche l'existant
			$this->addElement('content', "<img id='paysCarte' src='".$pays->getUrlCarte()."' usemap='#mapPays'/>");
			
			//affichage des Lieux
			$this->addElement('content', '<map name="mapPays" id="mapPays" >');
			$lieux = GestionLieux::getInstance()->getPaysLieux($pays->getId());
			if($lieux){
				foreach ($lieux as $lieu){
					if($lieu instanceof Lieu){
						if($lieu->getCoordonnees() || ($lieu->getCx() && $lieu->getCy())) {
							$class = '';
							if($lieu->getType() == 'ville'){
								$url = new Url();
								$url->addParam('page', $lieu->getType());
								$url->addParam('id', $lieu->getId());
								$chaineUrl = 'href="'.$url->getUrl().'"';
								
								if(!$lieu->hasLieuxLies()) {
									$chaineUrl = '';
									$class = ' villeOff';
								}
								$this->addElement('content', '<area   shape="circle" coords="'.$lieu->getCoordonnees().',5" '.$chaineUrl.' alt="'.$lieu->getId().'" class="'.$class.'" >');
							}
							else {
								$this->addElement('content', '<area   shape="circle" coords="'.$lieu->getCoordonnees().',5"  alt="'.$lieu->getId().'" class="'.$lieu->getType().$class.'" >');
							}
						}
					}
				}
			}
			
			$this->addElement('content', '</map>');
			
		}
		
		$this->addElement('content', "<br/><span id='copyright'><a href='".$pays->getUrlCarteFrom()."'>&copy; Daniel Dalet / d-maps.com</a></span>");
//		$this->addElement('content','<input type="hidden" id="current" value="0"/>');
	}
	
	public function getCartePaysSVG(Pays $pays, array $centerOn = null, $addXlink = true, $addCityName = true) {
		$paysSVG = GestionPaysSVG::getInstance()->getPaysSVGPays($pays->getId());
		$continent = GestionContinents::getInstance()->getContinent($pays->getIdContinent());
		//ici on affiche l'image SVG
		//ratio=Math.min(width_svg/width_viewBox,height_svg/height_viewBox);
		if($pays->getSensCarte() == 0) {
			// x x L H
			$viewBox = '0 0 297 210';
			$height = '700';
			$ratio = min(array(990/297,700/210));
		}
		else {
			$viewBox = '0 0 210 297';
			$height = '1250';
			$ratio = min(array(990/210,1400/297));
		}
		
		$svgStyle = '';
		if(!is_null($centerOn)) {
			$left = $centerOn[0]*$ratio - 125;
			$top = $centerOn[1]*$ratio - 125;
			$svgStyle = 'position: relative; left: -'.$left.'px; top: -'.$top.'px;';
		}
		
		$textOnSvg = '';
		
		$this->addElement('content','<svg id="svgMap" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" '
		. 'width="990px" height="'.$height.'px" style="shape-rendering:geometricPrecision; '
		. 'text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; '
		. 'clip-rule:evenodd; '.$svgStyle.'" viewBox="'.$viewBox.'" xmlns:xlink="http://www.w3.org/1999/xlink" '
		. ' >');
		$this->addElement('content','<g>');
		foreach ($paysSVG as $ps) {
			if($ps instanceof PaysSVG) {
				if($ps->getIdPaysStranger() == $pays->getId()) {
					//si c'est le pays courant
					$fill = 'white';
					$this->addElement('content','<path fill="'.$fill.'" stroke="black" name="" alt="" stroke-width="0.1" d="'.$ps->getCoordonnees().'" value="'.$ps->getId().'"/>');
				}
				else {
					if(!is_null($ps->getIdPaysStranger()) and $ps->getIdPaysStranger() != '') {
						$fill = eclaircir($continent->getCouleur(),27);
						$cur_pays = GestionPays::getInstance()->getPays($ps->getIdPaysStranger());
						$cur_continent = GestionContinents::getInstance()->getContinent($cur_pays->getIdContinent());
						$name = "<span class='pays' style='color:".$cur_continent->getCouleur().";'>";
						$name .= $cur_pays->getNom();
						$name .= '</span>';
						
						if($addXlink) {
							$url = new Url();
							$url->addParam('page', 'pays');
							$url->addParam('id', $ps->getIdPaysStranger());
							$this->addElement('content','<a xlink:href="'.$url->getUrl().'">');
						}
						
						$this->addElement('content','<path fill="'.$fill.'" stroke="black" name="'.$name.'" alt="'.$ps->getIdPaysStranger().'" stroke-width="0.1" d="'.$ps->getCoordonnees().'" value="'.$ps->getId().'" />');
						
						if($addXlink) {
							$this->addElement('content','</a>');
						}
					}
					else {
						$fill = $continent->getCouleur();
						$this->addElement('content','<path fill="'.$fill.'" stroke="black" name="" alt="" stroke-width="0.1" d="'.$ps->getCoordonnees().'" value="'.$ps->getId().'"/>');
					}
				}
			}
		}
		//ensuite on affiche les lacs et fleuves
		$lacs = GestionPaysSVG::getInstance()->getLacsPaysSVG($pays->getId());
		if($lacs) {
			$i = 0;
			foreach ($lacs as $lac) {
				if($lac instanceof LacPaysSVG) {
					if($i + 1 == count($lacs)){
						$fill = $continent->getCouleurOcean();
						$stroke = 'black';
					}
					else {
						$fill = 'none';
						$stroke = $continent->getCouleurOcean();
					}
					$this->addElement('content','<path fill="'.$fill.'" stroke="'.$stroke.'" stroke-width="0.1" d="'.$lac->getCoordonnees().'" />');
				}
				$i++;
			}
		}
		//enfin on affiche les villes et lieux
		$lieux = GestionLieux::getInstance()->getPaysLieux($pays->getId());
			if($lieux){
				foreach ($lieux as $lieu){
					if($lieu instanceof Lieu){
						if($lieu->getCx() and $lieu->getCy()) {
							$class = '';
							if($lieu->getType() == 'ville'){
								$url = new Url();
								$url->addParam('page', $lieu->getType());
								$url->addParam('id', $lieu->getId());
								$chaineUrl = '';
								$EndChaineUrl = '';
								if($addXlink) {
									$chaineUrl = '<a xlink:href="'.$url->getUrl().'">';
									$EndChaineUrl = '</a>';
								}
								$fill = 'black';
								if(!$lieu->hasLieuxLies()) {
									$chaineUrl = '';
									$EndChaineUrl = '';
									$class = ' villeOff';
									$fill = '#CCCCCC';
								}
								
								if($lieu->getCategorie() < 3){
									$dec = 0;
									
									$nomVille = $lieu->getNom();
									//cas particulier pour clermont Fd
									if($lieu->getId() == 22) {
										$nomVille = 'Clermont-Fd.';
									}
									$textOnSvg .= '<text class="ville_'.$lieu->getCategorie().'" x="'.($lieu->getCx()+1).'" y="'.($lieu->getCy()+$dec).'" style="font-size:3.5px;">'.$nomVille.'</text>';
								}
								
								$name = "<span class='pays' style='color:".$continent->getCouleur().";'>";
								$name .= $lieu->getPronom().' '.$lieu->getNom();
								$name .= '</span>';
								if($lieu->getCx() == $centerOn[0] and $lieu->getCy() == $centerOn[1]){
									$this->addElement('content', '<circle cx="'.$lieu->getCx().'" cy="'.$lieu->getCy().'" r="1" stroke="red" fill="none" stroke-width="0.3px" />');
								}
								//$this->addElement('content', '<area   shape="circle" coords="'.$lieu->getCoordonnees().',5" '.$chaineUrl.' alt="'.$lieu->getId().'" class="'.$class.'" >');
								$this->addElement('content', $chaineUrl.'<circle class="ville ville_'.$lieu->getCategorie().'" name="'.$name.'" cx="'.$lieu->getCx().'" cy="'.$lieu->getCy().'" r="0.55" stroke="black" fill="'.$fill.'" stroke-width="0.1px" alt="'.$lieu->getId().'"/>'.$EndChaineUrl);
								
							}
							else {
							$url = new Url();
								$url->addParam('page', $lieu->getType());
								$url->addParam('id', $lieu->getId());
								$chaineUrl = '';
								$EndChaineUrl = '';
								if($addXlink) {
									$chaineUrl = '<a xlink:href="'.$url->getUrl().'" data-toggle="modal" href="ajax.php?page=getNewVisioneuse&idLieu='.$lieu->getId().'" data-target="#visio_'.$lieu->getId().'">';
									$EndChaineUrl = '</a>';
								}
								//$this->addElement('content', '<area   shape="circle" coords="'.$lieu->getCoordonnees().',5"  alt="'.$lieu->getId().'" class="'.$lieu->getType().$class.'" >');
								$this->addElement('content',  $chaineUrl.'<circle class="'.$lieu->getType().'" name="'.$lieu->getPronom().' '.$lieu->getNom().'" cx="'.$lieu->getCx().'" cy="'.$lieu->getCy().'" r="0.55" stroke="black"  stroke-width="0.1px" alt="'.$lieu->getId().'"/>'.$EndChaineUrl);
								$lieuxVisio[] = $lieu->getId();
							}
						}
					}
				}
			}
		if($addCityName){
			$this->addElement('content', $textOnSvg);
		}
		$this->addElement('content','</g>');
		$this->addElement('content','</svg>');
		
		//on ajoute une fen�tre modale de visionnage pour chaque lieu
		if($addXlink and isset($lieuxVisio)){
			foreach ($lieuxVisio as $idLieu){
				$lieu = GestionLieux::getInstance()->getLieu($idLieu);
				if($lieu and $lieu instanceof Lieu){
					$this->addElement('content', '<div class="modal hide fade" id="visio_'.$lieu->getId().'">');
  						$this->addElement('content', '<div class="modal-header"> <a class="close" data-dismiss="modal">x</a>');
    						$this->addElement('content', '<h3>'.$lieu->getPronom().' '.firstchartoupper($lieu->getNom()).'</h3>');
  						$this->addElement('content', '</div>');
  						$this->addElement('content', '<div class="modal-body"></div>');
  						$this->addElement('content', '<div class="modal-footer"> <a class="btn" data-dismiss="modal">Fermer</a> </div>');
					$this->addElement('content', '</div>');
				}
			}
		}
		
		return $ratio;
	}
	
	public function getVille($idVille){
		$ville = GestionLieux::getInstance()->getLieu($idVille);
		$pays = GestionPays::getInstance()->getPays($ville->getIdPays());
		$continent = GestionContinents::getInstance()->getContinent($pays->getIdContinent());
		
		$this->couleur = $continent->getCouleur();
		$this->addElement('title', $continent->getNom());
		$this->addElement('title', $pays->getNom());
		$this->addElement('title', $ville->getNom());
		
		$urlContinent = new Url(true);
		$urlContinent->addParams(array('page' => 'continent', 'id' => $continent->getId()));
		$this->addElement('filariane', '<a href="'.$urlContinent->getUrl().'">'.$continent->getNom().'</a>');
		$urlPays = new Url(true);
		$urlPays->addParams(array('page' => 'pays', 'id' => $pays->getId()));
		$this->addElement('filariane', '<a href="'.$urlPays->getUrl().'">'.$pays->getNom().'</a>');
		$urlVille = new Url(true);
		$urlVille->addParams(array('page' => 'ville', 'id' => $ville->getId()));
		$this->addElement('filariane', '<a href="'.$urlVille->getUrl().'">'.$ville->getNom().'</a>');
		
		$this->addElement('content', "<div class='span12'><h1 style='color: ".$continent->getCouleur().";'>".$ville->getNom()."</h1></div>");
		
		$this->addElement('content', '<div class="row-fluid">');
			$this->addElement('content', '<div class="span8">');
			$lieuxLies = GestionLieux::getInstance()->getLieuxLies($ville->getId(),'nom');
			if($lieuxLies) {	
				foreach ($lieuxLies as $lieu){
					if($lieu instanceof Lieu) {
						$photo = GestionPhotos::getInstance()->getLieuPhoto($lieu->getId());
						$nbPhoto = GestionPhotos::getInstance()->getNombreLieuPhoto($lieu->getId());
						$this->addElement('content', '<div class="conteneur row-fluid">');
							$this->addElement('content', '<div class="cadreGauche">');
								$this->addElement('content', '<div class="cadrePhoto thumbnail" id="'.$lieu->getId().'">');
									$this->addElement('content', '<div class="nbPhoto">'.$nbPhoto.'</div>');
									$this->addElement('content', '<a class="" data-toggle="modal" href="ajax.php?page=getNewVisioneuse&idLieu='.$lieu->getId().'" data-target="#visio_'.$lieu->getId().'">');	
										$this->addElement('content', '<img src="'.$photo->getUrlPhotoMiniature(200,'L').'" width="200px" />');
									$this->addElement('content', '</a>');
								$this->addElement('content', '</div>');
							$this->addElement('content', '</div>');
							$this->addElement('content', '<div class="cadreTexte">');
								$this->addElement('content', '<h2>'.$lieu->getPronom().' '.firstchartoupper($lieu->getNom()).'</h2>');
								$this->addElement('content', '<a name="'.$lieu->getId().'"></a>');
								
								$this->addElement('content', '<div class="modal hide fade" id="visio_'.$lieu->getId().'">');
		  							$this->addElement('content', '<div class="modal-header"> <a class="close" data-dismiss="modal">x</a>');
		    							$this->addElement('content', '<h3>'.$lieu->getPronom().' '.firstchartoupper($lieu->getNom()).'</h3>');
		  							$this->addElement('content', '</div>');
		  							$this->addElement('content', '<div class="modal-body"></div>');
		  							$this->addElement('content', '<div class="modal-footer"> <a class="btn" data-dismiss="modal">Fermer</a> </div>');
								$this->addElement('content', '</div>');
							$this->addElement('content', '</div>');
						$this->addElement('content', '</div>');
						
					}
				}
			
			}
			
			$this->addElement('content', '</div>');
		
			if(strrpos($ville->getCoordonnees(), ",") !== false) {
				$coords = explode(',',$ville->getCoordonnees());
				$coords[0] = $coords[0] - 125;
				$coords[1] = $coords[1] - 125;
			}
			else {
				$coords = array(0,0);
			}
			$this->addElement('content', '<div id="infosVille" class="span4">');
				$this->addElement('content', '<div id="miniCarte" style="background-color: '.$continent->getCouleurOcean().';">');
					$paysSVG = GestionPaysSVG::getInstance()->getPaysSVGPays($pays->getId());
					if($paysSVG) {
						$this->getCartePaysSVG($pays, array($ville->getCx(),$ville->getCy()),true,false);
					}
					else {
						$this->addElement('content', '<img id="paysCarte" src="'.$pays->getUrlCarte().'" usemap="#mapPays" style="position: relative; left: -'.$coords[0].'px; top: -'.$coords[1].'px;"/>');
						//affichage des Lieux
						$this->addElement('content', '<map name="mapPays" id="mapPays" >');
						$lieux = GestionLieux::getInstance()->getPaysLieux($pays->getId());
						if($lieux){
							foreach ($lieux as $lieu){
								if($lieu instanceof Lieu){
									if($lieu->getCoordonnees() || ($lieu->getCx() && $lieu->getCy())) {
										$url = new Url();
										$url->addParam('page', $lieu->getType());
										$url->addParam('id', $lieu->getId());
										$chaineUrl = 'href="'.$url->getUrl().'"';
										if(!$lieu->hasLieuxLies()) {
											$chaineUrl = '';
										}
										$this->addElement('content', '<area   shape="circle" coords="'.$lieu->getCoordonnees().',5" '.$chaineUrl.' alt="'.$lieu->getId().'">');
									}
								}
							}
						}
						$this->addElement('content', '</map><img src="style/villeSelected.png" id="villeSelected" />');
						$this->addElement('content','<input type="hidden" id="current" value="0"/>');
					}
				$this->addElement('content', '</div>');
			
				$liens = GestionLiens::getInstance()->getLiensLieu($ville->getId());
				
				if($liens) {
					$this->addElement('content', '<span style="color:'.$this->couleur.'; font-size: 1.1em;">Liens utiles :</span>');
					$this->addElement('content', '<ul id="liensLieu" style="margin-top: 5px;">');
					foreach ($liens as $lien) {
						if($lien instanceof Lien) {
							$this->addElement('content', '<li id="lien_'.$lien->getId().'"><a href="'. $lien->getUrl() .'" target="_blank">'
									. $lien->getNom() . '</a>&nbsp;<input type="image" src="style/delete.png" id="btnLienMort_'.$lien->getId().'" class="btnLienMort" alt="'.$lien->getId().'" name="Signaler un lien mort"></li>');
						}
					}
					$this->addElement('content', '</ul>'
						. '<script>'
						. "	$('.btnLienMort').click(function(){"
						. "		$('#btnLienMort_'+$(this).attr('alt')).attr('src','style/loader_16.gif');"
						. "		$.get('ajax.php?page=traitementLienMort&id='+$(this).attr('alt'), function(data) {"
						. "			var result = eval('(' + data + ')');"
						. "			$('#btnLienMort_'+result.id).remove();"
						. "		});"
						. "	});"
						. '</script>'
					);
				}
				
			$this->addElement('content', '</div>');
		
		$this->addElement('content', '</div>');
	}
	
	
	public function getMerveille($idMerveille){
		$merveille = GestionLieux::getInstance()->getLieu($idMerveille);
		$pays = GestionPays::getInstance()->getPays($merveille->getIdPays());
		$continent = GestionContinents::getInstance()->getContinent($pays->getIdContinent());
		
		$this->couleur = $continent->getCouleur();
		$this->addElement('title', $continent->getNom());
		$this->addElement('title', $pays->getNom());
		$this->addElement('title', $merveille->getNom());
		
		$urlContinent = new Url(true);
		$urlContinent->addParams(array('page' => 'continent', 'id' => $continent->getId()));
		$this->addElement('filariane', '<a href="'.$urlContinent->getUrl().'">'.$continent->getNom().'</a>');
		$urlPays = new Url(true);
		$urlPays->addParams(array('page' => 'pays', 'id' => $pays->getId()));
		$this->addElement('filariane', '<a href="'.$urlPays->getUrl().'">'.$pays->getNom().'</a>');
		$urlMerveille = new Url(true);
		$urlMerveille->addParams(array('page' => 'merveille', 'id' => $merveille->getId()));
		$this->addElement('filariane', '<a href="'.$urlMerveille->getUrl().'">'.$merveille->getNom().'</a>');
		
		$this->addElement('content', "<div class='span12'><h1 style='color:".$continent->getCouleur().";'>".$merveille->getNom()."</h1></div>");
		
		$this->addElement('content', '<div class="row-fluid">');
		
			//la mini carte
			
			
			
			if(strrpos($merveille->getCoordonnees(), ",") !== false) {
				$coords = explode(',',$merveille->getCoordonnees());
				$coords[0] = $coords[0] - 125;
				$coords[1] = $coords[1] - 125;
			}
			else {
				$coords = array(0,0);
			}
			$this->addElement('content', '<div id="infosVille" class="offset8 span4">');
				$this->addElement('content', '<div id="miniCarte" style="background-color: '.$continent->getCouleurOcean().';">');
					$paysSVG = GestionPaysSVG::getInstance()->getPaysSVGPays($pays->getId());
					if($paysSVG) {
						$this->getCartePaysSVG($pays, array($merveille->getCx(),$merveille->getCy()));
					}
					else {
						$this->addElement('content', '<img id="paysCarte" src="'.$pays->getUrlCarte().'" usemap="#mapPays" style="position: relative; left: -'.$coords[0].'px; top: -'.$coords[1].'px;"/>');
						//affichage des Lieux
						$this->addElement('content', '<map name="mapPays" id="mapPays" >');
						$lieux = GestionLieux::getInstance()->getPaysLieux($pays->getId());
						if($lieux){
							foreach ($lieux as $lieu){
								if($lieu instanceof Lieu){
									if($lieu->getCoordonnees() || ($lieu->getCx() && $lieu->getCy())) {
										$url = new Url();
										$url->addParam('page', $lieu->getType());
										$url->addParam('id', $lieu->getId());
										$chaineUrl = 'href="'.$url->getUrl().'"';
										if(!$lieu->hasLieuxLies()) {
											$chaineUrl = '';
										}
										$this->addElement('content', '<area   shape="circle" coords="'.$lieu->getCoordonnees().',5" '.$chaineUrl.' alt="'.$lieu->getId().'">');
									}
								}
							}
						}
						$this->addElement('content', '</map><img src="style/villeSelected.png" id="villeSelected" />');
						$this->addElement('content','<input type="hidden" id="current" value="0"/>');
					}
				$this->addElement('content', '</div>');
			$this->addElement('content', '</div>');
		$this->addElement('content', '</div>');
		
	//	$this->addElement('content', "<h1 style='color:".$continent->getCouleur().";'>".$merveille->getNom()."</h1>");

		$this->addElement('content', '<div class="row-fluid">');
			//ici la visioneuse
			$this->addElement('content', '<div class="offset2 span8 thumbnail" id="visioContainer"></div>');
		$this->addElement('content', '</div>');
		
		$this->addElement('content', '<script>'
			. '$(document).ready(function(){'
			. '	$.get(\'ajax.php?page=getNewVisioneuse&idLieu='.$merveille->getId().'\', function(data) {'
			. '		$("#visioContainer").html(data);'
			. '	});'
			. '});'
			. '</script>'
		);

	}
	
	public function getListePlansMetros() {
		$this->addElement('title', 'Plan des Métro');
		$url = new Url(true);
		$url->addParam('page', 'plansmetros');
		//hard reset du filariane pour la page des plans 
		$this->filariane = array();
		$this->addElement('filariane', '<a href="'.$url->getUrl().'">Plan des Métro</a>');
	
		
		$this->addElement('content', '<div class="span12"><h1>Plans des Métros</h1></div>');
		
		$this->addElement('content', '<div class="row-fluid">');
		
		$pays = GestionPays::getInstance()->getAllPays('nom');
		
		$listePays = '<div class="tabbable tabs-left" ><ul class="nav nav-tabs">';
		
		$pays_id = -1;
		if(!is_null($this->id) and $this->id != 0) {
			$pays_id = $this->id;
		}
		
		foreach ($pays as $p) {
			if($p instanceof Pays) {
				$urlPays = new Url();
				$urlPays->addParam('page', 'plansmetros');
				$urlPays->addParam('id', $p->getId());
				$class = "";
				if($pays_id == $p->getId()) {
					$class = "active";
					$this->addElement('filariane', '<a href="'.$urlPays->getUrl().'">'.$p->getNom().'</a>');
				}
				$listePays .= '<li class="'.$class.'"><a href="'.$urlPays->getUrl().'">'.$p->getNom().'</a></li>';
			}
		}
		
		$listePays .= '</ul>';
		
		$this->addElement('content', $listePays);
		
		if(!is_null($this->id) and $this->id != 0) {
			//on affiche les villes et liens du pays
			$pays = GestionPays::getInstance()->getPays($this->id);
			$this->addElement('title', $pays->getNom());
			
			
			$this->addElement('content', '<div id="tableMetro" class="tab-content">');
			$this->addElement('content', '<table id="tableLiens" class="table table-striped table-condensed">');
			
			$villes = GestionLieux::getInstance()->getPaysLieuxByType($pays->getId(), 'ville', 'nom');
			$tableLiens = '';
			$nbLiens = 0;
			foreach ($villes as $ville) {
				if($ville instanceof Lieu) {
					$liens = GestionLiens::getInstance()->getLiensLieuByType($ville->getId(),'metro');
					if($liens){
						foreach ($liens as $lien){
							if($lien instanceof Lien){
								$tableLiens .= '<tr><td>'.$ville->getNom().'</td><td><a href="'.$lien->getUrl().'" target="_blank">'.$lien->getNom().'</a></td><td>&nbsp;<a href="#" style="font-size: .7em; color: red;" id="btnLienMort_'.$lien->getId().'" class="btnLienMort" alt="'.$lien->getId().'" >Signaler le lien comme mort</a></td></tr>';
								$nbLiens++;
							}
						}
					}
				}
			}
			if($nbLiens == 0) {
				$tableLiens .= '<tr><td>Pas de liens pour ce pays</td></tr>';
			}
			else {
				$tableLiens = '<tr><th>Ville</th><th>Lien vers le Plan du Métro</th><th></th></tr>' . $tableLiens;
			}
			
			$this->addElement('content', $tableLiens);
			
			$this->addElement('content', '</table>');
			
			$this->addElement('content', ''
				. '<script>'
				. "	$('.btnLienMort').click(function(){"
				. "		$('#btnLienMort_'+$(this).attr('alt')).html('<img src=\"style/loader_16.gif\"/>');"
				. "		$.get('ajax.php?page=traitementLienMort&id='+$(this).attr('alt'), function(data) {"
				. "			var result = eval('(' + data + ')');"
				. "			$('#btnLienMort_'+result.id).remove();"
				. "		});"
				. "	});"
				. '</script>'
			);
			
			$this->addElement('content', '</div>');
		}
		
		$this->addElement('content', '</div></div>');
		
	}
	
	public function getFormulaireContact($titleDisplay = true){
		if($titleDisplay){
			$this->addElement('title', 'Contact');
			$url = new Url(true);
			$url->addParam('page', 'contact');
			$this->addElement('filariane', '<a href="'.$url->getUrl().'">Contact</a>');
		}
		else {
			$this->addElement('content', '<div id="dialog-contact" title="Contactez nous">');
		}
		
		$P_nom = isset($_POST['nom'])?$_POST['nom']:'';
		$P_adresse_mail = isset($_POST['adresse_mail'])?$_POST['adresse_mail']:'';
		$P_sujet = isset($_POST['sujet'])?$_POST['sujet']:'';
		$P_text_contact = isset($_POST['text_contact'])?$_POST['text_contact']:'';
		$P_verifBot = isset($_POST['verifBot'])?$_POST['verifBot']:'';
		
		if($P_nom == '' or $P_adresse_mail == '' or $P_text_contact == '' or $P_sujet == '' or $P_verifBot != 'deux'){
			$P_verifBot = 'Résultat de la soustraction en lettres minuscule.';
			$form = '<form class="formContact" method="POST" action="">';
			if($titleDisplay){
				$form .= '<h2>Contactez nous</h2>';
			}
			$form .= '<label for="nom">Votre nom : </label><input type="text" name="nom" id="nom" value="'.$P_nom.'"/><br/>'
				. '<label for="adresse_mail">Votre adresse mail : </label><input type="text" name="adresse_mail" id="adresse_mail" value="'.$P_adresse_mail.'"/><br/>'
				. '<label for="sujet">Sujet du message : </label><input type="text" name="sujet" id="sujet" value="'.$P_sujet.'"/><br/>'
				. '<label for="verifBot">AntiBot : 4-2= ? : </label><input type="text" name="verifBot" id="verifBot" value="'.$P_verifBot.'"/><br/>'
				. '<label for="text_contact">Votre message : </label><br/><textarea id="text_contact" name="text_contact">'.$P_text_contact.'</textarea><br/>';
			if($titleDisplay){
				$form .= '<input type="submit" value="Envoyer"/>';
			}
			$form .= '</form>';
			$this->addElement('content', $form);
			if(!$titleDisplay){
				$this->addElement('content', '</div>');
			}
		}
		else {
			$message = new MessageContact();
			$message->setDate(date('Y-m-d H:i:s',time()));
			$message->setLu(false);
			$message->setNom($P_nom);
			$message->setMail($P_adresse_mail);
			$message->setSujet($P_sujet);
			$message->setMessage($P_text_contact);
			if(isset($_SESSION['vip']['isConnect']) and $_SESSION['vip']['isConnect'] == 'true') {
				$userVip = GestionUsersVip::getInstance()->getUserVip($_SESSION['vip']['id']);
				if($userVip and $userVip instanceof VipUser){
					$message->setIdUser($userVip->getId());
				}
			}
			$message->enregistrer();
			$this->addElement('content','Message envoyé');
		}
	}
	
	public function getRecherche($recherche) {
		
		$this->addElement('title', 'Recherche de '.$recherche);
		$url = new Url(true);
		$url->addParam('page', 'recherche');
		$this->addElement('filariane', '<a href="'.$url->getUrl().'&query='.$recherche.'">Recherche de '.$recherche.'</a>');
		
		$this->addElement('content','<div id="pageRecherche">');
		$this->addElement('content', '<h1>Recherche de '.$recherche.'</h1>');
		
		//en premier lieu, il faut �liminer le pronom �ventuel qui fausserait la recherche
		$pronoms = array('le ', 'la ', 'les', 'l\'');
		
		trim($recherche);
		if(strlen($recherche)>3){
			foreach ($pronoms as $p){
				$pos = strpos($recherche, $p);
				if($pos !== false && $pos <= 2){
					$begin = substr($recherche, 0, 3);
					$end = substr($recherche, 3);
					$begin = str_ireplace($p, " ", $begin);
					$recherche = trim($begin.$end);
				}
			}
			trim($recherche);
		}
		

		//affichage des r�sultats
		
		$lieux = GestionLieux::getInstance()->getLieuxLike(noSpecialChar2($recherche));
		if($lieux) {
			foreach ($lieux as $lieu) {
				if($lieu instanceof Lieu) {
					$urlLieu = new Url(true);
					
					$pays = GestionPays::getInstance()->getPays($lieu->getIdPays());
					if($pays){
						$pays = $pays->getNom();
					}
					$ville = GestionLieux::getInstance()->getLieuParent($lieu->getId());
					
					if($ville){
						if($ville instanceof Lieu) {
							if($ville->getType() == "ville") {
								$pays = $ville->getNom().', '.$pays;
								$urlLieu->addParam('page','ville');
								$urlLieu->addParam('id',$ville->getId());
							}
						}
					}
					else {
						$urlLieu->addParam('page',$lieu->getType());
						$urlLieu->addParam('id',$lieu->getId());
					}
					
					
					$this->addElement('content', '<div class="resultat row-fluid">');
						$this->addElement('content', '<div class="span12">');
							$photo = GestionPhotos::getInstance()->getLieuPhoto($lieu->getId());
							$this->addElement('content','<div class="photoMin thumbnail">');
								$this->addElement('content', '<img src="'.$photo->getUrlPhotoMiniature(150, 'H').'"  >');
							$this->addElement('content','</div>');
							$this->addElement('content','<div class="descriptif">');
								$this->addElement('content', '<h2><a href="'.$urlLieu->getUrl().'#'.$lieu->getId().'">'.$lieu->getPronom().' '.$lieu->getNom().'</a></h2>');
								$this->addElement('content', firstchartoupper($lieu->getType()).' de '.$pays);
							$this->addElement('content','</div>');
						$this->addElement('content','</div>');
					$this->addElement('content', '</div>');
				}
			}
			
		}
		else {
			$this->addElement('content', 'Pas de résultats.');
		}
		$this->addElement('content','</div>');
	}
}