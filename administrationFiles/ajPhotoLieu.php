<?php
if(isset($_GET['idLieu']) and !empty($_GET['idLieu'])){
	$_POST['idLieu'] = $_GET['idLieu'];
}

if(isset($_POST['idLieu']) and !empty($_POST['idLieu'])){
	$idLieu = $_POST['idLieu'];
	
	$lieu = GestionLieux::getInstance()->getLieu($idLieu);
	echo '<h2>'.$lieu->getType().' '.$lieu->getNom().' : Ajouter une Photo</h2>';
	
	$urlAction = new Url();
	$urlAction->addParam('page', 'traitementAjoutPhotoLieu');
	$urlAction->addParam('idLieu', $idLieu);
	echo '<form action="'.$urlAction->getUrl().'" method="POST" enctype="multipart/form-data" >';
	
//	echo '<label for="nomPhoto">Nom de la Photo : </label><input type="text" size="75" maxlength="255" name="nomPhoto" id="nomPhoto"/><br/>';
//	echo '<label for="nomProp">Proprietaire de la Photo : </label><input type="text" size="75" maxlength="255" name="nomProp" id="nomProp"/><br/>';
//	echo '<label for="date">Date de la prise de vue (aaaa-mm-jj) : </label><input type="text" size="10" maxlength="10" name="date" id="date"/><br/>';
//	echo '<label for="urlPhoto">Photo : </label><input type="file" size="75" name="urlPhoto" id="urlPhoto"/><br/>';
	echo '<div id="file-uploader"></div>';
	echo '<div id="upload-errors"></div>';
	echo '<div id="uploadedFiles"></div>';
	echo '<input type="hidden" name="nbUpload" id="nbUpload" size="75" value=0 />';
	echo '<input type="submit" value="valider"/>';
	echo '</form>';
	
}
else {
	//liste des lieux
	$lieux = GestionLieux::getInstance()->getLieux('type, nom');
	$urlAction = new Url();
	echo '<h2>Selectionner le lieu où ajouter une Photo :</h2>';
	echo '<form action="'.$urlAction->getUrl().'" method="POST" >';
	echo '<select name="idLieu">';
	$currentType = '';
	foreach ($lieux as $lieu) {
		if($lieu instanceof Lieu) {
			if($lieu->getType() != 'ville') {
				if($lieu->getType() != $currentType){
					$currentType = $lieu->getType();
					echo '<optgroup label="'.$currentType.'">';
				}
				echo '<option value="'.$lieu->getId().'">'.$lieu->getNom().'</option>';
			}
		}
	}
	echo '</select>';
	echo '<input type="submit" value="valider"/>';
	echo '</form>';
}

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
					var nouvPhoto = '<fieldset><legend>Photo ' + nbPhoto + '</legend>'
					+ '<img src="'+responseJSON['uploadedFile']+'" style="float:left;" width="200px" />'
					+ '<input type="hidden" name="upload['+nbUpload+']" id="upload_'+nbUpload+'" size="75" value="'+responseJSON['uploadedFile']+'" />'+responseJSON['uploadedFile']+'<br/>'
					+ '<label for="nomPhoto['+nbUpload+']">Nom de la Photo : </label><input type="text" size="75" maxlength="255" name="nomPhoto['+nbUpload+']" id="nomPhoto['+id+']"/><br/>'
					+ '<label for="nomProp['+nbUpload+']">Proprietaire de la Photo : </label><input type="text" size="75" maxlength="255" name="nomProp['+nbUpload+']" id="nomProp['+nbUpload+']"/><br/>'
					+ '<label for="date['+nbUpload+']">Date de la prise de vue (aaaa-mm-jj) : </label><input type="text" size="10" maxlength="10" name="date['+nbUpload+']" id="date['+nbUpload+']" value="'+responseJSON['datePV']+'"/><br/>'
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