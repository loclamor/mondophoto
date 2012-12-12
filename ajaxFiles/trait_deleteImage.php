<?php

$photo = GestionPhotos::getInstance()->getPhoto($_GET['idPhoto']);

if($photo and $photo instanceof Photo){
	$urlImage = $photo->getUrlPhoto();
	
	$pathinfos = pathinfo($urlImage);
	$tmp = explode('_',$pathinfos['filename']);
	$origineName = $tmp[0];
	$j = 1;
	while($j < count($tmp)-1) {
		$origineName .= '_' . $tmp[$j];
		$j++;
	}
	if($tmp[count($tmp)-1] == ''){
		$origineName .= '_';
	}

	$origineFile = $pathinfos['dirname'].'/'.$origineName.'.'.$pathinfos['extension'];
	if(is_file($origineFile)) {
		if(unlink($origineFile)) {
			$result = array( 'res' => 'true', 'id' => $photo->getId());
		}
		else {
			$result = array( 'res' => 'false', 'id' => $photo->getId(),'error' => 'cannot unlink');
		}
	}
	else {
		$result = array( 'res' => 'false', 'id' => $photo->getId(),'error' => 'not a file');
	}
	
}
else {
	$result = array( 'res' => 'false', 'id' => $photo->getId(),'error' => 'not a Photo');
}

echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);