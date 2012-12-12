<?php
if(isset($_POST['nom']) and !empty($_POST['nom']) 
	and isset($_POST['prenom']) and !empty($_POST['prenom'])
	and isset($_POST['pwd']) and !empty($_POST['pwd'])
	and isset($_POST['mail']) and !empty($_POST['mail'])
){
	
	$uv = new VipUser();
	$uv->setNom($_POST['nom']);
	$uv->setPrenom($_POST['prenom']);
	$uv->setPassword(md5($_POST['pwd']));
	$uv->setMail($_POST['mail']);
	$uv->setActif(true);
	$uv->setBanni(false);
	$uv->enregistrer();
	
	$url = new Url(true);
	echo 'Utilisateur VIP enregistré.<br/>';
	echo '<a href="'.$url->getUrl().'">retour accueil</a>';
}
else {
	//une des variable non definie
	$url = new Url(true);
	echo 'Une erreur est survenue. (1)<br/>Enregistrement ignoré.<br/><a href="'.$url->getUrl().'">retour accueil</a>';
	
	debug($_POST);
}