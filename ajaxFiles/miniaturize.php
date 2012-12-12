<?php
$return = '';
$start = $_GET['start'];
$nb = $_GET['nb'];
$res = SQL::getInstance()->exec('SELECT idPhoto FROM photo WHERE img_ppal = 1 ORDER BY idPhoto DESC LIMIT '.$start.', '.$nb);
if($res){
	foreach ($res as $row){
		$photo = GestionPhotos::getInstance()->getPhoto($row['idPhoto']);
		$return .= date('[H:i:s]',time()).' '.$photo->getUrlPhoto().'<br/>';
		$return .= '--> '.$photo->getUrlPhotoMiniature(150,'H').'<br/>';
		$return .= '--> '.$photo->getUrlPhotoMiniature(200,'L').'<br/>';
	}
}

echo $return;