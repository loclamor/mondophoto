<?php
$id = $_GET['idMessage'];
$message = GestionMessageContact::getInstance()->getMessageContact($id);
if($message and $message instanceof MessageContact) {
	$message->setLu();
	$message->enregistrer(array('lu'));
	$vip = "";
	if($message->getIdUser()!=null){
		$vipu = GestionUsersVip::getInstance()->getUserVip($message->getIdUser());
		$vip = "VIP : ".$vipu->getNom().' '.$vipu->getPrenom().' ('.$vipu->getMail().')<br/>';
	}
	echo $vip;
	echo 'De : '.$message->getNom().' ('.$message->getMail().')<br/>';
	echo 'Le : '.$message->getDate().'<br/>';
	echo 'Sujet : '.$message->getSujet().'<br/>';
	echo 'Contenu :<br/>';
	echo html_entity_decode($message->getMessage(),ENT_COMPAT,"ISO-8859-1");
}
else {
	echo 'erreur lors du chargement';
}