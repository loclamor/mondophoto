<?php
if(!empty($_FILES['cartePays']['size'])
	and isset($_POST['idContinent']) and (int)$_POST['idContinent'] > 0
	and isset($_POST['urlFromCarte']) and !empty($_POST['urlFromCarte'])
	and (
		(isset($_POST['idSelectPays']) and (int)$_POST['idSelectPays'] > 0)
		or (
			isset($_POST['idSelectPays']) and (int)$_POST['idSelectPays'] == 0
			and isset($_POST['nomPays']) and !empty($_POST['nomPays'])
		)
	)
	) {
		
	//traitements creation pays
	if((int)$_POST['idSelectPays'] == 0){
		//en ce cas le pays est inexistant
		$nomPays = $_POST['nomPays'];
		$pays = new Pays();
		$pays->setIdContinent((int)$_POST['idContinent']);
		$pays->setNom($nomPays);
		$pays->setUrlCarteFrom($_POST['urlFromCarte']);
		$pays->enregistrer();
	//	echo 'pays cree';
	}
	else {
		//on récupère le pays existant
		$pays = GestionPays::getInstance()->getPays((int)$_POST['idSelectPays']);
	//	echo 'pays charge';
	}
	
	$nomCarte = noSpecialChar($_FILES['cartePays']['name']);
	$emplacement = 'images/'.$nomCarte;
	move_uploaded_file($_FILES['cartePays']['tmp_name'],$emplacement);
	
//	include $emplacement;
	
	$dom = new DOMDocument();
	if (!$dom->load($emplacement)) {
	    echo "Impossible de charger le fichier";
	}
	
	$newDom = new DOMDocument('1.0','UTF-8');
	$newG1 = $newDom->createElement('g');
	$newG2 = $newDom->createElement('g');
	$gList = $dom->getElementsByTagName("g");
	// le premier G est celui contenant tout le reste
	if($gList->item(0) instanceof DOMNode){
	
		$g = $gList->item(0);
		$gChilds = $g->childNodes;
		$lastPath = null;
		$pathType = 'pays';
		$hasId = false;
		$nbPays = 0;
		$nbVilles = 0;
		foreach ($gChilds as $gc){
			if($gc instanceof DOMNode){
				
				if($gc->nodeName != '#text') {
				//	echo $gc->nodeName . '<br/>';
					$attributes = $gc->attributes;
					
					foreach ($attributes as $attr){
						if($attr instanceof DOMAttr) {
							switch($attr->nodeName) {
								case 'id':
									$hasId = true;
									$id = $attr->nodeValue;
									break;
								case 'd':
									$d = $attr->nodeValue;
									break;
								case 'xlink:href':
									$href = $attr->nodeValue;
									break;
								case 'x':
									$x = $attr->nodeValue;
									break;
								case 'y':
									$y = $attr->nodeValue;
									break;
							}
						}
					}
					switch($gc->nodeName){
						case 'path':
							
							if($pathType == 'pays'){
							//	echo '__pays<br/>';
								if(!is_null($lastPath)){
									$nbPays++;
									$paysSVG = new PaysSVG();
									$paysSVG->setIdPays($pays->getId());
									$paysSVG->setCoordonnees($lastPath->getAttribute('d'));
									$paysSVG->enregistrer();
									
									$lastPath->setAttribute('fill', 'white');
									$lastPath->setAttribute('id', $paysSVG->getId());
									$lastPath->setAttribute('class', 'pays');
									$lastPath->setAttribute('stroke', 'black');
									$newG1->appendChild($lastPath);
									
								}
								$newPath = $newDom->createElement('path');
								$newPath->setAttribute('d', $d);
								$newPath->setAttribute('stroke-width', '0.1px');
								$lastPath = $newPath;
								
							}
							break;
						case 'use':
							if(!is_null($lastPath) and $pathType == 'pays'){
								//c'est les lacs
							//	echo '__lac<br/>';
								$lacs = new LacPaysSVG();
								$lacs->setIdPays($pays->getId());
								$lacs->setCoordonnees($lastPath->getAttribute('d'));
								$lacs->enregistrer();
								
								$lastPath->setAttribute('class', 'lacs');
								$lastPath->setAttribute('fill', 'blue');
								$lastPath->setAttribute('stroke', 'blue');
								$newG1->appendChild($lastPath);
							}
						//	echo '__ville<br/>';
							$nbVilles++;
							//<circle class="ville" cx="" cy="" r="0.55" stroke="black" fill="black" stroke-width="0.1px"/>
							$newPath = $newDom->createElement('circle');
							$newPath->setAttribute('id', 'ville_'.$nbVilles);
							$newPath->setAttribute('class', 'ville');
							$newPath->setAttribute('cx', $x);
							$newPath->setAttribute('cy', $y);
							$newPath->setAttribute('r', '0.55');
							$newPath->setAttribute('stroke', 'black');
							$newPath->setAttribute('fill', 'black');
							$newPath->setAttribute('stroke-width', '0.1px');
							$newG2->appendChild($newPath);
							
							$pathType = 'fleuve';
							
							break;
						case 'g':
						//	echo '__ * G * <br/>';
							$gChildChilds = $gc->childNodes;
							foreach ($gChildChilds as $gcc){
								if($gcc instanceof DOMNode){
									if($gcc->nodeName == 'path') {
										$attributes = $gcc->attributes;
										foreach ($attributes as $attr){
											if($attr instanceof DOMAttr) {
												switch($attr->nodeName) {
													case 'id':
														$hasId = true;
														$id = $attr->nodeValue;
														break;
													case 'd':
														$d = $attr->nodeValue;
														break;
												}
											}
										}
										if(substr($id, 0, 1) == '_') {
										//	echo '____fleuve<br/>';
											$newPath = $newDom->createElement('path');
											$newPath->setAttribute('d', $d);
											$newPath->setAttribute('fill', 'none');
											
											$lacs = new LacPaysSVG();
											$lacs->setIdPays($pays->getId());
											$lacs->setCoordonnees($newPath->getAttribute('d'));
											$lacs->enregistrer();
											
											$newPath->setAttribute('class', 'fleuve');
											$newPath->setAttribute('stroke', 'blue');
											$newPath->setAttribute('stroke-width', '0.1px');
											$newG1->appendChild($newPath);
										}
									}
								}
							}
							break;
					}
				}
			}
		}
	}
	$viewbox = '';
	//dernière chose, on récupère les attributs de l'élément SVG pour conaitre le sens
	$svgList = $dom->getElementsByTagName("svg");
	if($svgList->item(0) instanceof DOMNode){
		$svg = $svgList->item(0);
		$attributes = $svg->attributes;
		foreach ($attributes as $attr){
			if($attr instanceof DOMAttr) {
			//	echo $attr->nodeName . '<br/>';
				if($attr->nodeName == 'viewBox') {
					$viewbox = $attr->nodeValue;
				}
			}
		}
	}
	if($viewbox == '0 0 210 297') {
		$sens = 1;
		$width = '210mm';
		$height = '297mm';
	}
	else {
		$sens = 0;
		$width = '297mm';
		$height = '210mm';
	}
	
	
	$newG1->appendChild($newG2);
	$newDom->appendChild($newG1);
	
	
	$urlAction = new Url();
	$urlAction->addParam('page', 'traitementTestAJSVG');
	$urlAction->addParam('idPays', $pays->getId());
?>


<form action="<?php echo $urlAction->getUrl(); ?>" method="POST" >
	<div id="tabs">
		<ul>
			<li id="1"><a href="#tabs-1">Selection du pays</a></li>
			<li id="2"><a href="#tabs-2">Selection des pays étranger</a></li>
			<li id="3"><a href="#tabs-3">Nomage des villes</a></li>
			<li id="4"><a href="#tabs-4">Validation</a></li>
		</ul>
		<div id="tabs-1">
			<p>Cliquez sur le pays à ajouter</p>
			<input type="hidden" id="idPaysSVG" name="idPaysSVG" />
		</div>
		<div id="tabs-2">
			<p>Cliquez sur le pays étranger à ajouter</p>
			<div id="dialog-modal-stranger" title="Informations sur le pays étranger">
				<select id="idPaysEtranger" name="idPaysEtranger" >
					<option value="-1">Choisir...</option>
				<?php 
					$paysEtrangers = GestionPays::getInstance()->getAllPays('idContinent, nom');
					if($paysEtrangers) {
						$curCont = 0;
						foreach ($paysEtrangers as $pe) {
							if($pe instanceof Pays) {
								if($pe->id != $pays->id) {
									if($pe->getIdContinent() != $curCont) {
										if($curCont != 0) {
											echo '</optgroup>';
										}
										$curCont = $pe->getIdContinent();
										$cont = GestionContinents::getInstance()->getContinent($curCont);
										echo '<optgroup label="'.$cont->getNom().'">';
									}
									echo '<option value="'.$pe->getId().'">'.$pe->getNom().'</option>';
								}
							}
						}
						if($curCont != 0) {
							echo '</optgroup>';
						}
					}
				?>
				</select>
				<input type="hidden" id="currentPathId" value="-1">
			</div>
			<div id="paysEtrangers"></div>
		</div>
		<div id="tabs-3">
			<p>Cliquez sur la ville à ajouter</p>
			<div id="dialog-modal-ville" title="Informations sur la ville">
				<select id="idVille" name="idVille" >
					<option value="-1">Choisir...</option>
					<option value="0">Nouvelle ville :</option>
				<?php 
					$villesExistantes = GestionLieux::getInstance()->getPaysLieuxByType($pays->getId(), 'ville','nom');
					if($villesExistantes) {
						foreach ($villesExistantes as $ville) {
							if($ville instanceof Lieu) {
							
								echo '<option value="'.$ville->getId().'">'.$ville->getNom().'</option>';
							
							}
						}
					}
				?>
				</select>
				<input type="text" id="nomVille" name="nomVille" value="" size="20" maxlength="255"/>
				<input type="hidden" id="currentCircleId" value="-1">
			</div>
			<div id="villes"></div>
		</div>
		<div id="tabs-4">
			<input type="button" value="Valider" id="subButton"/>
		</div>
	</div>
	<input type="hidden" id="currentOnglet" value="1">
</form>
<script>
	$(document).ready(function() {
		//initialisation des elements
		$( "#tabs" ).tabs();
		$('#dialog-modal-stranger').css('display', 'none');
		$('#dialog-modal-ville').css('display', 'none');
		$('#nomVille').attr('disabled', true);

		//gestion des evenements
		$('a').click(function(){
			$('#currentOnglet').attr('value',$(this).parent().attr('id'));
		});
		
		$('.pays').click(function(){
			if($('#currentOnglet').attr('value') == 1) {
				$('.pays').attr('fill','white');
				$('#idPaysSVG').attr('value',$(this).attr('id'));
				$(this).attr('fill','gray');
				$('#tabs').tabs('select','tabs-2');
				$('#currentOnglet').attr('value',2);
			}
			if($('#currentOnglet').attr('value') == 2) {
				//a faire : reinitialisation du dialog
				if($(this).attr('fill') == 'white'){
					$('#currentPathId').attr('value',$(this).attr('id'));
					$('#dialog-modal-stranger').dialog({height: 250,
						width: 500,
						modal: true,
						resizable: false,
						buttons: {
							Annuler: function() {
								$( this ).dialog( "close" );
								$('#idPaysEtranger').val('-1');
							},
							Valider: function() {
								// vérification selection
								if($('#idPaysEtranger').val() != '-1') {
									$('#'+$('#currentPathId').attr('value')).attr('fill','<?php echo GestionContinents::getInstance()->getContinent($pays->getIdContinent())->getCouleur();?>');
									var content = '<input type="hidden" name="idStrangerSVG[]" value="'+$('#currentPathId').attr('value')+'/'+$('#idPaysEtranger').val()+'"/>';
									$('#paysEtrangers').append(content);
									$( this ).dialog( "close" );
									$('#idPaysEtranger>option').filter(function(){
										return $(this).attr('value') == $('#idPaysEtranger').val();
									}).remove();
									$('#idPaysEtranger').val('-1');
								}
								else {
									alert('Choisissez un pays ou annulez');
								}
							}
						}
					});
				}
			}
			
		});
		
		$('.ville').click(function(){
			if($('#currentOnglet').attr('value') == 3) {
				//a faire : reinitialisation du dialog
				if($(this).attr('fill') == 'black'){
					$('#currentCircleId').attr('value',$(this).attr('id'));
					$('#dialog-modal-ville').dialog({height: 250,
						width: 500,
						modal: true,
						resizable: false,
						buttons: {
							Annuler: function() {
								$( this ).dialog( "close" );
								$('#idVille').val('-1');
							},
							Valider: function() {
								// vérification selection
								if($('#idVille').val() != '-1') {
									if($('#idVille').val() != '0') {
										//cas pas nouvelle ville
										$('#'+$('#currentCircleId').attr('value')).attr('fill','<?php echo GestionContinents::getInstance()->getContinent($pays->getIdContinent())->getCouleur();?>');
										var content = '<input type="hidden" name="idVillesSVG[]" value="'+$('#'+$('#currentCircleId').attr('value')).attr('cx')+'/'+$('#'+$('#currentCircleId').attr('value')).attr('cy')+'/:'+$('#idVille').val()+'"/>';
										$('#villes').append(content);
										$( this ).dialog( "close" );
										$('#idVille>option').filter(function(){
											return $(this).attr('value') == $('#idVille').val();
										}).remove();
										$('#idVille').val('-1');
										$('#nomVille').attr('disabled', true);
										$('#nomVille').attr('value','');
										$('#'+$('#currentCircleId')).removeClass('ville');
									}
									else {
										//cas nouvelle ville
										if($('#nomVille').attr('value') != '') {
											$('#'+$('#currentCircleId').attr('value')).attr('fill','<?php echo GestionContinents::getInstance()->getContinent($pays->getIdContinent())->getCouleur();?>');
											var content = '<input type="hidden" name="idVillesSVG[]" value="'+$('#'+$('#currentCircleId').attr('value')).attr('cx')+'/'+$('#'+$('#currentCircleId').attr('value')).attr('cy')+'/'+$('#nomVille').attr('value')+'"/>';
											$('#villes').append(content);
											$( this ).dialog( "close" );
											$('#idVille').val('-1');
											$('#nomVille').attr('disabled', true);
											$('#nomVille').attr('value','');
											$('#'+$('#currentCircleId')).removeClass('ville');
										}
										else {
											alert('Entrez une ville ou annulez');
										}
									}
								}
								else {
									alert('Choisissez une ville ou annulez');
								}
							}
						}
					});
				}
			}
		});
		
		$('#idVille>option').click(function(){
			if( $(this).attr('value') != '-1' && $(this).attr('value') != '0' ) {
				$('#nomVille').attr('value',$(this).html());
				$('#nomVille').attr('disabled', true);
			}
			else {
				$('#nomVille').attr('value','');
				$('#nomVille').removeAttr('disabled');
			}
		});

		$('#subButton').click(function(){
			if($('#idPaysSVG').attr('value') != '') {
				$('form').submit();
			/*	if($('.ville').length > 0){
					alert('Il reste des villes à placer.');
				}
				else {
					alert('ok');
				//	$('form').submit();
				}	*/
			}
			else {
				alert('Le pays de la carte n\'a pas été séléctioné.');
			}
		});
	});
</script>
<?php 
	
	echo '<svg xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="'.$viewbox.'" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" height="'.$height.'" width="'.$width.'" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">'
		. $newDom->saveHTML()
		. '</svg>';

}
else {
	debug($_POST);
	$urlAction = new Url();
	$urlAction->addParam('page', 'testAJSVG');
	echo '<form action="'.$urlAction->getUrl().'" method="POST" enctype="multipart/form-data" >';
	
	echo '<label for="idContinent">Continent du pays : </label><select id="idContinent" name="idContinent">';
	echo '<option value="-1">Choisir...</option>';
	$continents = GestionContinents::getInstance()->getContinents('nom');
	foreach ($continents as $continent) {
		if($continent instanceof Continent) {
			echo '<option value="'.$continent->getId().'">'.$continent->getNom().'</option>';
		}
	}
	echo '</select><br/>';
	
	echo '<label for="cartePays">Carte du pays : </label><input type="file" size="75" name="cartePays" id="cartePays"/><br/>';
	
	echo '<label for="urlFromCarte">Url d\'origine de la carte du pays : </label><input type="text" size="75" maxlength="255" name="urlFromCarte" id="urlFromCarte"/><br/>';
	echo '<label for="idSelectPays">Nom du pays : </label><select id="idSelectPays" name="idSelectPays">';
	echo '</select>';
	echo '<input type="text" size="75" maxlength="255" name="nomPays" id="nomPays"/><br/>';
	
	echo '<input id="submit" type="submit" value="valider"/>';
	echo '</form>';
	?>
	<script>
		$(document).ready(function(){
			$('#nomPays').attr('disabled', true);
		});
		$('#idContinent>option').click(function(){
			$('#idSelectPays').html('');
			$('#nomPays').attr('value','');
			$('#nomPays').attr('disabled', true);
			if ($(this).attr('value') != '-1') {
				$.get('ajax.php?page=listeSelectPays&idContinent='+$(this).attr('value'), function(data) {
					$('#idSelectPays').html('<option value="-1">Choisir...</option>'
							+ '<option value="0">Nouveau pays</option>'
							+ data);
					
				});
			}
			else {
				
			}
		});
		$('#idSelectPays>option').live('click',function(){
			if( $(this).attr('value') != '-1' && $(this).attr('value') != '0' ) {
				$('#nomPays').attr('value',$(this).html());
				$('#nomPays').attr('disabled', true);
			}
			else {
				$('#nomPays').attr('value','');
				$('#nomPays').removeAttr('disabled');
			}
		});

		$('#submit').click(function(){
			$('#nomPays').removeAttr('disabled');
			$('form').submit();
		});

	</script>
	<?php
}