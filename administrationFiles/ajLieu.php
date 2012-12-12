<?php
if(isset($_GET['idPays']) and !empty($_GET['idPays'])){
	$_POST['idPays'] = $_GET['idPays'];
}

if(isset($_POST['idPays']) and !empty($_POST['idPays'])){
	$idPays = $_POST['idPays'];
	$pays = GestionPays::getInstance()->getPays($idPays);
	echo '<h2>'.$pays->getNom().' : Ajouter un Lieu</h2>';
	$urlAction = new Url();
	$urlAction->addParam('page', 'traitementAjoutLieu');
	$urlAction->addParam('idPays', $idPays);
	echo '<form action="'.$urlAction->getUrl().'" method="POST">';
	echo '<label for="nomLieu">Nom du Lieu: </label><input type="text" size="75" maxlength="255" name="nomLieu" id="nomLieu"/>'
	. '<select name="typeLieu">'
		. '<option value="ville">Ville</option>'
		. '<option value="monument">Monument (créé par l\'Homme)</option>'
		. '<option value="merveille">Merveille (Naturelle)</option>'
	. '</select><br/>';
	echo '<label for="coordLieuX">Coordonnées du Lieu (cliquer sur la carte) : </label><input type="text" size="10" maxlength="10" name="coordLieuX" id="coordLieuX"/><input type="text" size="10" maxlength="10" name="coordLieuY" id="coordLieuY"/><br/>';
	echo '<input type="submit" value="valider"/>';
	echo '</form>';
/*	echo '<img src="'.$pays->getUrlCarte().'" id="admPaysCarte" usemap="#mapPays"/>';
	
	//affichage des Lieux sur la map
	echo '<map name="mapPays" id="mapPays" >';
	$lieux = GestionLieux::getInstance()->getPaysLieux($pays->getId());
	if($lieux){
		foreach ($lieux as $lieu){
			if($lieu instanceof Lieu){
				if($lieu->getCoordonnees()) {
					if($lieu->getType() == 'ville'){
						echo '<area   shape="circle" coords="'.$lieu->getCoordonnees().',5" alt="'.$lieu->getId().'">';
					}
					else {
						echo '<area   shape="circle" coords="'.$lieu->getCoordonnees().',5"  alt="'.$lieu->getId().'" class="'.$lieu->getType().'">';
					}
				}
			}
		}
	}
	
	echo '</map><div id="tooltip"></div>';
	
	*/
	
	$site = new Site(true);
	$ratio = $site->getCartePaysSVG($pays, null, false);
	echo '<div id="svgMapDiv">'.$site->getContent().'</div>';
	echo '<div id="tooltip"></div>';
	
	?>
	<script>
	$(document).ready(function(){
		var newCircle = '<circle alt="0" id="newLieu" stroke-width="0.1px" fill="#00ff00" stroke="black" r="0.55" cy="0" cx="0" class="ville"/>';
		document.getElementById('svgMap').appendChild(parseSVG(newCircle));
		$('svg').offset().left;
		$('svg').offset().top;
	});
	
	$('circle').mouseenter(function(){
		$('#tooltip').html('<img src="style/loader_16.gif" alt="Chargement..."/>');
		$('#tooltip').addClass('tooltipShow');
		$.get('ajax.php?page=getTooltipOnPays&idLieu='+$(this).attr('alt'), function(data) {
			$('#tooltip').html(data + '&nbsp;<img src="style/loader_16.gif" alt="Chargement..."/>');
		});
	});


	$('circle').mouseleave(function(){
		$('#tooltip').removeClass('tooltipShow');
	});

	$("svg").mousemove(function(event) {
		$('#tooltip').css('left',event.pageX + 15);
		$('#tooltip').css('top',event.pageY + 25);
//		$('#tooltip').html(event.pageX-$('svg').offset().left + event.pageY-$('svg').offset().top);
		});

	$('svg').click(function(event){
		$('#coordLieuX').val(((event.pageX-$('svg').offset().left)/<?php echo $ratio;?>+9.5));
		$('#coordLieuY').val(((event.pageY-$('svg').offset().top)/<?php echo $ratio;?>+9.5));
		$('#newLieu').attr('cx',$('#coordLieuX').val());
		$('#newLieu').attr('cy',$('#coordLieuY').val());
		// une seconde fois, sinon le premier clic est mal interprété
		$('#coordLieuX').val(((event.pageX-$('svg').offset().left)/<?php echo $ratio;?>+9.5));
		$('#coordLieuY').val(((event.pageY-$('svg').offset().top)/<?php echo $ratio;?>+9.5));
		$('#newLieu').attr('cx',$('#coordLieuX').val());
		$('#newLieu').attr('cy',$('#coordLieuY').val());
	});
	
	</script>
	
	<?php 
	
}
else {
	//liste des pays
	$pays = GestionPays::getInstance()->getAllPays('nom');
	$urlAction = new Url();
	echo '<h2>Selectionner le pays où ajouter un Lieu :</h2>';
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

?>
<script>
	$(document).ready(function(){
	//	$('#admPaysCarte').offset({ top: 200, left: 100 });
	});
	

</script>