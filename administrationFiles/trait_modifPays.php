<?php
if(isset($_GET['idPays']) and !empty($_GET['idPays'])
	and isset($_POST['nomPays']) and !empty($_POST['nomPays']) 
	and isset($_POST['urlOrigineCarte']) and !empty($_POST['urlOrigineCarte'])
){
	$pays = GestionPays::getInstance()->getPays($_GET['idPays']);
	if($pays instanceof Pays) {
		$newEmplacement = $pays->getUrlCarte();
		if(!empty($_FILES['cartePays']['size']) and !empty($_FILES['cartePays']['name']) and !empty($_FILES['cartePays']['size'])) {
			//il y a une new photo
			$continent = GestionContinents::getInstance()->getContinent($pays->getIdContinent());
			$dos = strtolower(substr(noSpecialChar($continent->getNom()),0,2));
			if(!is_dir(strtolower('images/'.$dos))){
				mkdir(strtolower('images/'.$dos),0777);
			}
			$nomPhoto = noSpecialChar($_FILES['cartePays']['name']);
			$emplacement = strtolower('images/'.$dos.'/'.$nomPhoto);
			//enregistrement du fichier
			
			move_uploaded_file($_FILES['cartePays']['tmp_name'],$emplacement);
			echo 'image enregistree<br/>';
			$newEmplacement = $emplacement;
		}
		//on met a jour le pays
		$pays->setNom($_POST['nomPays']);
		$pays->setUrlCarte($newEmplacement);
		$pays->setUrlCarteFrom($_POST['urlOrigineCarte']);
		$pays->enregistrer();
		
		echo 'Pays mis à jour<br/>';
		
		$url = new Url(true);
		echo '<a href="'.$url->getUrl().'">retour accueil</a>';
		
	}
	else {
		//pays erreur
		$url = new Url(true);
		echo 'Une erreur est survenue. (2)<br/>Enregistrement ignoré.<br/><a href="'.$url->getUrl().'">retour accueil</a>';
		debug($_FILES);debug($_POST);debug($_GET);debug($pays);
	}
}	
else {
	//une des variable non definie
	$url = new Url(true);
	echo 'Une erreur est survenue. (1)<br/>Enregistrement ignoré.<br/><a href="'.$url->getUrl().'">retour accueil</a>';
	
	debug($_FILES);debug($_POST);debug($_GET);
}