<?php

$urlAddVip = new Url(true);
$urlAddVip->addParam('page', 'ajoutVip');
echo "<a href='".$urlAddVip->getUrl()."' class='icon-add-user'>Ajouter un Utilisateur VIP</a>";

$usersVip = GestionUsersVip::getInstance()->getUsersVip();
if($usersVip) {
	?>
	<table>
		<tr><th>Prenom</th><th>Nom</th><th>Mail</th></tr>
	<?php 
	foreach ($usersVip as $uVip) {
		if($uVip instanceof VipUser) {
			?>
			<tr class="vip-container" id="<?php echo $uVip->getId();?>">
				<td class="Prenom"><?php echo $uVip->getPrenom();?></td>
				<td class="Nom"><?php echo $uVip->getNom(); ?></td>
				<td class="Mail"><?php echo $uVip->getMail(); ?></td>
				<td class="Actif" statut="<?php echo ($uVip->isActif()?'actif':'inactif');?>"><img src="style/puce_<?php echo ($uVip->isActif()?'verte':'rouge');?>.png"/></td>
				<td class="Banni" statut="<?php echo ($uVip->isBanni()?'actif':'inactif');?>"><img src="style/<?php echo ($uVip->isBanni()?'warning':'check');?>.png"/></td>
			</tr>
			<?php
		}
	}
	?>
	</table>
	<div class="dialogContainer" style="display: none;">
		<label for="nom">Nom</label><input type="text" id="nom" /><br/>
		<label for="prenom">Prenom</label><input type="text" id="prenom" /><br/>
		<label for="mail">Mail</label><input type="text" id="mail" /><br/>
		<input type="checkbox" id="actif" /><label for="actif"> compte activé</label><br/>
		<input type="checkbox" id="banni" /><label for="banni"> membre banni</label>
		<input type="hidden" id="novip" />
	</div>
	<script>
	$(document).ready(function(){
		$('.vip-container').click(function(){
			
			$('#novip').val($(this).attr('id'));
			$('.dialogContainer').attr('title','Modifier un utilisateur VIP');
			$('#nom').val($(this).children('.Nom').html());
			$('#prenom').val($(this).children('.Prenom').html());
			$('#mail').val($(this).children('.Mail').html());
			if($(this).children('.Actif').attr('statut') == 'actif') {
				$('#actif').attr('checked','checked');
			}
			else {
				$('#actif').removeAttr('checked');
			}
			if($(this).children('.Banni').attr('statut') == 'actif') {
				$('#banni').attr('checked','checked');
			}
			else {
				$('#banni').removeAttr('checked');
			}
			
			$('.dialogContainer').dialog({
				modal: true,
				resizable: false,
				height: 300,
				width: 500,
				buttons: {
					Annuler: function() {
						$( this ).dialog( "close" );
						
					},
					Enregistrer: function() {
						var novip = $('#novip').val();
						var Prenom = $('#prenom').val();
						var Nom = $('#nom').val();
						var Mail = $('#mail').val();
						if($('#actif').attr('checked') == 'checked') {
							var Actif = 1;
						}
						else {
							var Actif = 0;
						}
						if($('#banni').attr('checked') == 'checked') {
							var Banni = 1;
						}
						else {
							var Banni = 0;
						}
						$.post("ajax.php?page=traitementModifVip&idVip="+novip, { nom: Nom, prenom: Prenom, mail: Mail, actif: Actif, banni: Banni }, function(data) {
							var result = eval('(' + data + ')');
							if(result.res == "done"){
								$('#'+result.novip+' .Prenom').html(result.prenom);
								$('#'+result.novip+' .Nom').html(result.nom);
								$('#'+result.novip+' .Mail').html(result.mail);
								if($('#actif').attr('checked') == 'checked') {
									$('#'+result.novip+' .Actif').html('<img src="style/puce_verte.png"/>');
									$('#'+result.novip+' .Actif').attr('statut','actif');
								}
								else {
									$('#'+result.novip+' .Actif').html('<img src="style/puce_rouge.png"/>');
									$('#'+result.novip+' .Actif').attr('statut','inactif');
								}
								if($('#banni').attr('checked') == 'checked') {
									$('#'+result.novip+' .Banni').html('<img src="style/warning.png"/>');
									$('#'+result.novip+' .Banni').attr('statut','actif');
								}
								else {
									$('#'+result.novip+' .Banni').html('<img src="style/check.png"/>');
									$('#'+result.novip+' .Banni').attr('statut','inactif');
								}
								alert('Mis à jour !');

								$('.dialogContainer').dialog( "close" );
							}
							else {
								alert('Erreur lors de la mise à jour !\n'+result.mess);
							}
						});

					}
				}
			});
		});
	});
	</script>
	<?php 
}