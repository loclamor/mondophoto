<?php

if(isset($_POST['idPays']) and !empty($_POST['idPays'])){
	$idPays = $_POST['idPays'];
	//affichage de l'image du continent
	//champs de texte pour nom Pays
	//pour coord poly
	//champs upload pour img carte
	$pays = GestionPays::getInstance()->getPays($idPays);
	echo '<h2>'.$pays->getNom().' : Modifier</h2>';
	
	$urlAction = new Url();
	$urlAction->addParam('page', 'traitementModificationPays');
	$urlAction->addParam('idPays', $idPays);
	echo '<form action="'.$urlAction->getUrl().'" method="POST" enctype="multipart/form-data" >';
	
	echo '<label for="nomPays">Nom du pays : </label><input type="text" size="75" maxlength="255" name="nomPays" id="nomPays" value="'.$pays->getNom().'"/><br/>';
	echo '<label for="urlOrigineCarte">Url d\'origine de la carte : </label><input type="text" size="75" maxlength="255" name="urlOrigineCarte" id="urlOrigineCarte" value="'.$pays->getUrlCarteFrom().'"/><br/>';
	$size = getimagesize($pays->getUrlCarte());
	echo 'Image actuelle : '.$pays->getUrlCarte().' ('.$size[0].'x'.$size[1].')<br/>';
	echo '<img src="'.$pays->getUrlCarte().'" alt="carte '.$pays->getNom().'" height="200px"/><br/>';
	echo '<label for="cartePays">Nouvelle carte du pays : </label><input type="file" size="75" name="cartePays" id="cartePays"/><br/>';
	
	
	echo '<input type="submit" value="valider"/>';
	echo '</form>';

}
else {
	//liste des pays
	$pays = GestionPays::getInstance()->getAllPays('nom');
	$urlAction = new Url();
	echo '<h2>Selectionner le Pays à modifier :</h2>';
	echo '<form action="'.$urlAction->getUrl().'" method="POST" >';
	echo '<select name="idPays">';
	foreach ($pays as $p) {
		if($p instanceof Pays) {
			echo '<option value="'.$p->getId().'">'.$p->getNom().'</option>';
		}
	}
	echo '</select>';
	echo '<input type="submit" value="valider"/>';
	echo '</form>';
}
