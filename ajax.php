<?php
require_once 'conf/init.php';

if(isset($_GET['page'])){
	$page = $_GET['page'];
	
	switch($page){
		case 'getTooltipOnContinent':
			require_once 'ajaxFiles/getTooltipOnContinent.php';
			break;
		case 'getTooltipOnPays':
			require_once 'ajaxFiles/getTooltipOnPays.php';
			break;
		case 'listeContinents':
			require_once 'ajaxFiles/listeContinents.php';
			break;
		case 'listeLieuxLiesOnLieu':
			require_once 'ajaxFiles/listeLieuxLiesOnLieu.php';
			break;
		case 'listePays':
			require_once 'ajaxFiles/listePays.php';
			break;
		case 'getVisioneuse':
			require_once 'ajaxFiles/getVisioneuse.php';
			break;
		case 'getNewVisioneuse':
			require_once 'ajaxFiles/getNewVisioneuse.php';
			break;
		case 'traitementUploadPhotoLieu' :
			require_once 'ajaxFiles/trait_UploadPhotoLieu.php';
			break;
		case 'traitementVipUploadPhoto' :
			require_once 'ajaxFiles/trait_VipUploadPhoto.php';
			break;
		case 'traitementResizeImage' :
			require_once 'ajaxFiles/trait_resizeImage.php';
			break;
			
		case 'traitementBackImage' :
			require_once 'ajaxFiles/trait_backImage.php';
			break;
		case 'traitementDeleteImage' :
			require_once 'ajaxFiles/trait_deleteImage.php';
			break;
			
		case 'listeSelectLieu' :
			require_once 'ajaxFiles/listeSelectLieu.php';
			break;
		case 'listeSelectLieuLie' :
			require_once 'ajaxFiles/listeSelectLieuLie.php';
			break;
		case 'listeSelectVille' :
			require_once 'ajaxFiles/listeSelectVille.php';
			break;
		case 'listeSelectPays' :
			require_once 'ajaxFiles/listeSelectPays.php';
			break;
			
		case 'updateVilleCategorie' :
			require_once 'ajaxFiles/updateVilleCateg.php';
			break;
			
		case 'traitementAddLien' :
			require_once 'ajaxFiles/trait_addLien.php';
			break;
		case 'traitementDeleteLien' :
			require_once 'ajaxFiles/trait_deleteLien.php';
			break;
		case 'traitementVerifLien' :
			require_once 'ajaxFiles/trait_verifLien.php';
			break;
		
		case 'traitementLienMort' :
			require_once 'ajaxFiles/trait_lienMort.php';
			break;
		case 'traitementSetLienValide' :
			require_once 'ajaxFiles/trait_setLienValide.php';
			break;
			
		case 'traitementPostMessageContact' :
			require_once 'ajaxFiles/trait_PostMessageContact.php';
			break;
			
		case 'getMessageContact' :
			require_once 'ajaxFiles/getMessageContact.php';
			break;
		
		case 'search' :
			require_once 'ajaxFiles/search.php';
			break;
			
		case 'miniaturize' :
			require_once 'ajaxFiles/miniaturize.php';
			break;
			
		case 'traitementModifVip' :
			require_once 'ajaxFiles/trait_modifVip.php';
			break;
			
		case 'getPhotosLieu' :
			require_once 'ajaxFiles/getPhotosLieu.php';
			break;
	}
	//visualiser le loader une petite seconde...
	//for($i=0;$i<10000000;$i++){}
	
}
else {
	//erreur
	
}