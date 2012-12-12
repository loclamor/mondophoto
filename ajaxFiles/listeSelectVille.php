<?php
if(isset($_GET['idPays']) and !empty($_GET['idPays']) and $_GET['idPays'] != 0){
	$lieux = GestionLieux::getInstance()->getPaysLieuxByType($_GET['idPays'], 'ville', 'nom');
	if($lieux){
		foreach ($lieux as $l){
			if($l instanceof Lieu){
				if($l->getCoordonnees() || ($l->getCx() && $l->getCy()))
					echo '<option value="'.$l->getId().'">'.$l->getNom().'</option>';
			}
		}
	}
}