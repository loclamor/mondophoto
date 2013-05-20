<?php
require_once 'conf/init.php';
if(!isset($_GET['noDisplay'])){
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>Administration</title>
		<link rel="stylesheet" media="screen" type="text/css" title="style" href="style/style.css" />
		<link href="css/fileuploader.css" rel="stylesheet" type="text/css">	
		<link media="all" type="text/css" href="css/smoothness/jquery-ui-1.8.21.custom.css" rel="stylesheet">
		<script src="js/jquery-1.7.1.min.js" type="text/javascript" language="javascript" ></script>
		<script src="js/jquery-ui-1.8.21.custom.min.js" type="text/javascript" language="javascript" ></script>
		<script src="js/fileuploader.js" type="text/javascript"></script>
		<script src="js/jquery.svg.js" type="text/javascript" language="javascript" ></script>
		<script src="js/jquery.svgdom.js" type="text/javascript" language="javascript" ></script>
		<script src="js/functions.js" type="text/javascript" language="javascript" ></script>
	</head>
	<body>


<?php 
}
//debug($_SERVER,true);
//switch selon la page demand�e
$page = 'accueil';
if(isset($_GET['page'])){
	$page = $_GET['page'];
}
//v�rification de la connection
if(!isset($_SESSION['administration']['isConnect']) or $_SESSION['administration']['isConnect'] != 'true'){
	if(isset($_POST['pwd']) and !empty($_POST['pwd'])) {
		$page = 'traitementConnexion';
	}
	else {
		
		$page = 'connexion';
	}
}
if(!isset($_GET['noDisplay'])) {
	$urlAccueil = new Url(true);
	echo '<h1><a href="'.$urlAccueil->getUrl().'"><img src="./style/back_24.png" alt="retour" title="Retour � l\'accueil de l\'administration">&nbsp;Administration</a></h1>';
}
switch($page){
	case 'connexion':
		require_once 'administrationFiles/connect.php';
		break;
	case 'traitementConnexion':
		require_once 'administrationFiles/trait_connect.php';
		break;
	case 'accueil':
		echo "<ul>";
		$url = new Url();
		$url->addParam('page', 'ajoutPays');
//		echo "<li><a href='".$url->getUrl()."'>Ajouter un Pays</a></li>";

		$url->addParam('page', 'ajoutLieu');
		echo "<li><a href='".$url->getUrl()."'>Ajouter un Lieu � un pays</a></li>";

		$url->addParam('page', 'ajoutLieuSVG');
		echo "<li><a href='".$url->getUrl()."'>Ajouter un Lieu � un pays par triangulation</a></li>";
		
		$url->addParam('page', 'modifLieuSVG');
		echo "<li><a href='".$url->getUrl()."'>Modifier un Lieu sur un pays par triangulation</a></li>";
		
		$url->addParam('page', 'ajoutLieuVille');
		echo "<li><a href='".$url->getUrl()."'>Ajouter un Lieu � une ville</a></li>";

		$url->addParam('page', 'ajoutPhotoLieu');
//		echo "<li><a href='".$url->getUrl()."'>Ajouter une Photo � un lieu</a></li>";

		$url->addParam('page', 'modificationLieu');
		echo "<li><a href='".$url->getUrl()."'>Modifier un Lieu</a></li>";

		$url->addParam('page', 'modificationContinent');
//		echo "<li><a href='".$url->getUrl()."'>Modifier un Continent</a></li>";

		$url->addParam('page', 'modificationPays');
//		echo "<li><a href='".$url->getUrl()."'>Modifier un Pays</a></li>";

		$url->addParam('page', 'testAJSVG');
		echo "<li><a href='".$url->getUrl()."'>Ajouter Pays SVG</a></li>";
		

		
		
		$url->addParam('page', 'gestionVip');
		echo "<li><a href='".$url->getUrl()."'>G�rer les Utilisateur VIP</a></li>";
		
		$url->addParam('page', 'gestionPhotoVip');
		$photosVip = GestionPhotosVip::getInstance()->getPhotosVip();
		$nbPhotosVip = count($photosVip);
		if($nbPhotosVip != 0 && $photosVip) {
			$chaineCount = '( '.$nbPhotosVip.' )';
		}
		else {
			$chaineCount = '';
		}
		echo "<li><a href='".$url->getUrl()."'>Gestion photos VIP ".$chaineCount."</a></li>";
		
		$url->addParam('page', 'newsCreation');
		echo "<li><a href='".$url->getUrl()."'>Creation de news</a></li>";
		
		$url->addParam('page', 'recapVilles');
		$chaineCount = '';
		$liensMorts = GestionLiens::getInstance()->getAllLiensInactifs();
		if($liensMorts){
			$nbLiensMorts = count($liensMorts);
			if($nbLiensMorts > 0) {
				$chaineCount = '( '.$nbLiensMorts.' liens morts )';
			}
		}
		echo "<li><a href='".$url->getUrl()."'>Tableau r�capitulatif des Villes ".$chaineCount."</a></li>";
		
		$url->addParam('page', 'listeMessages');
		$msgNL = GestionMessageContact::getInstance()->getMessagesContactNonLu();
		$nbMsgNL = count($msgNL);
		if($nbMsgNL != 0 && $msgNL) {
			$chaineCount = '( '.$nbMsgNL.' )';
		}
		else {
			$chaineCount = '';
		}
		echo "<li><a href='".$url->getUrl()."'>Messages ".$chaineCount."</a></li>";
		
		$url->addParam('page', 'miniaturize');
		echo "<li><a href='".$url->getUrl()."'>Miniaturiser les images principales</a></li>";
		
		if(GestionMappemondePaysSVG::getInstance()->countMappemondePaysSVG() == 0) {
			$url->addParam('page', 'loadMappemonde');
			echo "<li><a href='".$url->getUrl()."'>Charger la mappemonde</a></li>";
		}
		else {
			$url->addParam('page', 'setOnMappemonde');
			echo "<li><a href='".$url->getUrl()."'>Placer des pays sur la mappemonde</a></li>";
		}
		
		echo "</ul>";
		
		echo 'Server settings :<br/>post_max_size : ' . (ini_get('post_max_size')) . ' - ' . 'upload_max_filesize : ' . (ini_get('upload_max_filesize'));
		
	/*	$conxions = GestionConnexions::getInstance()->getConnexions();
		if($conxions) {
			$conxions = count($conxions);
		}
		else {
			$conxions = 0;
		}
		echo '<p>Nombre de visites : '.$conxions.'</p>';
		*/
		break;
	case 'ajoutPays':
		require_once 'administrationFiles/ajPays.php';
		break;
	case 'traitementAjoutPays':
		require_once 'administrationFiles/trait_ajPays.php';
		break;
	case 'ajoutLieu':
		require_once 'administrationFiles/ajLieu.php';
		break;
	case 'ajoutLieuSVG':
		require_once 'administrationFiles/ajLieuSVG.php';
		break;
	case 'traitementAjoutLieu':
		require_once 'administrationFiles/trait_ajLieu.php';
		break;
	case 'modifLieuSVG':
		require_once 'administrationFiles/modifLieuSVG.php';
		break;
	case 'traitementModifLieuSVG':
		require_once 'administrationFiles/trait_modifLieuSVG.php';
		break;
		
	case 'ajoutPhotoLieu':
		require_once 'administrationFiles/ajPhotoLieu.php';
		break;
	case 'traitementAjoutPhotoLieu':
		require_once 'administrationFiles/trait_ajPhotoLieu.php';
		break;
	case 'ajoutLieuVille':
		require_once 'administrationFiles/ajLieuVille.php';
		break;
	case 'traitementAjoutLieuVille':
		require_once 'administrationFiles/trait_ajLieuVille.php';
		break;
	case 'modificationLieu':
		require_once 'administrationFiles/modifLieu.php';
		break;
	case 'traitementModificationLieu':
		require_once 'administrationFiles/trait_modifLieu.php';
		break;
	case 'modificationPays':
		require_once 'administrationFiles/modifPays.php';
		break;
	case 'traitementModificationPays':
		require_once 'administrationFiles/trait_modifPays.php';
		break;
	case 'modificationContinent':
		require_once 'administrationFiles/modifContinent.php';
		break;
	case 'traitementModificationContinent':
		require_once 'administrationFiles/trait_modifContinent.php';
		break;
		
		
	case 'testAJSVG':
		require_once 'administrationFiles/testAJSVG.php';
		break;
	case 'traitementTestAJSVG':
		require_once 'administrationFiles/trait_testAJSVG.php';
		break;
		
	case 'ajoutVip':
		require_once 'administrationFiles/gestUsers/ajVip.php';
		break;
	case 'traitementAjoutVip':
		require_once 'administrationFiles/gestUsers/trait_ajVip.php';
		break;
		
	case 'gestionPhotoVip':
		require_once 'administrationFiles/gestPhotoVip.php';
		break;
	case 'traitementGestionPhotoVip' :
		require_once 'administrationFiles/trait_gestPhotoVip.php';
		break;
		
	case 'recapVilles':
		require_once 'administrationFiles/recapVilles.php';
		break;
		
	case 'listeMessages' :
		require_once 'administrationFiles/listeMessages.php';
		break;
		
	case 'miniaturize' :
		require_once 'administrationFiles/miniaturize.php';
		break;
		
	case 'getZipVip' :
		require_once 'administrationFiles/getZipVip.php';
		break;
		
	case 'gestionVip' :
		require_once 'administrationFiles/gestionVip.php';
		break;
	case 'instal' :
		require_once 'administrationFiles/instal.php';
		break;
		
	case 'newsCreation' :
		require_once 'administrationFiles/newsCreation.php';
		break;
		
	case 'loadMappemonde' :
		require_once 'administrationFiles/loadMappemonde.php';
		break;
	case 'setOnMappemonde' :
		require_once 'administrationFiles/setOnMappemonde.php';
		break;
}

if(!isset($_GET['noDisplay'])){
?>
	<script>
	$(document).ready(function(){
		$('input[type=submit],input[type=button]').button().removeClass('ui-button ui-widget');
		
	});
	</script>
	</body>
</html>
<?php 
}
require_once 'conf/fini.php';