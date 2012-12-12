<?php
if(isset($_GET['idContinent']) and !empty($_GET['idContinent'])
	and isset($_POST['nomPays']) and !empty($_POST['nomPays']) 
	and isset($_POST['urlOrigineCarte']) and !empty($_POST['urlOrigineCarte'])
	and isset($_POST['coordPays_0']) and !empty($_POST['coordPays_0'])
	and isset($_POST['nbCoord']) and $_POST['nbCoord'] >= 0
	and !empty($_FILES['cartePays']['size'])
//	and $_FILES['cartePays']['error'] == 0
){
	
	//on récupère le nom du continent
	$continent = GestionContinents::getInstance()->getContinent($_GET['idContinent']);
	if ($continent instanceof Continent) {
	//	debug($_FILES);
		$dos = strtolower(substr(noSpecialChar($continent->getNom()),0,2));
		if(!is_dir(strtolower('images/'.$dos))){
			mkdir(strtolower('images/'.$dos),0777);
		}
		$nomPhoto = noSpecialChar($_FILES['cartePays']['name']);
		$emplacement = strtolower('images/'.$dos.'/'.$nomPhoto);
		//enregistrement du fichier
		
		move_uploaded_file($_FILES['cartePays']['tmp_name'],$emplacement);
		
		//creation du pays
		$pays = new Pays();
		$pays->setIdContinent($continent->getId());
		$pays->setNom($_POST['nomPays']);
		$pays->setUrlCarteFrom($_POST['urlOrigineCarte']);
		$pays->setUrlCarte($emplacement);
		
		$idPays = $pays->enregistrer();
		
		//creation des coords de map
		for($i=0; $i<=$_POST['nbCoord']; $i++){
			if(isset($_POST['coordPays_'.$i]) and !empty($_POST['coordPays_'.$i])){
				$map = new Map();
				$map->setIdContinent($continent->getId());
				$map->setIdPays($idPays);
				$map->setCoordonnees($_POST['coordPays_'.$i]);
				$map->enregistrer();
			}
		}
		
		$url = new Url(true);
		echo 'Pays enregistré.<br/><a href="'.$url->getUrl().'">retour accueil</a>';
	}
	else {
		//continent erreur
		$url = new Url(true);
		echo 'Une erreur est survenue. (2)<br/>Enregistrement ignoré.<br/><a href="'.$url->getUrl().'">retour accueil</a>';
		debug($_FILES);debug($_POST);debug($_GET);debug($continent);
	}
}
else {
	//une des variable non definie
	$url = new Url(true);
	echo 'Une erreur est survenue. (1)<br/>Enregistrement ignoré.<br/><a href="'.$url->getUrl().'">retour accueil</a>';
	
	debug($_FILES);debug($_POST);debug($_GET);
}