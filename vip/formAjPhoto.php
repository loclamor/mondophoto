<?php 
$urlAction = new Url();
$urlAction->addParam('page', 'traitementFormulaireAjoutPhoto');

?>
		<h2>Ajout de photo avancé</h2>
		<button id='button'>Ajouter un Lieu</button>
		<form action="<?php echo $urlAction->getUrl();?>" method="POST" >
			<input type="hidden" id="nbMonuments" name="nbMonuments" value="0" />
			<div id='newMonuments'>
				
			</div>
			<input type="submit" value="Envoyer" />
		</form>
		Cliquez sur "Ajouter un Lieu" pour commencer.<br/>
		<br/>
		Nommez ce lieu, en précisant sa localisation, puis commencez à envoyer des photographies de ce lieu en cliquant sur le bouton "Envoyer un fichier" présent dans le cadre du lieu.<br/>
		<br/>
		Vous pouvez ajouter autant de lieu et autant de photos par lieu que vous le désirez.
		<br/>
		Les champs "Pays", "Ville" et "Nom du Lieu" ne sont pas obligatoire, mais ils permettent aux administrateur de retrouver plus facilement le lieu que vous ajoutez, et ainsi faciliter son intégration au site.<br/>
		<br/>
		Quand tous les transferts de fichiers sont terminés, cliquez sur "Envoyer" pour valider l'ajout de vos Lieux et Photos.<br/>
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
					$('#fs_'+responseJSON['idUp']+' .imgs').append('<img src="'+responseJSON['uploadedFile']+'" height="100px" />&nbsp;');
					$('#fs_'+responseJSON['idUp']).append('<input type="hidden" name="imgs_'+responseJSON['idUp']+'['+$('#nbImgs_'+responseJSON['idUp']).val()+']" value="'+responseJSON['uploadedFile']+'"/>');
					$('#nbImgs_'+responseJSON['idUp']).val(parseInt($('#nbImgs_'+responseJSON['idUp']).val())+1);
					$('.qq-upload-success').remove();
		        }
	        //	showMessage: function(message){ alert(message); }
        	});           
    }
    $('#button').click(function(){
    	$('#nbMonuments').val(parseInt($('#nbMonuments').val())+1);
		var content = "<fieldset id='fs_"+$('#nbMonuments').val()+"'><legend></legend><div class='descmonument'>"
		+	"Pays <input type='text' class='pays_name' size='35px' name='pays_"+$('#nbMonuments').val()+"' id='pays_"+$('#nbMonuments').val()+"' > Ville <input type='text' class='ville_name' size='35px' name='ville_"+$('#nbMonuments').val()+"' id='ville_"+$('#nbMonuments').val()+"' ><br/>"
		+	"Nom du Lieu <input type='text' class='lieu_name' size='75px' name='nomMonument_"+$('#nbMonuments').val()+"' id='nomMonument_"+$('#nbMonuments').val()+"'><br/>"
		+	"<div id='file-uploader_"+$('#nbMonuments').val()+"' ></div>"
		+	"</div><div class='imgs'></div>"
		+	"<input type='hidden' id='nbImgs_"+$('#nbMonuments').val()+"' name='nbImgs_"+$('#nbMonuments').val()+"' value='0' ></fieldset>";
        $('#newMonuments').append(content);
    	createUploader();
    	
	});
	$('input').live('keyup',function(){
		
		$(this).parent('.descmonument').siblings('legend').html($(this).parent('.descmonument').children('.pays_name').val()+' : '+$(this).parent('.descmonument').children('.ville_name').val()+' : '+$(this).parent('.descmonument').children('.lieu_name').val());
	});
		</script>