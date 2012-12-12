<?php
$urlBack = new Url(true);
$urlBack->addParam('page', 'accueil');
if(isset($_POST['nom']) and !empty($_POST['nom'])
	and isset($_POST['prenom']) and !empty($_POST['prenom'])
	and isset($_POST['mail']) and !empty($_POST['mail'])
	and isset($_POST['password']) and !empty($_POST['password'])
	and isset($_POST['accept']) and !empty($_POST['accept'])){

	//création du comtpe VIP
	$uVip = new VipUser();
	$uVip->setActif(false);
	$uVip->setMail($_POST['mail']);
	$uVip->setNom($_POST['nom']);
	$uVip->setPrenom($_POST['prenom']);
	$uVip->setPassword(md5($_POST['password']));

	$id = $uVip->enregistrer();

	$nomComplet = $uVip->getPrenom() . ' ' . $uVip->getNom();
	$mail = $uVip->getMail();
	$pwd = $_POST['password'];

	$chaine = md5("Cette$id chaîne$id n\'a$id aucun$id intérêt$id si$id ce$id n\'est$id perturber$id un$id éventuel$id hackeur$id :)$id");

	//=====Déclaration des messages au format texte et au format HTML
	$message_txt = "Bonjour $nomComplet,\n\nVous venez de créer un compte VIP sur MondoPhoto.fr.\n\nVotre mot de passe est : $pwd\n\nGardez ce mail pour ne pas l'oublier !\n------------------------------\nPour activer votre compte VIP sur MondoPhoto.fr, cliquez sur le lien ci-dessous ou copiez-collez le dans votre navigateur :\nhttp://mondophoto.fr/index.php?page=activate&compte=$id&membre=$chaine\n\nA bientôt sur MondoPhoto.fr !\n\n------------------------------\nCe mail a été envoyé automatiquement, merci de ne pas y répondre.";
	$message_html = "<html><head></head><body><b>Bonjour $nomComplet,</b><br/><br/>Vous venez de créer un compte VIP sur MondoPhoto.fr.<br/><br/>Votre mot de passe est : <b>$pwd</b><br/><br/>Gardez ce mail pour ne pas l'oublier !<hr/>Pour activer votre compte VIP sur MondoPhoto.fr, cliquez sur le lien ci-dessous ou copiez-collez le dans votre navigateur :<br/><a href=\"http://mondophoto.fr/vip.php?page=activate&compte=$id&membre=$chaine\">http://mondophoto.fr/index.php?page=activate&compte=$id&membre=$chaine</a><br/><br/>A bientôt sur MondoPhoto.fr !<br/><hr/>Ce mail a été envoyé automatiquement, merci de ne pas y répondre.</body></html>";
	//==========

	//=====Création de la boundary
	$boundary = "-----=".md5(rand());
	//==========

	//=====Définition du sujet
	$sujet = "Création de votre compte VIP sur MondoPhoto.fr";
	//=========

	//=====Création du header de l'e-mail
	$header = "From: \"MondoPhoto.fr\"<postmaster@mondophoto.fr>\n";
	$header.= "Reply-to: \"MondoPhoto.fr\"<postmaster@mondophoto.fr>\n";
	$header.= "MIME-Version: 1.0\n";
	$header.= "Content-Type: multipart/alternative;\n boundary=\"$boundary\"\n";
	//==========

	//=====Création du message
	$message = "\n--".$boundary."\n";
	//=====Ajout du message au format texte
	$message.="Content-Type: text/plain;\n charset=\"ISO-8859-1\"\n";
	$message.="Content-Transfer-Encoding: 8bit\n";
	$message.="\n".$message_txt."\n";
	//==========
	$message.= "\n--".$boundary."\n";
	//=====Ajout du message au format HTML
	$message.="Content-Type: text/html; charset=\"ISO-8859-1\"\n";
	$message.="Content-Transfer-Encoding: 8bit\n";
	$message.="\n".$message_html."\n";
	//==========
	$message.= "\n--".$boundary."--\n";
	$message.= "\n--".$boundary."--\n";
	//==========

	//=====Envoi de l'e-mail
	
	
	if(mail($mail,$sujet,$message,$header)) {
		$urlBack->addParam('mess', 'confirm');
	}
	else {
		$urlBack->addParam('mess', 'Mail%20non%20envoye%20Contactez%20nous%20avec%20ce%20code%20'.$id.'-'.$chaine);
	}
}
else {
	$urlBack->addParam('page', 'formulaireNewVip');
	$urlBack->addParam('mess', 'errCreationVide');
}
redirect($urlBack->getUrl());
echo $urlBack->getUrl();
debug($_POST);