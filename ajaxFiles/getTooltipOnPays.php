<?php

require_once 'conf/init.php';

if(isset($_GET['idLieu']) and !empty($_GET['idLieu'])) {
	$lieu = GestionLieux::getInstance()->getLieu($_GET['idLieu']);
	$pays = GestionPays::getInstance()->getPays($lieu->getIdPays());
	$continent = GestionContinents::getInstance()->getContinent($pays->getIdContinent());
	$color = 'black';
	if($lieu->getType() == 'ville'){
		$color = $continent->getCouleur();
	}
	echo '<span class="'.$lieu->getType().'" style="color:'.$color.';">';
	echo $lieu->getPronom() . ' ' . $lieu->getNom();
	echo '</span>';
	
	echo "<script>
$(document).ready(function(){
	if($('#currentLoaded').attr('value') != ".$lieu->getId()."){
		$.get('ajax.php?page=listeLieuxLiesOnLieu&idLieu=".$lieu->getId()."', function(data) {
			$('#tooltip').html(data);
			$('#currentLoaded').attr('value') = ".$lieu->getId().";
		});	
	}
});
</script>";
/*	
	$lieuxLies = GestionLieux::getInstance()->getLieuxLies($lieu->getId(),'nom');
	if ($lieuxLies) {
		echo '<ul>';
		
		foreach ($lieuxLies as $lieu) {
			if($lieu instanceof Lieu) {
				echo '<li>'.$lieu->getNom().'</li>';
			}
		}
		echo '</ul>';
	}
	*/
}