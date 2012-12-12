<?php
if(isset($_GET['idContinent']) and !empty($_GET['idContinent']) and $_GET['idContinent'] != 0){
	
	$pays = GestionPays::getInstance()->getPaysContinent($_GET['idContinent']);
	if($pays){
		foreach ($pays as $p){
			if($p instanceof Pays){
				echo '<option value="'.$p->getId().'">'.$p->getNom().'</option>';
			}
		}
	}
	else {
		echo 'false';
	}
}