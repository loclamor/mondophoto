<?php

$paysMappemonde = GestionMappemondePaysSVG::getInstance()->getMappemondePaysSVG();
$height = "100%";
$width = "100%";
$viewbox = "10 10 297 170";
echo '<svg id="svg_mappemonde" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="'.$viewbox.'" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" height="'.$height.'" width="'.$width.'" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">';
	echo "<g>";
	foreach ($paysMappemonde as $pays){
		if($pays instanceof MappemondePaysSVG){
			$fillColor = GestionContinents::getInstance()->getContinent(1)->getCouleur();
			$idPays = $pays->getIdPays();
			$ii = $idPays;
			if(!empty($idPays)){
				$fillColor = "white";
			}
			else {
				$idPays = "null";
			}
			echo '<path class="svg_mappemonde_pays" id="'.$pays->getId().'" fill="'.$fillColor.'" idpays="'.$idPays.'" stroke="black" stroke-width="0.1px" class="paysMappemonde" d="'.$pays->getCoordonnees().'" />';
		}
	}
	echo "</g>";
echo "</svg>";
?>
<div id="dialog-modal-pays" title="Pays..." style="display: none;">
	<select id="idPays" name="idPays" >
		
		<option value="null">Pays ind&eacute;termin&eacute;</option>
	<?php 
		$all_pays = GestionPays::getInstance()->getAllPays('idContinent, nom');
		if($all_pays) {
			$curCont = 0;
			foreach ($all_pays as $pays) {
				if($pays instanceof Pays) {
					if($pays->getIdContinent() != $curCont) {
						if($curCont != 0) {
							echo '</optgroup>';
						}
						$curCont = $pays->getIdContinent();
						$cont = GestionContinents::getInstance()->getContinent($curCont);
						echo '<optgroup label="'.$cont->getNom().'">';
					}
					echo '<option value="'.$pays->getId().'">'.$pays->getNom().'</option>';

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
<script>
$(".svg_mappemonde_pays").mousemove(function(e) {
	$(".svg_mappemonde_pays").attr('stroke-width','0.1px');
	$(".svg_mappemonde_pays").attr('stroke','black');
	$(".svg_mappemonde_pays").attr('style','z-index: 0;');

	$(this).attr('stroke-width','0.7px');
	$(this).attr('stroke','rgba(82, 168, 236, 0.8)');
	$(this).attr('style','z-index: 100;');
});
$(".svg_mappemonde_pays").mouseleave(function(){
	$(".svg_mappemonde_pays").attr('stroke-width','0.1px');
	$(".svg_mappemonde_pays").attr('stroke','black');
	$(".svg_mappemonde_pays").attr('style','z-index: 0;');
});

$(".svg_mappemonde_pays").click(function(){
	//if($(this).attr('fill') == 'white')
	{
		$('#currentPathId').attr('value',$(this).attr('id'));
		
		$('#idPays').val($(this).attr('idpays'));
		
		$('#dialog-modal-pays').dialog({height: 250,
			width: 500,
			modal: true,
			resizable: false,
			buttons: {
				Annuler: function() {
					$( this ).dialog( "close" );
					$('#idPays').val('-1');
				},
				Valider: function() {
					// v�rification selection
					if($('#idPays').val() != '-1') {
						if($('#idPays').val() != 'null') {
							//async request to update the database
							$.getJSON('ajax.php?page=updateSVGMap', { "idPaysSVG": $('#currentPathId').attr('value'), "idPays": $('#idPays').val() }, function( data ){
								console.log( data );
								if( data.error ){
									//error on update
									$('#'+data.idPaysSVG).attr('fill','#FF0000');
								}
								else {
									//success
									$('#'+data.idPaysSVG).attr('fill','#FFFFFF');
									$('#'+data.idPaysSVG).attr('idpays',data.idPays);
								}
							});
							$('#'+$('#currentPathId').attr('value')).attr('fill','#D2D2D2');
						}
						else {
							$('#'+$('#currentPathId').attr('value')).attr('fill','<?php echo GestionContinents::getInstance()->getContinent(1)->getCouleur(); ?>');
						}
						
						//alert($('#idPays').val() + " - " + $('#'+$('#currentPathId').attr('value')).attr('idpays'));
						$( this ).dialog( "close" );
						
						$('#idPays').val('-1');
					}
					else {
						alert('Choisissez un pays ou annulez');
					}
				}
			}
		});
	}
})
</script>
