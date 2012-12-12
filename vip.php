<?php
require_once 'conf/init.php';
if(!isset($_GET['noDisplay'])){
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>Espace Vip</title>
		<link rel="stylesheet" media="screen" type="text/css" title="style" href="style/style.css" />
		<link href="css/fileuploader.css" rel="stylesheet" type="text/css">	
		<link media="all" type="text/css" href="css/smoothness/jquery-ui-1.8.18.custom.css" rel="stylesheet">
		<script src="js/jquery-1.6.1.min.js" type="text/javascript" language="javascript" ></script>
		<script src="js/jquery-ui-1.8.13.custom.min.js" type="text/javascript" language="javascript" ></script>
		<script src="js/fileuploader.js" type="text/javascript"></script>
		<script src="js/jquery.svg.js" type="text/javascript" language="javascript" ></script>
		<script src="js/jquery.svgdom.js" type="text/javascript" language="javascript" ></script>
		<script src="js/functions.js" type="text/javascript" language="javascript" ></script>
		
		<style type="text/css">
			
			.descmonument { }
			.imgs { height: 121px; width: 720px; overflow: auto; white-space:nowrap; overflow-y: hidden;}
			input[type=file] { height: 50px; }
			
		</style>
	</head>
	<body>
	
	

<?php 
}
//switch selon la page demandée
$page = 'accueil';
if(isset($_GET['page'])){
	$page = $_GET['page'];
}
//vérification de la connection
if(!isset($_SESSION['vip']['isConnect']) or $_SESSION['vip']['isConnect'] != 'true'){
	if(isset($_POST['pwd'])) {
		$page = 'traitementConnexion';
	}
	else {
		// les pages de creation de compte est accessible si on est pas connecté
		$pagesAuto = array('formulaireNewVip', 'traitementFormulaireNewVip', 'activate');
		if(!in_array($page, $pagesAuto)) {
			$page = 'connexion';
		}
		if(!isset($_GET['noDisplay'])) {
			$urlContact = new Url(true,'index.php');
			$urlContact->addParam('page', 'contact');
			$urlBack = new Url(true);
			$urlBack->addParam('page', 'accueil');
			?>
			<div id="vip_header">
				<h1><a href="<?php echo $urlBack->getUrl(); ?>">Espace VIP</a></h1>
			</div>
			<div id="vip_menu">
				<a href='./CGU_MondoPhoto.pdf' target='_blank'>CGU</a> - 
				<a class="contact" href="<?php echo $urlContact->getUrl();?>" target="_blank">Contact</a>
			</div>
			<?php
		}
	}
}
else {
	$uv = GestionUsersVip::getInstance()->getUserVip($_SESSION['vip']['id']);
	if(!isset($_GET['noDisplay'])) {
	// ici on met toutes les données qui vont apparaitre sur chaques pages de l'espace VIP
	// (header, menu, footer...)
	
		$urlDeco = new Url();
		$urlDeco->addParam('page', 'deconnexion');
		$urlAJPhotoAvance = new Url();
		$urlAJPhotoAvance->addParam('page', 'formulaireAjoutPhoto');
		$urlAJPhotoSimple = new Url();
		$urlAJPhotoSimple->addParam('page', 'formulaireAjoutPhotoSimple');
		$urlContact = new Url(true,'index.php');
		$urlContact->addParam('page', 'contact');
		$urlBack = new Url(true);
		$urlBack->addParam('page', 'accueil');
?>
		<div id="vip_header">
			
			<span class="vip_connectedLabel">Connecté en tant que <?php echo ($uv->getNom() . ' ' . $uv->getPrenom()); ?> <a href="<?php echo $urlDeco->getUrl();?>"><img src="style/shutdown.png"></a></span>
			<h1><a href="<?php echo $urlBack->getUrl(); ?>">Espace VIP</a></h1>
		</div>
		<div id="vip_menu">
			<ul>
			
				<li>
					Ajouter des Photos
					<ul>
						<li><a href='<?php echo $urlAJPhotoSimple->getUrl(); ?>'>Ajout simple</a></li>
						<li><a href='<?php echo $urlAJPhotoAvance->getUrl(); ?>'>Ajout avancé</a></li>
					</ul>
				</li>
				<li>Voir mes photos en attente (Bientôt!)</li>
				<li>Modifier mon profil (Bientôt!)</li>
			</ul>
			
			<a href='./CGU_MondoPhoto.pdf' target='_blank'>CGU</a> - 
			<a class="contact" href="<?php echo $urlContact->getUrl();?>" target="_blank">Contact</a>
		
		</div>
		
		
<?php 
	
	}
	
}
if(!isset($_GET['noDisplay'])){
	?>
		<div id='vip_content'>
	<?php
}
switch($page){
	case 'formulaireNewVip':
		require_once 'vip/formNewVip.php';
		break;
	case 'traitementFormulaireNewVip' :
		require_once 'vip/trait_formNewVip.php';
		break;
	case 'activate' :
		require_once 'vip/activateVip.php';
	
	case 'connexion':
		require_once 'vip/connect.php';
		break;
	case 'traitementConnexion':
		require_once 'vip/trait_connect.php';
		break;
	case 'accueil':
		?>
		Bienvenue dans votre espace VIP.<br/>
		<br/>
		Via le menu de gauche, vous pouvez envoyer des photographies, voir vos photos en attentes de validation ou modifier votre profil.<br/>
		<br/>
		En cas de problème, n'hésitez pas à nous <a class="contact" href="<?php echo $urlContact->getUrl();?>" target="_blank">contacter</a>, nous ferons tout notre possible pour résoudre votre problème.<br/>
		<?php
		break;
	
	
	case 'formulaireAjoutPhoto' :
		require_once 'vip/formAjPhoto.php';
		break;
		case 'formulaireAjoutPhotoSimple' :
		require_once 'vip/formAjPhotoSimple.php';
		break;
	case 'traitementFormulaireAjoutPhoto' :
		require_once 'vip/trait_formAjPhoto.php';
		break;
		
	case 'deconnexion' :
		require_once 'vip/deconnect.php';
		break;
		
}

if(!isset($_GET['noDisplay'])){
	
?>
		</div>
		<?php 
		if(isset($_GET['mess'])){
			switch ($_GET['mess']) {
				case 'inactif' :
					$mess = 'Ce compte n\'a pas été activé.';
					break;
				case 'banni' :
					$mess = 'Ce compte est banni.';
					break;
				case 'inconnu':
					$mess = 'Cet identifiant ne correspond à aucun compte.';
					break;
				case 'vide' :
					$mess = 'Un ou plusieur champs vide !';
					break;
				case 'wrongPass' :
					$mess = 'Mot de passe incorrect !';
					break;
				case 'confirm' :
					$mess = 'Un lien de validation d\'inscription vient de vous être envoyé sur l\'adresse mail que vous avez spécifiés.<br/>';
					$mess .= 'Cliquez sur ce lien pour valider votre compte VIP.<br/>';
					$mess .= '<span class="info">L\'email de validation peut mettre un certain temps (10~15 minutes) à vous parvenir...<br/>Soyez patients ;)</span>';
					break;
				case 'errCreationVide' :
					$mess = 'Erreur lors de la création de votre compte VIP : un ou plusieur champs vide.';
					break;
				case 'errVerifAct' :
					$mess = 'La vérification de l\'activation du compte VIP a échouée.';
					break;
				case 'errVerifCpt' :
					$mess = 'Le compte VIP à activer est inexistant.';
					break;
				case 'errVerifDejAct' :
					$mess = 'Ce compte VIP est déjà activé.';
					break;
				case 'cptAct' :
					$mess = 'Compte VIP activé.<br/>Vous pouvez dès maintenant commencer à utiliser votre compte VIP.';
					break;
				default :
					$mess = 'Une erreur est survenue.<br/>'.$_GET['mess'];
			}
			?>
			<script>
			$(function() {
				
				$( "#dialog:ui-dialog" ).dialog( "destroy" );
			
				$( "#dialog-message" ).dialog({
					modal: true,
					resizable: false,
					height: 300,
					width: 500,
					buttons: {
						Ok: function() {
							$( this ).dialog( "close" );
						}
					}
				});
			});
			</script>
			<div id="dialog-message" title="MondoPhoto" style="display: none;">
				<p>
					<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
					<?php echo $mess;?>
				</p>
			</div>
			<?php
		}
		?>
	</body>
</html>
<?php }