<?php
//debug($_POST);
$uvExist = false;
$uvId = false;
$urlAction = new Url(true);
$urlAction->addParam('page', 'accueil');



if(isset($_POST['user']) and !empty($_POST['user']) and isset($_POST['pwd']) and !empty($_POST['pwd'])){
	
	$user = GestionUsersVip::getInstance()->getUserVipByMail($_POST['user']);
	if($user and $user instanceof VipUser){
		if($user->getPassword() == md5($_POST['pwd'])) {
			if($user->isActif()) {
				if(!$user->isBanni()) {
					$_SESSION['vip']['isConnect'] = 'true';
					$_SESSION['vip']['id'] = $user->getId();
				}
				else {
					unset($_SESSION['vip']['isConnect']);
					unset($_SESSION['vip']['id']);
					$urlAction->addParam('mess', 'banni');
				}
			}
			else {
				unset($_SESSION['vip']['isConnect']);
				unset($_SESSION['vip']['id']);
				$urlAction->addParam('mess', 'inactif');
			}
		}
		else {
			unset($_SESSION['vip']['isConnect']);
			unset($_SESSION['vip']['id']);
			$urlAction->addParam('mess', 'wrongPass');
		}
		
	}
	else {
		unset($_SESSION['vip']['isConnect']);
		unset($_SESSION['vip']['id']);
		$urlAction->addParam('mess', 'inconnu');
	}
	
}
else {
	unset($_SESSION['vip']['isConnect']);
	unset($_SESSION['vip']['id']);
	$urlAction->addParam('mess', 'vide');
}

redirect($urlAction->getUrl());
echo 'Vous avez été connecté.<br/><a href="'.$urlAction->getUrl().'">retour accueil</a>';