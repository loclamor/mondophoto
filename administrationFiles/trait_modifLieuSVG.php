<?php
if(isset($_GET['idPays']) and !empty($_GET['idPays'])
	and isset($_POST['idLieu']) and !empty($_POST['idLieu']) 
	and isset($_POST['coordLieuX']) and !empty($_POST['coordLieuX'])
	and isset($_POST['coordLieuY']) and !empty($_POST['coordLieuY'])
){
	
	//on récupère le pays
	$pays = GestionPays::getInstance()->getPays($_GET['idPays']);
	if ($pays instanceof Pays) {
		
		$ville = GestionLieux::getInstance()->getLieu($_POST['idLieu']);
		
		$ville->setCoordonnees(null);
		$ville->setCx(round($_POST['coordLieuX'],3));
		$ville->setCy(round($_POST['coordLieuY'],3));

		$id = $ville->enregistrer(array('coordonnees', 'cx', 'cy'));
		
		echo 'Lieu mis à jour.<br/>';
		
		$urlAgain = new Url(true);
		$urlAgain->addParam('page', 'modifLieuSVG');
		$urlAgain->addParam('idPays', $pays->getId());
		echo '<br/><a href="'.$urlAgain->getUrl().'">'.$pays->getNom().' : modifier un lieu</a>';
		
		$url = new Url(true);
		echo '<br/><a href="'.$url->getUrl().'">retour accueil</a>';
	}
	else {
		//pays erreur
		$url = new Url(true);
		echo 'Une erreur est survenue. (2)<br/>Enregistrement ignoré.<br/><a href="'.$url->getUrl().'">retour accueil</a>';
		debug($_POST);debug($_GET);debug($pays);
	}
}
else {
	//une des variable non definie
	$url = new Url(true);
	echo 'Une erreur est survenue. (1)<br/>Enregistrement ignoré.<br/><a href="'.$url->getUrl().'">retour accueil</a>';
	
	debug($_POST);debug($_GET);
}