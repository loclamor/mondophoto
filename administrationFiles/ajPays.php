<?php

if(isset($_POST['idContinent']) and !empty($_POST['idContinent'])){
	$idContinent = $_POST['idContinent'];
	//affichage de l'image du continent
	//champs de texte pour nom Pays
	//pour coord poly
	//champs upload pour img carte
	$continent = GestionContinents::getInstance()->getContinent($idContinent);
	echo '<h2>'.$continent->getNom().' : Ajouter un Pays</h2>';
	echo '<img src="'.$continent->getUrlCarte().'" alt="carte '.$continent->getNom().'" height="200px"/>';
	
	$urlAction = new Url();
	$urlAction->addParam('page', 'traitementAjoutPays');
	$urlAction->addParam('idContinent', $idContinent);
	echo '<form action="'.$urlAction->getUrl().'" method="POST" enctype="multipart/form-data" >';
	
	echo '<label for="nomPays">Nom du pays : </label><input type="text" size="75" maxlength="255" name="nomPays" id="nomPays"/><br/>';
	echo '<label for="urlOrigineCarte">Url d\'origine de la carte : </label><input type="text" size="75" maxlength="255" name="urlOrigineCarte" id="urlOrigineCarte"/><br/>';
	echo '<label for="coordPays_0">Coordonnées du polygone : </label><input type="text" size="75" name="coordPays_0" id="coordPays_0"/><br/>';
	
	echo '<div id="poly"></div>';
	echo '<a href="#" id="addCoord">+ ajouter un polygone</a><br/>';
	echo '<input type="hidden" value="0"/ name="nbCoord" id="nbCoord"/>';
	echo '<label for="cartePays">Carte du pays : </label><input type="file" size="75" name="cartePays" id="cartePays"/><br/>';
	
	
	echo '<input type="submit" value="valider"/>';
	echo '</form>';

}
else {
	//liste des continents
	$continents = GestionContinents::getInstance()->getContinents('nom');
	$urlAction = new Url();
	echo '<h2>Selectionner le continent où ajouter un Pays :</h2>';
	echo '<form action="'.$urlAction->getUrl().'" method="POST" >';
	echo '<select name="idContinent">';
	foreach ($continents as $continent) {
		if($continent instanceof Continent) {
			echo '<option value="'.$continent->getId().'">'.$continent->getNom().'</option>';
		}
	}
	echo '</select>';
	echo '<input type="submit" value="valider"/>';
	echo '</form>';
}

?>

<script>
	$("#addCoord").click(function(){
		var nbCoord = $("#nbCoord").val();
		nbCoord++;
		var content = $("#poly").html();
		content = content + '<label for="coordPays_'+nbCoord+'">Coordonnées du polygone : </label><input type="text" size="75" name="coordPays_'+nbCoord+'" id="coordPays_'+nbCoord+'"/><br/>';
		$("#poly").html(content);
		$("#nbCoord").val(nbCoord);
	});

</script>