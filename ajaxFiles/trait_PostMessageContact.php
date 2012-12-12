<?php
$result = array('res' => 'fail', 'verif' => $_POST['verifBot']);
if(isset($_POST['nom']) and !empty($_POST['nom'])
	and isset($_POST['adresse_mail']) and !empty($_POST['adresse_mail'])
	and isset($_POST['sujet']) and !empty($_POST['sujet'])
	and isset($_POST['text_contact']) and !empty($_POST['text_contact'])
	and isset($_POST['verifBot']) and $_POST['verifBot'] == 'deux')
	 {
	$message = new MessageContact();
	$message->setDate(date('Y-m-d H:i:s',time()));
	$message->setLu(false);
	$message->setNom($_POST['nom']);
	$message->setMail($_POST['adresse_mail']);
	$message->setSujet($_POST['sujet']);
	$message->setMessage($_POST['text_contact']);
	if(isset($_SESSION['vip']['isConnect']) and $_SESSION['vip']['isConnect'] == 'true') {
		$userVip = GestionUsersVip::getInstance()->getUserVip($_SESSION['vip']['id']);
		if($userVip and $userVip instanceof VipUser){
			$message->setIdUser($userVip->getId());
		}
	}
	$id = $message->enregistrer();
	
	if($id) {
		$result['res'] = 'done';
	}
}
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);