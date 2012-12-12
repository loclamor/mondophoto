<?php
$villes = GestionLieux::getInstance()->getLieuxByType('ville','idPays, nom');
echo '<style>
	tr.couleur {
		background-color: #d1d1ff;
	}
	tr:hover {
		background-color: #babaff;
	}
</style>';
echo '<table>';

$cur_idPays = 0;
$numLigne = 0;
foreach ($villes as $ville) {
	$bkg = '';
	if($numLigne % 2 == 0){
		$bkg = ' couleur';
	}
	if($ville instanceof Lieu) {
		if($cur_idPays != $ville->getIdPays()) {
			$pays = GestionPays::getInstance()->getPays($ville->getIdPays());
			echo '<tr><th colspan=3>'.$pays->getNom().'</th></tr>';
			echo '<tr><th>Id</th><th>Nom</th><th>Catégorie</th><th>Etat des liens</th></tr>';
			$cur_idPays = $ville->getIdPays();
		}
		echo '<tr class="'.$bkg.'">';
			echo '<td style="border-bottom: 1px solid black;">'.$ville->getId().'</td>';
			$urlVille = new Url(true);
			$urlVille->addParam('page', 'modificationLieu');
			$urlVille->addParam('idLieu', $ville->getId());
			echo '<td style="border-bottom: 1px solid black;"><a href="'.$urlVille->getUrl().'">'.$ville->getNom().'</a></td>';
			echo '<td style="border-bottom: 1px solid black;">';
				echo '<select class="categVille" id="'.$ville->getId().'">';
					echo '<option '.(($ville->getCategorie()=="1")?'selected':'').' alt="'.$ville->getId().'" value="1">1</option>';
					echo '<option '.(($ville->getCategorie()=="2")?'selected':'').' alt="'.$ville->getId().'" value="2">2</option>';
					echo '<option '.(($ville->getCategorie()=="3")?'selected':'').' alt="'.$ville->getId().'" value="3">3</option>';
					echo '<option '.(($ville->getCategorie()=="4")?'selected':'').' alt="'.$ville->getId().'" value="4">4</option>';
					echo '<option '.(($ville->getCategorie()=="5")?'selected':'').' alt="'.$ville->getId().'" value="5">5</option>';
				echo '</select><span id="load_'.$ville->getId().'"></span>';
			echo '</td>';
			echo '<td style="border-bottom: 1px solid black;">';
				$liens = GestionLiens::getInstance()->getLiensLieu($ville->getId());
				if($liens) {
					echo '<img src="style/check.png"/>&nbsp;';
				}
				$liensInactifs = GestionLiens::getInstance()->getLiensInactifsLieu($ville->getId());
				if($liensInactifs) {
					$nbLiensInactifs = count($liensInactifs);
					if($nbLiensInactifs == 0) {
						echo 'liens OK';
					}
					else {
						if($nbLiensInactifs > 1) {
							echo $nbLiensInactifs . ' liens morts.';
						}
						else {
							echo $nbLiensInactifs . ' lien mort.';
						}
					}
				}
			echo '</td>';
			echo '<td style="border-bottom: 1px solid black;">';
			
				echo '<label for="nomLien_'.$ville->getId().'">Nom : </label>';
				echo '<input type="text" size="15" maxlength="255" id="nomLien_'.$ville->getId().'"/>';
				echo '<label for="urlLien_'.$ville->getId().'">Url : </label>';
				echo '<input type="text" size="30" maxlength="255" id="urlLien_'.$ville->getId().'"/>';
				echo '<select id="typeLien_'.$ville->getId().'">';
					echo '<option value="metro">Métro</option>';
					echo '<option value="plan">Plan</option>';
					echo '<option value="autre">Autre</option>';
				echo '</select>';
				echo '<input type="button" class="btnAddLien" alt="'.$ville->getId().'" value="Ajouter"/>';
				echo '<span id="addLienLoader_'.$ville->getId().'"></span>';
			
			
			echo '</td>';
		echo '</tr>';
	}
	$numLigne++;
}

echo '</table>';

?>
<script>
$('.categVille>option').click(function(){
	$('#load_'+$(this).attr('alt')).html('<img src="style/loader_16.gif"/>');
	$.get('ajax.php?page=updateVilleCategorie&id='+$(this).attr('alt')+'&categ='+$(this).attr('value'), function(data) {
		var result = eval('(' + data + ')');
		if(result.res == 'done') {
			$('#load_'+result.id).html('<img src="style/check.png"/>');
		}
		else {
			$('#load_'+result.id).html('<img src="style/warning.png"/>');
		}
	});
});

$('.btnAddLien').click(function(){
	var nomLien = $('#nomLien_'+$(this).attr('alt')).val();
	$('#nomLien_'+$(this).attr('alt')).attr('disabled', true);
	var urlLien = $('#urlLien_'+$(this).attr('alt')).val();
	$('#urlLien_'+$(this).attr('alt')).attr('disabled', true);
	var typeLien = $('#typeLien_'+$(this).attr('alt')).val();
	$('#typeLien_'+$(this).attr('alt')).attr('disabled', true);
	
	$('#addLienLoader_'+$(this).attr('alt')).html('<img src="style/loader_16.gif"/>');
	$.post("ajax.php?page=traitementAddLien&idLieu="+$(this).attr('alt'), { nom: nomLien, url: urlLien, type: typeLien }, function(data) {
		var result = eval('(' + data + ')');
		if(result.res == "done"){
		//	var nouvLien = '<li id="lien_'+result.id+'">'+result.nom+' - <a href="'+result.url+'" target="_blank">'+result.url+'&nbsp;<input type="image" src="style/delete.png" value="supprimer lien" id="btnDeleteLien_'+result.id+'" class="btnDeleteLien" alt="'+result.id+'"></li>';
		//	$('#liensLieu').append(nouvLien);
			$('#nomLien_'+result.idLieu).val('');
			$('#urlLien_'+result.idLieu).val('');
			$('#typeLien_'+result.idLieu).val('metro');
			$('#addLienLoader_'+result.idLieu).html('<img src="style/check.png"/>');
		}
		else {
			$('#addLienLoader_'+result.idLieu).html('<img src="style/warning.png"/>');
		}
		$('#nomLien_'+result.idLieu).removeAttr('disabled');
		$('#urlLien_'+result.idLieu).removeAttr('disabled');
		$('#typeLien_'+result.idLieu).removeAttr('disabled');
	});
	return false;
});

</script>