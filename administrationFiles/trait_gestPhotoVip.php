<?php

if(isset($_POST['chxLieuLie']) and !empty($_POST['chxLieuLie'])
	and isset($_POST['idLieuVip']) and !empty($_POST['idLieuVip'])
){
	
	$lieuVip = GestionLieuxVip::getInstance()->getLieuVip($_POST['idLieuVip']);
	
	$photosLieuVip = GestionPhotosVip::getInstance()->getPhotosLieuVip($lieuVip->getId());
	
	$uv = GestionUsersVip::getInstance()->getUserVip($lieuVip->getIdVip());
	
	$lieu = GestionLieux::getInstance()->getLieu($_POST['chxLieuLie']);
	$pays = GestionPays::getInstance()->getPays($lieu->getIdPays());
	$continent = GestionContinents::getInstance()->getContinent($pays->getIdContinent());
	if (($lieu instanceof Lieu) and ($pays instanceof Pays) and ($continent instanceof Continent)) {
	//	debug($_FILES);
		
		$nomLieu = noSpecialChar($lieu->getNom());
		$nomPays = noSpecialChar($pays->getNom());
		$nomCont = noSpecialChar($continent->getNom());
		
		$dos = strtolower(substr($nomCont,0,2).'/'.$nomPays.'/'.$nomLieu);
		
	//	echo $dos.'/'.$nomPhoto;
		
		if(!is_dir('images/'.$dos)){
			if(!is_dir(strtolower('images/'.substr($nomCont,0,2).'/'.$nomPays))){
				if(!is_dir(strtolower('images/'.substr($nomCont,0,2)))){
					if(!is_dir(strtolower('images/'))){
						mkdir(strtolower('images/'),0777);
					}
					mkdir(strtolower('images/'.substr($nomCont,0,2)),0777);
				}
				mkdir(strtolower('images/'.substr($nomCont,0,2).'/'.$nomPays),0777);
			}
			mkdir(strtolower('images/'.$dos),0777);
		}
		$urlImages = 'images/'.$dos.'/';
		$deplace = true;
	}
	else {
		$deplace = false;
	}
	
	//on déplace les enregistrements de photos et on déplace les photos au bon endroit
	
	foreach ($photosLieuVip as $photoVip) {
		if($photoVip instanceof VipPhoto) {
			$photo = new Photo();
			$photo->setAffiche(false);
			$datePV = "0000-00-00";
			$tmp = getImageDate($photoVip->getUrlImage());
			if($tmp) {
				$datePV = $tmp;
			}
		//	debug ($datePV); 
			$photo->setDatePriseVue($datePV);
			$photo->setIdLieu($_POST['chxLieuLie']);
			$photo->setImagePrincipale(false);
			$photo->setNom($lieuVip->getNomLieu());
			$photo->setProprietaire(strtoupper(noSpecialChar($uv->getNom())) . ' ' . firstchartoupper($uv->getPrenom()));
			
		//on récupère les noms du lieu, du pays, du continent
			if($deplace) {
				$pathinfo = pathinfo($photoVip->getUrlImage());
				$nomFichierExt = $pathinfo['basename'];
				$newUrlImage = $urlImages . $nomFichierExt;
				
				echo 'copying ' . $photoVip->getUrlImage() . ' to ' . $newUrlImage . '...';
				if(copy($photoVip->getUrlImage(), $newUrlImage)) {
					$url = $newUrlImage;
					echo ' done <br/>';
				}
				else {
					$deplace = false;
					$url = $photoVip->getUrlImage();
					echo ' fail <br/>';
				}
				
				
			}
			else {
				$url = $photoVip->getUrlImage();
			}
			
			$photo->setUrlPhoto($url);
			if($photo->enregistrer()){
				if($deplace){
					unlink($photoVip->getUrlImage());
					echo 'unlink ' . $photoVip->getUrlImage() . ' done';
					$photoVip->supprimer();
				}
			}
		}
	} 
	
	$url = new Url(true);
	echo 'Photos enregistrées.<br/><a href="'.$url->getUrl().'">retour accueil</a>';
	
	
}