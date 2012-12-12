<?php
if(isset($_GET['idVille']) and !empty($_GET['idVille'])){
	$_POST['idVille'] = $_GET['idVille'];
}

if((isset($_POST['idVille']) and !empty($_POST['idVille']))){
	$idVille = $_POST['idVille'];
	
	$ville = GestionLieux::getInstance()->getLieu($idVille);
	echo '<h2>'.$ville->getNom().' : Ajouter un Lieu</h2>';
	
	$urlAction = new Url();
	$urlAction->addParam('page', 'traitementAjoutLieuVille');
	$urlAction->addParam('idVille', $idVille);
	echo '<form action="'.$urlAction->getUrl().'" method="POST" >';
	
	echo '<label for="nomLieu">Nom du Lieu : </label>';
	
	echo '<select name="pronom" id="pronom">';
		echo '<option value=""></option>';
		echo '<option value="Le ">Le</option>';
		echo '<option value="La ">La</option>';
		echo '<option value="Les ">Les</option>';
		echo '<option value="L\'">L\'</option>';
	echo '</select><input type="text" size="75" maxlength="255" name="nomLieu" id="nomLieu"/><br/>';
	
	echo '<label for="type">Type du Lieu : </label><select name="type" id="type">';
		echo '<option value="monument">Monument (créé par l\'Homme)</option>';
		echo '<option value="merveille">Merveille (Naturelle)</option>';
	echo '</select>';
	
	echo '<input type="submit" value="valider"/>';
	echo '</form>';
	
	echo '<h2>'.$ville->getNom().' : Lieux existants :</h2>';
	$lieux = GestionLieux::getInstance()->getLieuxLies($ville->getId(),'nom');
	if($lieux){
		echo '<ul>';
		foreach ($lieux as $l) {
			if($l instanceof Lieu) {
				echo '<li>'.$l->getPronom().' '.$l->getNom().'</li>';
			}
		}
		echo '</ul>';
	}
	else {
		echo 'pas de lieux déjà liés';
	} 
	
}
else {
	//liste des lieux
/*	$lieux = GestionLieux::getInstance()->getLieuxByType('ville','nom');
	$urlAction = new Url();
	echo '<h2>Selectionner la ville où ajouter un Lieu :</h2>';
	echo '<form action="'.$urlAction->getUrl().'" method="POST" >';
	echo '<select name="idVille">';
	foreach ($lieux as $lieu) {
		if($lieu instanceof Lieu) {

			echo '<option value="'.$lieu->getId().'">'.$lieu->getNom().'</option>';
		}
	}
	echo '</select>';
	echo '<input type="submit" value="valider"/>';
	echo '</form>';
*/
	echo '<h2>Selectionner la ville où ajouter un Lieu : </h2>';
	$urlAction = new Url();
	echo '<form action="'.$urlAction->getUrl().'" method="POST" >';
	
	$pays = GestionPays::getInstance()->getAllPays('nom');
	echo '<select id="chxPays" name="chxPays">';
		echo '<option value="0">Choisir...</option>';
	foreach ($pays as $p){
		if($p instanceof Pays){
			echo '<option value="'.$p->getId().'">'.$p->getNom().'</option>';
		}
	}
	echo '</select>';
	echo '<select id="idVille" name="idVille">';
	echo '</select>';

	echo '<input type="submit" value="Valider" />';
	echo '</form>';	
	
	?>
	<script>
		$(document).ready(function(){
			$('input').hide();
		});
		$('#chxPays>option').click(function(){
		//	alert ($(this).attr('value'));
			$.get('ajax.php?page=listeSelectVille&idPays='+$(this).attr('value'), function(data) {
				$('#idVille').html(data);
				$('input').show();
			});
		});

	</script>
	<?php
	
}

?>
<script>

</script>