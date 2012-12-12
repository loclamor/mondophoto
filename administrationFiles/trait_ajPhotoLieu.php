<?php
if(isset($_GET['idLieu']) and !empty($_GET['idLieu'])
	and isset($_POST['nomPhoto']) and !empty($_POST['nomPhoto'])
	and isset($_POST['nomProp']) and !empty($_POST['nomProp'])
	and isset($_POST['date']) and !empty($_POST['date']) 
	and isset($_POST['upload']) and !empty($_POST['upload'])
){
	
	//on récupère les noms du lieu, du pays, du continent
	$lieu = GestionLieux::getInstance()->getLieu($_GET['idLieu']);
	$pays = GestionPays::getInstance()->getPays($lieu->getIdPays());
	$continent = GestionContinents::getInstance()->getContinent($pays->getIdContinent());
	if (($lieu instanceof Lieu) and ($pays instanceof Pays) and ($continent instanceof Continent)) {
		if (is_array($_POST['nomPhoto'])){
			$count = count($_POST['nomPhoto']);
			for($i=0;$i<$count;$i++) {
				//creation de la Photo
				$photo = new Photo();
				$photo->setIdLieu($lieu->getId());
				$photo->setUrlPhoto($_POST['upload'][$i]);
				$photo->setNom($_POST['nomPhoto'][$i]);
				$photo->setProprietaire($_POST['nomProp'][$i]);
				$photo->setDatePriseVue($_POST['date'][$i]);
				$photo->enregistrer();
				echo $photo->getUrlPhoto().' enregistré.<br/>';
			}
		}
		else {
			$photo = new Photo();
			$photo->setIdLieu($lieu->getId());
			$photo->setUrlPhoto($_POST['upload[0]']);
			$photo->setNom($_POST['nomPhoto[0]']);
			$photo->setProprietaire($_POST['nomProp[0]']);
			$photo->setDatePriseVue($_POST['date[0]']);
			$photo->enregistrer();
			echo $photo->getUrlPhoto().' enregistré.<br/>';
		}
		$url = new Url(true);
		echo 'Photos enregistrées.<br/><a href="'.$url->getUrl().'">retour accueil</a>';
		
		$urlAgain = new Url();
		$urlAgain->addParam('page', 'ajoutPhotoLieu');
		echo '<br/><a href="'.$urlAgain->getUrl().'">'.$lieu->getNom().' : rajouter une photo</a>';
		
		//debug(exif_read_data($emplacement));
	}
	else {
		//lieu erreur
		$url = new Url(true);
		echo 'Une erreur est survenue. (2)<br/>Enregistrement ignoré.<br/><a href="'.$url->getUrl().'">retour accueil</a>';
		debug($_FILES);debug($_POST);debug($_GET);debug($lieu);debug($pays);debug($continent);
	}
}
else {
	//une des variable non definie
	$url = new Url(true);
	echo 'Une erreur est survenue. (1)<br/>Enregistrement ignoré.<br/><a href="'.$url->getUrl().'">retour accueil</a>';
	
	debug($_FILES);debug($_POST);debug($_GET);
}