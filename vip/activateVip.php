<?php
$urlBack = new Url(true);
$urlBack->addParam('page', 'accueil');
//on recup�re le ID compte et la chaine crypt�e
$id = $_GET['compte'];
$chaineOrig = $_GET['membre'];
//on vas maintenant v�rifier la cor�lation entre les 2 chaines crypt�es
$chaine = md5("Cette$id cha�ne$id n\'a$id aucun$id int�r�t$id si$id ce$id n\'est$id perturber$id un$id �ventuel$id hackeur$id :)$id");
if($chaineOrig == $chaine) {
	$uVip = GestionUsersVip::getInstance()->getUserVip($id);
	if($uVip and $uVip instanceof VipUser){
		if(!$uVip->isActif()) {
			$uVip->setActif(true);
			$uVip->enregistrer(array('actif'));
			$urlBack->addParam('mess', 'cptAct');
		}
		else {
			$urlBack->addParam('mess', 'errVerifDejAct');
		}
	}
	else {
		$urlBack->addParam('mess', 'errVerifCpt');
	}
}
else {
	$urlBack->addParam('mess', 'errVerifAct');
}
redirect($urlBack->getUrl());