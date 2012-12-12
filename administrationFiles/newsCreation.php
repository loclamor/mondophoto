<style>
	/** style spécifique au news Creator **/
	#formCreatNew { width: 1000px; }
	
	/** reset des div **/
	div { padding: 0px; margin: 0px; }
	
	#formCreatNew .title { width: 100%; } 
	
	#formCreatNew .container { width: 100%; border: 1px solid #A0A0A0; }
	
	#formCreatNew .leftImageContainer { position: relative; height: 500px; float: left; width: 50%; border-right: 1px solid #A0A0A0; }
	#formCreatNew .leftImageContainer .content img { height: 500px; }
	
	#formCreatNew .textContainer { height: 249px; border-bottom: 1px solid #A0A0A0; width: 50%; margin-left: 50%; }
	
	#formCreatNew .bottomImageContainer { position: relative; height: 250px; width: 50%; margin-left: 50%; }
	#formCreatNew .bottomImageContainer .content img { height: 250px; }
	
	#formCreatNew .textOptions { height: 30px; width: 50%; }
	#formCreatNew .textArea { height: 210px; width: 490px; }
	
	#formCreatNew .addImageLink { position: absolute; bottom: 0px; right: 0px; cursor: pointer; padding-left: 26px; background-image: url("./style/add-icon-24.png"); background-repeat: no-repeat; display: table-cell; height: 26px; vertical-align: middle;}
	
	.imgsContainer { height: 161px; width: 566px; overflow: auto; white-space:nowrap; overflow-y: hidden;}
	.imgsContainer .img { margin-left: 4px; cursor: pointer; display: inline-block;}
</style>

<form id="formCreatNew">
	<div class="title">
		<label for="title">Titre : </label><input type="text" id="title" name="title" />
	</div>
	<div class="container" >
		<div class="leftImageContainer">
			<div class="content"></div>
			<span class="addImageLink" >Ajouter une photo...</span>
		</div>
		<div class="textContainer">
			<div class="textOptions"></div>
			<textarea class="textArea">Texte...</textarea>
		</div>
		<div class="bottomImageContainer">
			<div class="content"></div>
			<span class="addImageLink">Ajouter une photo...</span>
		</div>
	</div>
</form>

<div class="dialog-addImage" style="display: none;" title="Ajouter une image">
<?php 
$pays = GestionPays::getInstance()->getAllPays('nom');
echo '<select class="chxPays" name="chxPays" >';
	echo '<option value="0">Choisir...</option>';
foreach ($pays as $p){
	if($p instanceof Pays){
		echo '<option value="'.$p->getId().'">'.$p->getNom().'</option>';
	}
}
echo '</select>';
echo '<select class="chxLieu" id="chxLieu" name="chxLieu" ></select>';
echo '<select class="chxLieuLie" id="chxLieuLie" name="chxLieuLie"></select>';

echo '<input type="submit" id="subbutton" value="Valider" />';
?>
<div class="imgsContainer">

</div>
</div>

<script>
$(document).ready(function(){
	$('#subbutton').hide();
	$('.chxPays>option').click(function(){
	
		$.get('ajax.php?page=listeSelectLieu&idPays='+$(this).attr('value'), function(data) {
			$('#chxLieu').html('<option value="0">Choisir...</option>' + data);
			$('#chxLieuLie').html("");
			$('#subbutton').hide();
		});
	});
	$('.chxLieu>option').live('click',function(){
		$.get('ajax.php?page=listeSelectLieuLie&idLieu='+$(this).attr('value'), function(data) {
			$('#chxLieuLie').html(data);
			$('#subbutton').show();
		});
	});
	$('#subbutton').click(function(){
		$.get('ajax.php?page=getPhotosLieu&idLieu='+$('#chxLieuLie').attr('value'), function(data) {
			var photos = eval('(' + data + ')');
			$('.imgsContainer').html('');
			for(var id in photos) {
				$('.imgsContainer').append('<div class="img" alt="'+id+'"><img src="'+photos[id]+'" alt="'+id+'" height="150px"/></div>');
			}
		});
	});
	$('.img').live('click',function(){
		var parent = $('.dialog-addImage').attr('referer');
		alert(parent);
		$('.'+parent).children('.content').html($(this).html());
		$('.'+parent).children('.content').children('img').removeAttr('height');
		$('.dialog-addImage').dialog("close");
	});
	$('.addImageLink').click(function(){
		var parent = $(this).parent('div').attr('class');
		$('.dialog-addImage').attr('referer',parent);
		$('.dialog-addImage').dialog({
				modal: true,
				resizable: false,
				height: 400,
				width: 600,
				buttons: {
					Annuler: function() {
						$( this ).dialog( "close" );
					}
				}
		});
		$('.dialog-addImage').html();
	});
});
</script>