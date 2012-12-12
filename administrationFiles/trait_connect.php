<?php
//debug($_POST);
if(isset($_POST['pwd']) and $_POST['pwd'] == 'supervoyage'){
	$_SESSION['administration']['isConnect'] = 'true';
}
else {
	unset($_SESSION['administration']['isConnect']);
}
$urlAction = new Url(true);
$urlAction->addParam('page', 'accueil');
redirect($urlAction->getUrl());
echo 'Vous avez été connecté.<br/><a href="'.$urlAction->getUrl().'">retour accueil</a>';