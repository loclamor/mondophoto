<?php
$idLieuVip = $_GET['idLieuVip'];
$lieuVip = GestionLieuxVip::getInstance()->getLieuVip($idLieuVip);
if($lieuVip){
	$photosLieuVip = GestionPhotosVip::getInstance()->getPhotosLieuVip($idLieuVip);
	if($photosLieuVip) {
		$zipName = './vip/'.date("Y-m-d").'lieuVip'.$lieuVip->getId().'.zip';
		
		$z = new ZipArchive();
		if($z->open($zipName, ZIPARCHIVE::CREATE)) {
			foreach ($photosLieuVip as $photoVip) {
				if($photoVip instanceof VipPhoto) {
					if(!$z->addFile($photoVip->getUrlImage())){
						die('erreur lors de l\'ajout du fichier '.$photoVip->getUrlImage());
					}
				}
			}
			$z->close();
		}
		
		$fic = $zipName;
		
		forceDownload($fic);
		
		//header('Location: '.$fic);
	}
	else {
		echo 'Pas de photos sur ce lieu VIP';
	}
}
else {
	echo 'Le lieu VIP n\'existe pas';
}