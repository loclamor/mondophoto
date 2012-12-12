<?php
if(isset($_GET['idLieu']) and !empty($_GET['idLieu'])){
	$_POST['chxLieuLie'] = $_GET['idLieu'];
}
	$idLieu=0;
if(isset($_POST['chxLieuLie']) and !empty($_POST['chxLieuLie'])){
	$idLieu = $_POST['chxLieuLie'];
	$lieu = GestionLieux::getInstance()->getLieu($_POST['chxLieuLie']);
	if($lieu) {
		echo '<h2>Modifier : '.$lieu->getPronom().' '.firstchartoupper($lieu->getNom()).'</h2>';
		$urlAction = new Url();
		$urlAction->addParam('page', 'traitementModificationLieu');
		$urlAction->addParam('idLieu', $lieu->getId());
		echo '<form action="'.$urlAction->getUrl().'" method="POST" >';
		//champs de maj du lieu
		echo '<label for="nomLieu">Nom du Lieu : </label>';
		echo '<select name="pronom" id="pronom">';
			echo '<option '.((trim($lieu->getPronom())=="")?'selected':'').' value=""></option>';
			echo '<option '.((trim($lieu->getPronom())=="Le")?'selected':'').' value="Le ">Le</option>';
			echo '<option '.((trim($lieu->getPronom())=="La")?'selected':'').' value="La ">La</option>';
			echo '<option '.((trim($lieu->getPronom())=="Les")?'selected':'').' value="Les ">Les</option>';
			echo '<option '.((trim($lieu->getPronom())=="L'")?'selected':'').' value="L\'">L\'</option>';
		echo '</select><input type="text" size="75" maxlength="255" name="nomLieu" id="nomLieu" value="'.$lieu->getNom().'"/><br/>';
		
		echo '<label for="typeLieu">Type du Lieu : </label>';
		echo '<select name="typeLieu" id="typeLieu">';
			echo '<option '.(($lieu->getType()=="ville")?'selected':'').' value="ville">Ville</option>';
			echo '<option '.(($lieu->getType()=="monument")?'selected':'').' value="monument">Monument (créé par l\'Homme)</option>';
			echo '<option '.(($lieu->getType()=="merveille")?'selected':'').' value="merveille">Merveille (Naturelle)</option>';
		echo '</select><br/>';
		
		echo '<label for="categLieu">Catégorie du Lieu : </label>';
		echo '<select name="categLieu" id="categLieu">';
			echo '<option '.(($lieu->getCategorie()=="1")?'selected':'').' value="1">1</option>';
			echo '<option '.(($lieu->getCategorie()=="2")?'selected':'').' value="2">2</option>';
			echo '<option '.(($lieu->getCategorie()=="3")?'selected':'').' value="3">3</option>';
			echo '<option '.(($lieu->getCategorie()=="4")?'selected':'').' value="4">4</option>';
			echo '<option '.(($lieu->getCategorie()=="5")?'selected':'').' value="5">5</option>';
		echo '</select><br/>';
		
		echo '<h3>Liens liés :</h3>';
		$liens = GestionLiens::getInstance()->getLiensLieu($lieu->getId());
		echo '<ul id="liensLieu" style="list-style-type: none">';
		if($liens) {
			foreach ($liens as $lien) {
				if($lien instanceof Lien) {
					
					$warning = '';
					if(!$lien->isValide()) {
						$warning = '&nbsp;<input type="image" src="style/warning.png" value="Marquer comme valide" id="btnSetLienValide_'.$lien->getId().'" class="btnSetLienValide" alt="'.$lien->getId().'">';
					}
					
					echo '<li id="lien_'.$lien->getId().'" class="linkitem" alt="'.$lien->getId().'" >'
						. $lien->getNom() . ' - <a href="'.$lien->getUrl().'" target="_blank">'
						. $lien->getUrl() . '</a>&nbsp;<input type="image" src="style/delete.png" value="supprimer lien" id="btnDeleteLien_'.$lien->getId().'" class="btnDeleteLien" alt="'.$lien->getId().'">&nbsp;'
						. $warning.'</li>';
				}
			}
		}
		echo '</ul>';
		echo '<label for="nomLien">Nom du Lien : </label>';
		echo '<input type="text" size="35" maxlength="255" id="nomLien"/>';
		echo '<label for="urlLien">Url du Lien : </label>';
		echo '<input type="text" size="35" maxlength="255" id="urlLien"/>';
		echo '<select id="typeLien">';
			echo '<option value="metro">Type plan Métro / Tram</option>';
			echo '<option value="plan">Type Plan de Ville</option>';
			echo '<option value="autre">Type Autre</option>';
		echo '</select>';
		echo '<input type="button" id="btnAddLien" value="Ajouter le lien"/>';
		echo '<span id="addLienLoader"></span>';
		
		echo '<h3>Photos liées :</h3>';
		
		if($lieu->getType() != 'ville') {
			$photos = GestionPhotos::getInstance()->getLieuPhotos($lieu->getId());
			if($photos) {
				$i = 0;
				foreach ($photos as $p) {
					if($p instanceof Photo) {
						$btnResize = '';
						$sizes = getimagesize($p->getUrlPhoto());
						if($sizes[0] > 1000 || $sizes[1] > 1000) {
							$btnResize = '<input type="image" src="style/resize.png" value="redimentionner" id="btnResize_'.$p->getId().'" class="redimBtn" alt="'.$p->getId().'">';
						}
						
						$pathinfos = pathinfo($p->getUrlPhoto());
						$tmp = explode('_',$pathinfos['filename']);
						$origineName = $tmp[0];
						$j = 1;
						while($j < count($tmp)-1) {
							$origineName .= '_' . $tmp[$j];
							$j++;
						}
						if($tmp[count($tmp)-1] == ''){
							$origineName .= '_';
						}
						$btnBack = '';
						$btnDelete = '';
						$origineFile = $pathinfos['dirname'].'/'.$origineName.'.'.$pathinfos['extension'];
						if(is_file($origineFile) && $p->getUrlPhoto() != $origineFile) {
							$btnBack = '<input type="image" src="style/back.png" value="retour image origine" id="btnBack_'.$p->getId().'" class="backBtn" alt="'.$p->getId().'">';
							$btnDelete = '<input type="image" src="style/delete.png" value="supprimer image origine" id="btnDelete_'.$p->getId().'" class="deleteBtn" alt="'.$p->getId().'">';
						}
						
						echo '<fieldset id="fs_'.$p->getId().'">'
						. $btnResize . $btnBack . $btnDelete
						. '<img src="'.$p->getUrlPhoto().'" style="float:left;" width="200px" />'
						. '<input type="hidden" name="idE['.$i.']" id="idE['.$i.']" size="75" value="'.$p->getId().'" />'.$p->getUrlPhoto()
						. '<INPUT TYPE=RADIO NAME="img_ppal" VALUE="E'.$p->getId().'" '.($p->isImagePrincipale()?'CHECKED':'').' >Image principale<br/>'
						. '<label for="nomPhotoE['.$i.']">Nom de la Photo : </label><input type="text" size="75" maxlength="255" name="nomPhotoE['.$i.']" id="nomPhotoE['.$i.']" value="'.$p->getNom().'"/>'
						. '<INPUT TYPE=CHECKBOX NAME="affiche[E'.$i.']" '.($p->isAffiche()?'CHECKED':'').' >afficher <br/>'
						. '<label for="nomPropE['.$i.']">Proprietaire de la Photo : </label><input type="text" size="75" maxlength="255" name="nomPropE['.$i.']" id="nomPropE['.$i.']" value="'.$p->getProprietaire().'"/><br/>'
						. '<label for="dateE['.$i.']">Date de la prise de vue (aaaa-mm-jj) : </label><input type="text" size="10" maxlength="10" name="dateE['.$i.']" id="dateE['.$i.']" value="'.$p->getDatePriseVue().'"/><br/>'
						. '<br/><br/><INPUT TYPE=CHECKBOX NAME="supprimer[E'.$i.']" >supprimer l\'image <br/>'
						. '</fieldset>';
						$i++;
					}
				}
			}
			echo '<div id="uploadedFiles"></div>';
			echo '<div id="file-uploader"></div>';
			echo '<div id="upload-errors"></div>';
		}
		
		echo '<input type="hidden" name="nbUpload" id="nbUpload" size="75" value=0 />';
		echo '<input type="submit" value="Valider" />';
		echo '</form>';
		
		?>
		<script>
		$('.redimBtn').live('click',function(){
			$(this).attr('src','style/loader_16.gif');
			$.get('ajax.php?page=traitementResizeImage&idPhoto='+$(this).attr('alt'),$(this).attr('alt'), function(data) {
				if(data.substr(0,1) == '{') {
					var result = eval('(' + data + ')');
					if(result.res == "true"){
						$('#btnResize_'+result.id).remove();
	
						$('#fs_'+result.id).prepend('<input type="image" src="style/back.png" value="retour image origine" id="btnBack_'+result.id+'" class="backBtn" alt="'+result.id+'">'
						+'<input type="image" src="style/delete.png" value="supprimer image origine" id="btnDelete_'+result.id+'" class="deleteBtn" alt="'+result.id+'">');
						
					}
					else {
						$('#btnResize_'+result.id).attr('src','style/warning.png');
					}
				}
				else {
					$('#btnResize_'+this.data).attr('src','style/warning.png');
				}
				
			});
			return false;
		});
		$('.backBtn').live('click',function(){
			$(this).attr('src','style/loader_16.gif');
			$.get('ajax.php?page=traitementBackImage&idPhoto='+$(this).attr('alt'), function(data) {
				var result = eval('(' + data + ')');
				if(result.res == "true"){
					$('#btnBack_'+result.id).remove();
					$('#btnDelete_'+result.id).remove();
					$('#fs_'+result.id).prepend('<input type="image" src="style/resize.png" value="redimentionner" id="btnResize_'+result.id+'" class="redimBtn" alt="'+result.id+'">');
				}
				else {
				//	$('#btnBack_'+result.id).attr('src','style/back.png');
				}
				
			});
			return false;
		});
		$('.deleteBtn').live('click',function(){
			$(this).attr('src','style/loader_16.gif');
			$.get('ajax.php?page=traitementDeleteImage&idPhoto='+$(this).attr('alt'), function(data) {
				var result = eval('(' + data + ')');
				if(result.res == "true"){
					$('#btnBack_'+result.id).remove();
					$('#btnDelete_'+result.id).remove();
				}
				else {
					$('#btnDelete_'+result.id).attr('src','style/delete.png');
				}
				
			});
			return false;
		});

		//############### les liens
		$(document).ready(function(){
			$('.linkitem').each(function(){
				$('#lien_'+$(this).attr('alt')).prepend('<img src="style/loader_16.gif" id="imgLienValide_'+$(this).attr('alt')+'" alt="lien en validation"/>&nbsp;');
				
				$.get('ajax.php?page=traitementVerifLien&id='+$(this).attr('alt'), function(data) {
					var result = eval('(' + data + ')');
					if(result.res == "done"){
						$('#imgLienValide_'+result.id).attr('src','style/puce_verte.png');
						$('#imgLienValide_'+result.id).attr('alt','lien valide');
					}
					else {
						$('#imgLienValide_'+result.id).attr('src','style/puce_rouge.png');
						$('#imgLienValide_'+result.id).attr('alt','lien non valide');
					}
					
				});
			});
		});
		
		$('#btnAddLien').click(function(){
			var nomLien = $('#nomLien').val();
			$('#nomLien').attr('disabled', true);
			var urlLien = $('#urlLien').val();
			$('#urlLien').attr('disabled', true);
			var typeLien = $('#typeLien').val();
			$('#typeLien').attr('disabled', true);
			
			$('#addLienLoader').html('<img src="style/loader_16.gif"/>');
			$.post("ajax.php?page=traitementAddLien&idLieu=<?php echo $lieu->getId();?>", { nom: nomLien, url: urlLien, type: typeLien }, function(data) {
				var result = eval('(' + data + ')');
				if(result.res == "done"){
					var nouvLien = '<li id="lien_'+result.id+'">'+result.nom+' - <a href="'+result.url+'" target="_blank">'+result.url+'&nbsp;<input type="image" src="style/delete.png" value="supprimer lien" id="btnDeleteLien_'+result.id+'" class="btnDeleteLien" alt="'+result.id+'"></li>';
					$('#liensLieu').append(nouvLien);
					$('#nomLien').val('');
					$('#urlLien').val('');
					$('#typeLien').val('metro');
				}
				$('#addLienLoader').html('');
				$('#nomLien').removeAttr('disabled');
				$('#urlLien').removeAttr('disabled');
				$('#typeLien').removeAttr('disabled');
			});
			return false;
		});
		
		$('.btnDeleteLien').live('click',function(){
			$(this).attr('src','style/loader_16.gif');
			$.get('ajax.php?page=traitementDeleteLien&idLien='+$(this).attr('alt'), function(data) {
				var result = eval('(' + data + ')');
				if(result.res == "done"){
					$('#lien_'+result.id).remove();
				}
				else {
					$('#btnDeleteLien_'+result.id).attr('src','style/delete.png');
				}
				
			});
			return false;
		});

		$('.btnSetLienValide').click(function(){
			$(this).attr('src','style/loader_16.gif');
			$.get('ajax.php?page=traitementSetLienValide&id='+$(this).attr('alt'), function(data) {
				var result = eval('(' + data + ')');
				if(result.res == "done"){
					$('#btnSetLienValide_'+result.id).remove();
				}
				else {
					$('#btnSetLienValide_'+result.id).attr('src','style/warning.png');
				}
				
			});
			return false;
		});
		</script>
		<?php 
	}
	else {
		echo 'erreur : lieu innexistant';		
	}
	$urlAgain = new Url(true);
	$urlAgain->addParam('page', 'modificationLieu');
	echo '<br/><a href="'.$urlAgain->getUrl().'">modifier un autre lieu</a><br/>';
}
else {
	echo '<h2>Selectionner le lieu à modifier : </h2>';
	$urlAction = new Url();
	$urlAction->addParam('page', 'modificationLieu');
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
	echo '<select id="chxLieu" name="chxLieu">';
	echo '</select>';
	echo '<select id="chxLieuLie" name="chxLieuLie">';
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
		$.get('ajax.php?page=listeSelectLieu&idPays='+$(this).attr('value'), function(data) {
			$('#chxLieu').html('<option value="0">Choisir...</option>' + data);
			$('#chxLieuLie').html("");
			$('input').hide();
		});
	});
	$('#chxLieu>option').live('click',function(){
	//	alert ($(this).attr('value'));
		$.get('ajax.php?page=listeSelectLieuLie&idLieu='+$(this).attr('value'), function(data) {
			$('#chxLieuLie').html(data);
			$('input').show();
		});
	});

	
</script>
	<?php
}

$url = new Url(true);
echo '<br/><a href="'.$url->getUrl().'">retour accueil</a>';

?>
 <script>        
    function createUploader(){            
        var uploader = new qq.FileUploader({
	            element: document.getElementById('file-uploader'),
	            action: 'ajax.php?page=traitementUploadPhotoLieu&idLieu=<?php echo $idLieu;?>',
	            debug: true,
	            onComplete: function(id, fileName, responseJSON){
				//	alert("id="+id+"\nfileName="+fileName+"\nresponseJSON="+responseJSON['uploadedFile']);
					var cont = $('#uploadedFiles').html();
					var nbUpload = ($('#nbUpload').attr('value'));
					var nbPhoto = nbUpload;
					nbPhoto++;
					var nouvPhoto = '<fieldset><legend>Nouvelle photo ' + nbPhoto + '</legend>'
					+ '<img src="'+responseJSON['uploadedFile']+'" style="float:left;" width="200px" />'
					+ '<input type="hidden" name="upload['+nbUpload+']" id="upload_'+nbUpload+'" size="75" value="'+responseJSON['uploadedFile']+'" />'+responseJSON['uploadedFile']
					+ '<INPUT TYPE=RADIO NAME="img_ppal" VALUE="N'+nbUpload+'" >Image principale<br/>'
					+ '<label for="nomPhoto['+nbUpload+']">Nom de la Photo : </label><input type="text" size="75" maxlength="255" name="nomPhoto['+nbUpload+']" id="nomPhoto['+id+']"/>'
					+ '<INPUT TYPE=CHECKBOX NAME="affiche[N'+nbUpload+']" >afficher <br/>'
					+ '<label for="nomProp['+nbUpload+']">Proprietaire de la Photo : </label><input type="text" size="75" maxlength="255" name="nomProp['+nbUpload+']" id="nomProp['+nbUpload+']"/><br/>'
					+ '<label for="date['+nbUpload+']">Date de la prise de vue (aaaa-mm-jj) : </label><input type="text" size="10" maxlength="10" name="date['+nbUpload+']" id="date['+nbUpload+']" value="'+responseJSON['datePV']+'"/><br/>'
					+ '<br/><br/><INPUT TYPE=CHECKBOX NAME="supprimer[N'+nbUpload+']" >supprimer l\'image <br/>'
					+ '</fiedset>';
					nbUpload++;
					$('#nbUpload').attr('value',nbUpload);
					$('#uploadedFiles').append(nouvPhoto);
					$('.qq-upload-success').remove();
		        }
	        //	showMessage: function(message){ alert(message); }
        	});           
    }
    $(document).ready(function(){
    	createUploader();
	});
</script>