<?php
$urlBack = new Url(true);
$urlBack->addParam('page', 'accueil');
//on recupère le ID compte et la chaine cryptée
$id = $_GET['compte'];
$chaineOrig = $_GET['membre'];
//on vas maintenant vérifier la corélation entre les 2 chaines cryptées
$chaine = md5("Cette$id chaîne$id n\'a$id aucun$id intérêt$id si$id ce$id n\'est$id perturber$id un$id éventuel$id hackeur$id :)$id");
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