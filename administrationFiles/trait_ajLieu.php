<?php
if(isset($_GET['idPays']) and !empty($_GET['idPays'])
	and isset($_POST['nomLieu']) and !empty($_POST['nomLieu']) 
	and isset($_POST['coordLieuX']) and !empty($_POST['coordLieuX'])
	and isset($_POST['coordLieuY']) and !empty($_POST['coordLieuY'])
	and isset($_POST['typeLieu']) and !empty($_POST['typeLieu'])
){
	
	//on récupère le pays
	$pays = GestionPays::getInstance()->getPays($_GET['idPays']);
	if ($pays instanceof Pays) {
		$ville = new Lieu();
		$ville->setNom($_POST['nomLieu']);
		$ville->setIdPays($pays->getId());
		$ville->setCoordonnees(null);
		$ville->setCx(round($_POST['coordLieuX'],3));
		$ville->setCy(round($_POST['coordLieuY'],3));
		$ville->setType($_POST['typeLieu']);
		$id = $ville->enregistrer();
		
		echo 'Lieu enregistré.<br/>';
		
		$urlAgain = new Url(true);
		$urlAgain->addParam('page', 'modificationLieu');
		$urlAgain->addParam('idLieu', $id);
		echo '<br/><a href="'.$urlAgain->getUrl().'">'.$ville->getNom().' : modifier</a>';
		
		$urlAgain = new Url(true);
		$urlAgain->addParam('page', 'ajoutLieu');
		$urlAgain->addParam('idPays', $pays->getId());
		echo '<br/><a href="'.$urlAgain->getUrl().'">'.$pays->getNom().' : rajouter un lieu</a>';
		
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