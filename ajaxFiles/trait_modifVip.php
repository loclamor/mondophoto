<?php
$result = array('res' => 'fail', 'mess' => 'RaS');
if(isset($_POST['nom']) and !empty($_POST['nom'])
	and isset($_POST['prenom']) and !empty($_POST['prenom'])
	and isset($_POST['mail']) and !empty($_POST['mail'])
	and isset($_POST['actif'])
	and isset($_POST['banni'])
	and isset($_GET['idVip']) and !empty($_GET['idVip'])) {
	
	//TODO !!
	$uVip = GestionUsersVip::getInstance()->getUserVip($_GET['idVip']);
	if($uVip and $uVip instanceof VipUser) {
		$uVip->setActif(($_POST['actif']==1));
		$uVip->setBanni(($_POST['banni']==1));
		$uVip->setNom(htmlentities($_POST['nom'],ENT_QUOTES,"UTF-8"));
		$uVip->setPrenom(htmlentities($_POST['prenom'],ENT_QUOTES,"UTF-8"));
		$uVip->setMail($_POST['mail']);
		$res = $uVip->enregistrer(array('nom','prenom','mail','actif','banni'));
		
		$result['novip'] = $uVip->getId();
		$result['nom'] = html_entity_decode($uVip->getNom(),ENT_QUOTES,"UTF-8");
		$result['prenom'] = html_entity_decode($uVip->getPrenom(),ENT_QUOTES,"UTF-8");
		$result['mail'] = $uVip->getMail();
		$result['actif'] = ($uVip->isActif()?1:0);
		$result['banni'] = ($uVip->isBanni()?1:0);
		
		if($res) {
			$result['res'] = 'done';
			
		}
		else {
			$result['mess'] = 'Erreur enregistrement';
		}
	}
	else {
		$result['mess'] = 'Erreur membre inconnu';
	}
}
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);