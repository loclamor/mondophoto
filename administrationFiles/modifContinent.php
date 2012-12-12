<?php

if(isset($_POST['idContinent']) and !empty($_POST['idContinent'])){
	$idContinent = $_POST['idContinent'];
	//affichage de l'image du continent
	//champs de texte pour nom Pays
	//pour coord poly
	//champs upload pour img carte
	$continent = GestionContinents::getInstance()->getContinent($idContinent);
	echo '<h2>'.$continent->getNom().' : Modifier</h2>';
	
	$urlAction = new Url();
	$urlAction->addParam('page', 'traitementModificationContinent');
	$urlAction->addParam('idContinent', $idContinent);
	echo '<form action="'.$urlAction->getUrl().'" method="POST" enctype="multipart/form-data" >';
	
	echo '<label for="nomContinent">Nom du continent : </label><input type="text" size="75" maxlength="255" name="nomContinent" id="nomContinent" value="'.$continent->getNom().'"/><br/>';
	echo '<label for="couleurContinent">Couleur du continent : </label><input type="text" size="75" maxlength="255" name="couleurContinent" id="couleurContinent" value="'.$continent->getCouleur().'"/><br/>';
	echo '<label for="urlOrigineCarte">Url d\'origine de la carte : </label><input type="text" size="75" maxlength="255" name="urlOrigineCarte" id="urlOrigineCarte" value="'.$continent->getUrlCarteFrom().'"/><br/>';
	$size = getimagesize($continent->getUrlCarte());
	echo 'Image actuelle : '.$continent->getUrlCarte().' ('.$size[0].'x'.$size[1].')<br/>';
	
	echo '<img src="'.$continent->getUrlCarte().'" alt="carte '.$continent->getNom().'" height="200px"/><br/>';
	echo '<label for="carteContinent">Nouvelle carte du continent : </label><input type="file" size="75" name="carteContinent" id="carteContinent"/><br/>';
	
	
	echo '<input type="submit" value="valider"/>';
	echo '</form>';

}
else {
	//liste des continents
	$continents = GestionContinents::getInstance()->getContinents('nom');
	$urlAction = new Url();
	echo '<h2>Selectionner le Pays à modifier :</h2>';
	echo '<form action="'.$urlAction->getUrl().'" method="POST" >';
	echo '<select name="idContinent">';
	foreach ($continents as $c) {
		if($c instanceof Continent) {
			echo '<option value="'.$c->getId().'">'.$c->getNom().'</option>';
		}
	}
	echo '</select>';
	echo '<input type="submit" value="valider"/>';
	echo '</form>';
}
