<?php

require_once 'conf/init.php';

if(isset($_GET['idPays']) and !empty($_GET['idPays'])) {
	$pays = GestionPays::getInstance()->getPays($_GET['idPays']);
	$continent = GestionContinents::getInstance()->getContinent($pays->getIdContinent());
	$color = $continent->getCouleur();
	echo '<span class="pays" style="color:'.$color.';">';
	echo $pays->getNom();
	echo '</span>';
	
}