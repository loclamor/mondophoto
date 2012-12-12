<?php 
$urlAction = new Url();
$urlAction->addParam('page', 'traitementFormulaireAjoutPhoto');
$urlAction->addParam('simple', 'simple');

?>
		<h2>Ajout de photo simple</h2>

		<form action="<?php echo $urlAction->getUrl();?>" method="POST" >
			<input type="hidden" id="nbMonuments" name="nbMonuments" value="1" />
			<div id='newMonuments'>
				<input type='hidden' class='pays_name' size='35px' name='pays_1' id='pays_1' value="simple">
				<input type='hidden' class='ville_name' size='35px' name='ville_1' id='ville_1' value="simple">
				<input type='hidden' class='lieu_name' size='75px' name='nomMonument_1' id='nomMonument_1' value="simple">
				<div id='file-uploader_1' ></div>
				<div class='imgs'></div>
				<input type='hidden' id='nbImgs_1' name='nbImgs_1' value='0' >
			</div>
			<input type="submit" value="Envoyer" />
		</form>
		
		Vous pouvez ajouter autant de photos que vous le désirez.
		<br/>
		Quand tous les transferts de fichiers sont terminés, cliquez sur "Envoyer" pour valider l'ajout de vos Photos.<br/>
		<br/>
		<script>      
		  
    function createUploader(){            
        var uploader = new qq.FileUploader({
	            element: document.getElementById('file-uploader_'+$('#nbMonuments').val()),
	            action: 'ajax.php?page=traitementVipUploadPhoto&idUp='+$('#nbMonuments').val(),
	            debug: true,
	            onComplete: function(id, fileName, responseJSON){
            
				//	alert("id="+id+"\nfileName="+fileName+"\nresponseJSON="+responseJSON['uploadedFile']);
				//	alert(responseJSON['idUp']);
					$('.imgs').append('<img src="'+responseJSON['uploadedFile']+'" height="100px" />&nbsp;');
					$('#newMonuments').append('<input type="hidden" name="imgs_'+responseJSON['idUp']+'['+$('#nbImgs_'+responseJSON['idUp']).val()+']" value="'+responseJSON['uploadedFile']+'"/>');
					$('#nbImgs_'+responseJSON['idUp']).val(parseInt($('#nbImgs_'+responseJSON['idUp']).val())+1);
					$('.qq-upload-success').remove();
		        }
	        //	showMessage: function(message){ alert(message); }
        	});           
    }
    $(document).ready(function(){
    	createUploader();
    	
	});

		</script>