<?php
if(isset($_GET['idVille']) and !empty($_GET['idVille'])
	and isset($_POST['nomLieu']) and !empty($_POST['nomLieu'])
	and isset($_POST['pronom']) 
	and isset($_POST['type']) and !empty($_POST['type'])
){
	
	//on récupère la ville
	$ville = GestionLieux::getInstance()->getLieu($_GET['idVille']);
	if (($ville instanceof Lieu) and $ville->getType() == 'ville'){
		
		$lieu = new Lieu();
		$lieu->setIdPays($ville->getIdPays());
		$lieu->setNom($_POST['nomLieu']);
		$lieu->setPronom($_POST['pronom']);
		$lieu->setType($_POST['type']);
		
		$idLieu = $lieu->enregistrer();
		
		$lie = new Lie();
		$lie->setIdLieuParent($ville->getId());
		$lie->setIdLieuFils($idLieu);
		$lie->enregistrer();
		
		$url = new Url(true);
		echo 'Lieu enregistré.<br/><a href="'.$url->getUrl().'">retour accueil</a>';
		
		$urlAgain = new Url();
		$urlAgain->addParam('page', 'ajoutLieuVille');
		echo '<br/><a href="'.$urlAgain->getUrl().'">'.$ville->getNom().' : rajouter un lieu</a>';
		
		$urlAgain = new Url(true);
		$urlAgain->addParam('page', 'modificationLieu');
		$urlAgain->addParam('idLieu', $idLieu);
		echo '<br/><a href="'.$urlAgain->getUrl().'">'.$lieu->getNom().' : modifier</a>';
		/*
		$urlPhoto = new Url(true);
		$urlPhoto->addParam('page', 'ajoutPhotoLieu');
		$urlPhoto->addParam('idLieu', $idLieu);
		echo '<br/><a href="'.$urlPhoto->getUrl().'">'.$lieu->getNom().' : ajouter une photo</a>';
		*/
	}
	else {
		//ville erreur
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