<h2>Connexion</h2>
<?php
$urlAction = new Url();
//c'est mieux que l'utilisateur connaisse pas la page de traitement de connexion
$urlAction->addParam('page', 'accueil');
$urlAction->addParam('noDisplay', 'true'); 
?>
<form action='<?php echo $urlAction->getUrl();?>' method='POST' >
	
	<input type="password" name="pwd" size="75" /><br/>
	<input type="submit" value="Valider"/>
</form>