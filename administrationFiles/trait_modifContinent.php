<?php
if(isset($_GET['idContinent']) and !empty($_GET['idContinent'])
	and isset($_POST['nomContinent']) and !empty($_POST['nomContinent'])
	and isset($_POST['couleurContinent']) and !empty($_POST['couleurContinent']) 
	and isset($_POST['urlOrigineCarte']) and !empty($_POST['urlOrigineCarte'])
){
	$continent = GestionContinents::getInstance()->getContinent($_GET['idContinent']);
	if($continent instanceof Continent) {
		$newEmplacement = $continent->getUrlCarte();
		if(!empty($_FILES['carteContinent']['size']) and !empty($_FILES['carteContinent']['name']) and !empty($_FILES['carteContinent']['size'])) {
			//il y a une new photo
			if(!is_dir(strtolower('images'))){
				mkdir(strtolower('images'),0777);
			}
			$nomPhoto = noSpecialChar($_FILES['carteContinent']['name']);
			$emplacement = strtolower('images/'.$nomPhoto);
			//enregistrement du fichier
			
			move_uploaded_file($_FILES['carteContinent']['tmp_name'],$emplacement);
			echo 'image enregistree<br/>';
			$newEmplacement = $emplacement;
		}
		//on met a jour le continent
		$continent->setNom($_POST['nomContinent']);
		$continent->setCouleur($_POST['couleurContinent']);
		$continent->setUrlCarte($newEmplacement);
		$continent->setUrlCarteFrom($_POST['urlOrigineCarte']);
		$continent->enregistrer();
		
		echo 'Continent mis à jour<br/>';
		
		$url = new Url(true);
		echo '<a href="'.$url->getUrl().'">retour accueil</a>';
		
	}
	else {
		//pays erreur
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