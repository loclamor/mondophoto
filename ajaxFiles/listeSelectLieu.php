<?php
if(isset($_GET['idPays']) and !empty($_GET['idPays']) and $_GET['idPays'] != 0){
	$lieux = GestionLieux::getInstance()->getPaysLieux($_GET['idPays'], 'nom');
	if($lieux){
		foreach ($lieux as $l){
			if($l instanceof Lieu){
				if($l->getCoordonnees() || ($l->getCx() && $l->getCy()))
					echo '<option value="'.$l->getId().'">'.$l->getPronom() . ' ' . firstchartoupper($l->getNom()).'</option>';
			}
		}
	}
}