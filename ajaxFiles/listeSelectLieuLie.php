<?php
if(isset($_GET['idLieu']) and !empty($_GET['idLieu']) and $_GET['idLieu'] != 0){
	$lieuP = GestionLieux::getInstance()->getLieu($_GET['idLieu']);
	if($lieuP->getType() != 'ville') {
		echo '<option value="'.$lieuP->getId().'">'.$lieuP->getPronom() . ' ' . firstchartoupper($lieuP->getNom()).'</option>';
	}
	else {
		echo '<option value="'.$lieuP->getId().'">Lieu : '.$lieuP->getPronom() . ' ' . firstchartoupper($lieuP->getNom()).'</option>';
	}
	$lieux = GestionLieux::getInstance()->getLieuxLies($_GET['idLieu'], 'nom');
	if($lieux){
		foreach ($lieux as $l){
			if($l instanceof Lieu){
				echo '<option value="'.$l->getId().'">'.$l->getPronom() . ' ' . firstchartoupper($l->getNom()).'</option>';
			}
		}
	}
}