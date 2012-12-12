<?php
if(isset($_GET['idPays']) and !empty($_GET['idPays'])){
	$_POST['idPays'] = $_GET['idPays'];
}

if(isset($_POST['idPays']) and !empty($_POST['idPays'])){
	$idPays = $_POST['idPays'];
	$pays = GestionPays::getInstance()->getPays($idPays);
	echo '<h2>'.$pays->getNom().' : Modifier un Lieu</h2>';
	$urlAction = new Url();
	$urlAction->addParam('page', 'traitementModifLieuSVG');
	$urlAction->addParam('idPays', $idPays);
	echo '<form action="'.$urlAction->getUrl().'" method="POST">';
//	echo '<label for="nomLieu">Nom du Lieu: </label><input type="text" size="75" maxlength="255" name="nomLieu" id="nomLieu"/>'
	echo '<label for="nomLieu">Nom du Lieu: </label><select id="idLieu" name = "idLieu">';
	$lieux = GestionLieux::getInstance()->getPaysLieux($idPays,'nom');
	if($lieux) {
		foreach ($lieux as $lieu) {
			if($lieu instanceof Lieu) {
				if($lieu->getCoordonnees() || ($lieu->getCx() && $lieu->getCy()))
					echo '<option value="'.$lieu->getId().'">'.$lieu->getNom().'</option>';
			}
			
		}
	}
	echo '</select><br/>';
	echo 'Sélectionnez une première ville : <span class="ville_1">?</span><input type="hidden" size="10" maxlength="10" name="pt1X" id="pt1X"/><input type="hidden" size="10" maxlength="10" name="pt1Y" id="pt1Y"/><br/>';
	echo 'Sélectionnez une deuxième ville : <span class="ville_2">?</span><input type="hidden" size="10" maxlength="10" name="pt2X" id="pt2X"/><input type="hidden" size="10" maxlength="10" name="pt2Y" id="pt2Y"/><br/>';
//	echo 'Sélectionnez une troisième ville : <span class="ville_3">?</span><input type="hidden" size="10" maxlength="10" name="pt3X" id="pt3X"/><input type="hidden" size="10" maxlength="10" name="pt3Y" id="pt3Y"/><br/>';
	echo 'Distance entre <span class="ville_1">?</span> et <span class="ville_2">?</span> : <input type="text" size="10" maxlength="10" name="dist_0" id="dist_0"/> (km, ex: 109.3)<input type="hidden" size="10" maxlength="10" name="dist_svg_ratio" id="dist_svg_ratio"/><br/>';
	echo 'Distance entre <span class="maVille">?</span> et <span class="ville_1">?</span> : <input type="text" size="10" maxlength="10" name="dist_1" id="dist_1"/> (km)<br/>';
	echo 'Distance entre <span class="maVille">?</span> et <span class="ville_2">?</span> : <input type="text" size="10" maxlength="10" name="dist_2" id="dist_2"/> (km)<br/>';
//	echo 'Distance entre <span class="maVille">?</span> et <span class="ville_3">?</span> : <input type="text" size="10" maxlength="10" name="dist_3" id="dist_3"/> (km)<br/>';
//	echo '<input type="button" value="calculer position" id="calcBtn" /><br/>';
	echo '<input type="text" size="10" maxlength="10" name="coordLieuX" id="coordLieuX"/><input type="text" size="10" maxlength="10" name="coordLieuY" id="coordLieuY"/><br/>';
	echo '<input type="submit" value="valider"/>';
	echo '</form>';
	
	$site = new Site(true);
	$ratio = $site->getCartePaysSVG($pays, null, false);
	echo '<div id="svgMapDiv">'.$site->getContent().'</div>';
	echo '<div id="tooltip"></div>';
	
	?>
	<script>
	$(document).ready(function(){
		var newCircle = '<circle id="c_1" alt="0" stroke-width="0.1px" fill="none" stroke="black" r="0" cy="0" cx="0" />';
		document.getElementById('svgMap').appendChild(parseSVG(newCircle));
		newCircle = '<circle id="c_2" alt="0" stroke-width="0.1px" fill="none" stroke="black" r="0" cy="0" cx="0" />';
		document.getElementById('svgMap').appendChild(parseSVG(newCircle));
	//	newCircle = '<circle id="c_3" alt="0" stroke-width="0.1px" fill="none" stroke="black" r="0" cy="0" cx="0" />';
	//	document.getElementById('svgMap').appendChild(parseSVG(newCircle));
		
		newCircle = '<circle id="c_P" alt="0" style="cursor: pointer;" stroke-width="0.1px" fill="#00FF00" stroke="black" r="0.55" cy="0" cx="0" />';
		document.getElementById('svgMap').appendChild(parseSVG(newCircle));
		newCircle = '<circle id="c_Q" alt="0" style="cursor: pointer;" stroke-width="0.1px" fill="#00FF00" stroke="black" r="0.55" cy="0" cx="0" />';
		document.getElementById('svgMap').appendChild(parseSVG(newCircle));
	});
	
	$('circle').click(function(){
		var x = $(this).attr('cx');
		var y = $(this).attr('cy');
		if($('#pt1X').val() == '') {
			$('#pt1X').val(x);
			$('#pt1Y').val(y);
			$('.ville_1').html($(this).attr('name'));
			$('#c_1').attr('cx',x);
			$('#c_1').attr('cy',y);
		}
		else {
		//	if($('#pt2X').val() == '') {
				$('#pt2X').val(x);
				$('#pt2Y').val(y);
				$('.ville_2').html($(this).attr('name'));
				$('#c_2').attr('cx',x);
				$('#c_2').attr('cy',y);
		/*	}
			else {
				$('#pt3X').val(x);
				$('#pt3Y').val(y);
				$('.ville_3').html($(this).attr('name'));
				$('#c_3').attr('cx',x);
				$('#c_3').attr('cy',y);
			}*/
		}
	});

	$('#idLieu>option').click(function(){
		$('.maVille').html($(this).html());
	});

//	sqrt((x1-x2)²+(y1-y2)²)
	$('#dist_0').keyup(function(){
		var x1 = $('#pt1X').val();
		var y1 = $('#pt1Y').val();
		var x2 = $('#pt2X').val();
		var y2 = $('#pt2Y').val();
		var dist_svg = Math.sqrt((x1-x2)*(x1-x2)+(y1-y2)*(y1-y2));
		var ratio = $('#dist_0').val()/dist_svg;
		$('#dist_svg_ratio').val(ratio);
	});

	$('#dist_1').keyup(function(){
		$('#c_1').attr('r',$('#dist_1').val()/$('#dist_svg_ratio').val());
		defIntersec();
	});

	$('#dist_2').keyup(function(){
		$('#c_2').attr('r',$('#dist_2').val()/$('#dist_svg_ratio').val());
		defIntersec();
	});

/*	$('#dist_3').keyup(function(){
		$('#c_3').attr('r',$('#dist_3').val()/$('#dist_svg_ratio').val());
	//	defIntersec();
	});*/
	
	$('circle').mouseenter(function(){
		$('#tooltip').html('<img src="style/loader_16.gif" alt="Chargement..."/>');
		$('#tooltip').addClass('tooltipShow');
		$.get('ajax.php?page=getTooltipOnPays&idLieu='+$(this).attr('alt'), function(data) {
			$('#tooltip').html(data + '&nbsp;<img src="style/loader_16.gif" alt="Chargement..."/>');
		});
	});

	$('#calcBtn').click(function(){
		defIntersec();
		alert('fait !');
	});
	
	function defIntersec(){
		//1. on pose a = 2(xB - xA), b = 2(yB - yA) et c = (xB - xA)² + (yB - yA)² - R² + r² ;
		//2. on pose delta = (2ac)² - 4(a² + b²)(c² - b²r²) ;
		var xA = parseFloat($('#pt1X').val());
		var xB = parseFloat($('#pt2X').val());
		var yA = parseFloat($('#pt1Y').val());
		var yB = parseFloat($('#pt2Y').val());
		var r = parseFloat($('#c_1').attr('r'));
		var R = parseFloat($('#c_2').attr('r'));
		var a = parseFloat(2*(xB - xA));
		var b = parseFloat(2*(yB - yA));
		var c = parseFloat((xB - xA)*(xB - xA) + (yB - yA)*(yB - yA) - R*R + r*r );
		var delta = parseFloat((2*a*c)*(2*a*c) - 4*(a*a + b*b)*(c*c - b*b*r*r) );

		var xP = parseFloat(xA + (2*a*c - Math.sqrt(delta))/(2*(a*a+b*b)));
		var xQ = parseFloat(xA + (2*a*c + Math.sqrt(delta))/(2*(a*a+b*b)));

		if(b != 0) {
			var yP = parseFloat(yA + (c-a*(xP - xA))/b);
			var yQ = parseFloat(yA + (c-a*(xQ - xA))/b);

			$('#c_P').attr('cx',xP);
			$('#c_P').attr('cy',yP);

			$('#c_Q').attr('cx',xQ);
			$('#c_Q').attr('cy',yQ);
			
		}
		else {
		//	var yP = yA + b/2
		}
	}

	$('#c_P').live('click',function(){
		$('#coordLieuX').val($('#c_P').attr('cx'));
		$('#coordLieuY').val($('#c_P').attr('cy'));
	});

	$('#c_Q').live('click',function(){
		$('#coordLieuX').val($('#c_Q').attr('cx'));
		$('#coordLieuY').val($('#c_Q').attr('cy'));
	});


	$('circle').mouseleave(function(){
		$('#tooltip').removeClass('tooltipShow');
	});

	$("svg").mousemove(function(event) {
		$('#tooltip').css('left',event.pageX + 15);
		$('#tooltip').css('top',event.pageY + 25);
		});

/*	$('svg').click(function(event){
		$('#coordLieuX').val(((event.pageX-$('svg').offset().left)/<?php echo $ratio;?>-1));
		$('#coordLieuY').val(((event.pageY-$('svg').offset().top)/<?php echo $ratio;?>-1));
		$('#c_Q').attr('cx',$('#coordLieuX').val());
		$('#c_Q').attr('cy',$('#coordLieuY').val());
	});*/
	
	</script>
	
	<?php 
	
}
else {
	//liste des pays
	$pays = GestionPays::getInstance()->getAllPays('nom');
	$urlAction = new Url();
	echo '<h2>Selectionner le pays où modifier la position d\'un Lieu :</h2>';
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
