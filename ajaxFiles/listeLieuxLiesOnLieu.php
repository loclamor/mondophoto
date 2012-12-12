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
	echo $lieu->getPronom().' '.$lieu->getNom();
	echo '</span>';
	
	
	$lieuxLies = GestionLieux::getInstance()->getLieuxLies($lieu->getId(),'nom');
	if ($lieuxLies) {
		echo '<ul>';
		
		foreach ($lieuxLies as $lieu) {
			if($lieu instanceof Lieu) {
				
				echo '<li>'.$lieu->getPronom().' '.firstchartoupper($lieu->getNom()).'</li>';
			}
		}
		echo '</ul>';
	}
//	for($i=0;$i<10000000;$i++){}
	
}