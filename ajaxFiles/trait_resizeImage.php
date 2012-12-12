<?php

$photo = GestionPhotos::getInstance()->getPhoto($_GET['idPhoto']);

if($photo and $photo instanceof Photo){
	$urlImage = $photo->getUrlPhoto();
	
	$sizes = getimagesize($urlImage);
	
	
	//width > height
	if($sizes[0] > $sizes[1]){
		if($sizes[0] < 1001) {
			$result = array( 'res' => 'false', 'id' => $photo->getId());
			echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
			die();
		}
		$resizeType = 'L';
	}
	else {
		if($sizes[1] < 1001) {
			$result = array( 'res' => 'false', 'id' => $photo->getId());
			echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
			die();
		}
		$resizeType = 'H';
	}
	
	$newUrl = redimJPEG($urlImage, 1000, $resizeType);
	
	if(is_file($newUrl)) {
		$photo->setUrlPhoto($newUrl);
		$photo->enregistrer(array('urlPhoto'));
		$result = array( 'res' => 'true', 'id' => $photo->getId());
	}
	else {
		$result = array( 'res' => 'false', 'id' => $photo->getId());
	}
	
}
else {
	$result = array( 'res' => 'false', 'id' => $photo->getId());
}

echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);