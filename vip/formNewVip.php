<?php
$urlAction = new Url(true);
$urlAction->addParam('page', 'traitementFormulaireNewVip');
$urlAction->addParam('noDisplay', 'true');
?>
<form class="formNewVip" method="post" action="<?php echo $urlAction->getUrl(); ?>" >
	<p>Pour devenir VIP, veuillez entrer vos informations ci-après :</p>
	<label for="nom">Votre Nom : </label><input type="text" id="nom" name="nom" ><br/>
	<label for="prenom">Votre Prénom : </label><input type="text" id="prenom" name="prenom" ><br/>
	
	<label for="mail">Votre mail : </label><input type="text" id="mail" name="mail" ><br/>
	
	<label for="password">Votre mot de passe : </label><input type="password" id="password" name="password" ><br/>
	<label for="password_2">Retapez votre mot de passe : </label><input type="password" id="password_2" name="password_2" ><span id="passEquals" statut=""></span><br/>
	<input type="checkbox" name="accept" id="accept"> Vous acceptez les <a href='./CGU_MondoPhoto.pdf' target='_blank'>CGU</a>
	<br/><input type="submit" value="Envoyer" id="submit" />
	<br/>Tous les champs sont obligatoires.
	<script>
	$(document).ready(function(){
		$('#submit').attr('disabled','disabled');
		$('#accept').change(function(){
			if($('#submit').attr('disabled') == 'disabled' && $('#passEquals').attr("statut") == "equals"){
				$('#submit').removeAttr('disabled');
			}
			else {
				$('#submit').attr('disabled','disabled');
			}
		});
		$('#password, #password_2').keyup(function(){
			if($('#password').val() != '' && $('#password').val() == $('#password_2').val()){
				$('#passEquals').attr('statut','equals');
				$('#passEquals').html('<img src="style/check.png" />');
				if($('#accept').attr('checked') == 'checked') {
					$('#submit').removeAttr('disabled');
				}
			}
			else {
				$('#passEquals').attr('statut','diff');
				$('#passEquals').html('<img src="style/delete.png" />');
				$('#submit').attr('disabled','disabled');
			}
		});
	});
	</script>
</form>