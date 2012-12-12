<?php
//avant tout, mise à jour des infos du lieu
if(isset($_GET['idLieu']) and !empty($_GET['idLieu'])
	and isset($_POST['nomLieu']) and !empty($_POST['nomLieu'])
	and isset($_POST['pronom'])
	and isset($_POST['typeLieu']) and !empty($_POST['typeLieu'])
	and isset($_POST['categLieu']) and !empty($_POST['categLieu'])
){
	$lieu = GestionLieux::getInstance()->getLieu($_GET['idLieu']);
	$lieu->setNom($_POST['nomLieu']);
	$lieu->setPronom($_POST['pronom']);
	$lieu->setType($_POST['typeLieu']);
	$lieu->setCategorie($_POST['categLieu']);
	$lieu->enregistrer();
	echo 'Lieu mis à jour.<br/>';
}
else {
	//une des variable non definie
	echo 'Une erreur est survenue. (0.1)<br/>Mise à jour du lieu ignorée.';
	debug($_FILES);debug($_POST);debug($_GET);
}


//en premier lieu la mise à jour et supression existant
if(isset($_GET['idLieu']) and !empty($_GET['idLieu'])
	and isset($_POST['idE']) and !empty($_POST['idE'])
	and isset($_POST['nomPhotoE']) and !empty($_POST['nomPhotoE'])
	and isset($_POST['nomPropE']) and !empty($_POST['nomPropE'])
	and isset($_POST['dateE']) and !empty($_POST['dateE']) 
){
//	debug($_POST);
	if(is_array($_POST['idE'])){
		for($i=0;$i<count($_POST['idE']);$i++){
			$photo = new Photo($_POST['idE'][$i]);
			if(!isset($_POST['supprimer']['E'.$i])) {
				$photo->setNom($_POST['nomPhotoE'][$i]);
				$photo->setProprietaire($_POST['nomPropE'][$i]);
				$photo->setDatePriseVue($_POST['dateE'][$i]);
				$photo->setAffiche(isset($_POST['affiche']['E'.$i]));
				$photo->setImagePrincipale(($_POST['img_ppal']=='E'.$_POST['idE'][$i]));
				$photo->enregistrer();
				echo $photo->getUrlPhoto().' mis à jour.<br/>';
			}
			else {
				unlink($photo->getUrlPhoto());
				$urlP = $photo->getUrlPhoto();
				$photo->supprimer();
				echo $urlP.' suprimée.<br/>';
			}
		}
	}
	else {
		$photo = new Photo($_POST['idE[0]']);
		if(!isset($_POST['supprimer[E0]'])) {
			$photo->setNom($_POST['nomPhotoE[0]']);
			$photo->setProprietaire($_POST['nomPropE[0]']);
			$photo->setDatePriseVue($_POST['dateE[0]']);
			$photo->setAffiche(isset($_POST['affiche[E0]']));
			$photo->setImagePrincipale(($_POST['img_ppal']=='E'.$_POST['idE[0]']));
			$photo->enregistrer();
			echo $photo->getUrlPhoto().' mis à jour.<br/>';
		}
		else {
			unlink($photo->getUrlPhoto());
			$urlP = $photo->getUrlPhoto();
			$photo->supprimer();
			echo $urlP.' suprimée.<br/>';
		}
	}
}
else {
	//une des variable non definie
	echo 'Une erreur est survenue. (1.1)<br/>Mise à jour ignoré.';
	debug($_POST);debug($_GET);
}

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
				if(!isset($_POST['supprimer']['N'.$i])) {
					$photo = new Photo();
					$photo->setIdLieu($lieu->getId());
					$photo->setUrlPhoto($_POST['upload'][$i]);
					$photo->setNom($_POST['nomPhoto'][$i]);
					$photo->setProprietaire($_POST['nomProp'][$i]);
					$photo->setDatePriseVue($_POST['date'][$i]);
					$photo->setAffiche(isset($_POST['affiche']['N'.$i]));
					$photo->setImagePrincipale(($_POST['img_ppal']=='N'.$i));
					$photo->enregistrer();
					echo $photo->getUrlPhoto().' enregistré.<br/>';
				}
				else {
					unlink($_POST['upload'][$i]);
					echo $_POST['upload'][$i].' effacé.<br/>';
				}
			}
		}
		else {
			if(!isset($_POST['supprimer[N0]'])) {
				$photo = new Photo();
				$photo->setIdLieu($lieu->getId());
				$photo->setUrlPhoto($_POST['upload[0]']);
				$photo->setNom($_POST['nomPhoto[0]']);
				$photo->setProprietaire($_POST['nomProp[0]']);
				$photo->setDatePriseVue($_POST['date[0]']);
				$photo->setAffiche(isset($_POST['affiche[N0]']));
				$photo->setImagePrincipale(($_POST['img_ppal']=='N0'));
				$photo->enregistrer();
				echo $photo->getUrlPhoto().' enregistré.<br/>';
			}
			else {
				unlink($_POST['upload[0]']);
				echo $_POST['upload[0]'].' effacé.<br/>';
			}
		}
		echo 'Photos enregistrées.';
		
		//debug(exif_read_data($emplacement));
	}
	else {
		//lieu erreur
		echo 'Une erreur est survenue. (2.2)<br/>Enregistrement ignoré.';
		debug($_POST);debug($_GET);debug($lieu);debug($pays);debug($continent);
	}
}
else {
	//une des variable non definie
	echo 'Une erreur est survenue. (2.1)<br/>Enregistrement ignoré.';
	debug($_POST);debug($_GET);
}
$lieu = GestionLieux::getInstance()->getLieu($_GET['idLieu']);
$urlAgain = new Url();
$urlAgain->addParam('page', 'modificationLieu');
echo '<br/><a href="'.$urlAgain->getUrl().'">'.$lieu->getNom().' : modifier à nouveau</a>';

$url = new Url(true);
echo '<br/><a href="'.$url->getUrl().'">retour accueil</a>';