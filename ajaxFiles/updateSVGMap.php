<?php
if(isset($_GET['idPaysSVG']) and !empty($_GET['idPaysSVG']) and isset($_GET['idPays']) and !empty($_GET['idPays'])){
	$pays = GestionPays::getInstance()->getPays( $_GET['idPays'] );
	$paysMappemonde = GestionMappemondePaysSVG::getInstance()->getPaysSVG( $_GET['idPaysSVG'] );
	
	if( $pays and $paysMappemonde ){
		$paysMappemonde->setIdPays( $pays->getId() );
		$paysMappemonde->enregistrer( array( 'idPays' ) );
		
		echo '{ "idPays": '.$pays->getId().', "idPaysSVG": '.$paysMappemonde->getId().' }';
	}
	else {
		echo '{ error: "pays inexistant" }';
	}
}
else {
	echo '{ error: "erreur de parametre" }';
}