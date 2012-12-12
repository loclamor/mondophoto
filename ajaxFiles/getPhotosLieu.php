<?php
$tab_photos = array();

if(isset($_GET['idLieu']) and !empty($_GET['idLieu'])){
	$photos = GestionPhotos::getInstance()->getLieuPhotos($_GET['idLieu'],'idLieu');
	if($photos) {
		foreach ($photos as $photo) {
			if($photo instanceof Photo) {
				$tab_photos[$photo->getId()] = $photo->getUrlPhoto();
			}
		}
	}
}

echo htmlspecialchars(json_encode($tab_photos), ENT_NOQUOTES);