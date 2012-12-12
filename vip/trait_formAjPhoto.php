<?php
//debug($_POST);
//debug($_GET);

$i = 1;

while(isset($_POST['pays_'.$i]) and isset($_POST['ville_'.$i]) and isset($_POST['nomMonument_'.$i])){
	//on crée un nouveau lieu VIP
	//seulement si ce n'est pas le systeme d'ajout simplifié
	$lieuVip = new VipLieu();
	$lieuVip->setIdVip($_SESSION['vip']['id']);
	if(!isset($_GET['simple'])) {
		$lieuVip->setNomLieu($_POST['nomMonument_'.$i]);
		$lieuVip->setNomPays($_POST['pays_'.$i]);
		$lieuVip->setNomVille($_POST['ville_'.$i]);
	}
	else {
		$lieuVip->setNomLieu('[color=red]Ajout simplifié ![/color]');
	}
	$idLieuVip = $lieuVip->enregistrer();
	
	//ensuite on va parcourir les images pour les enregistrer
	if(isset($_POST['imgs_'.$i]) and is_array($_POST['imgs_'.$i])) {
		foreach ($_POST['imgs_'.$i] as $img) {
			$vipPhoto = new VipPhoto();
			$vipPhoto->setIdVipLieu($idLieuVip);
			$vipPhoto->setUrlImage($img);
			$vipPhoto->enregistrer();
			
		//	debug($vipPhoto);
		}
	}
	else {
		echo 'erreur 1.1 sur $'.$i.'-'.$idLieuVip.' : pas d\'images<br/>';
	}

	
	
	$i++;
}

$urlBack = new Url(true);
$urlBack->addParam('page', 'accueil');
echo 'Photographies  enregistrées.<br/><a href="'.$urlBack->getUrl().'">Retour à l\'accueil</a>';
echo "<p>L'équipe MondoPhoto vous remercie pour votre contribution.</p>";