<h2>Connexion</h2>
<?php
$urlAction = new Url(true);
//c'est mieux que l'utilisateur connaisse pas la page de traitement de connexion
$urlAction->addParam('page', 'accueil');
$urlAction->addParam('noDisplay', 'true'); 

$urlNewVip = new Url(true);
$urlNewVip->addParam('page', 'formulaireNewVip');

?>
<form class="formConnect" action='<?php echo $urlAction->getUrl();?>' method='POST' >
	<label for="user">Utilisateur (mail) : </label>
	<input type="text" name="user" id="user"  /><br/>
	<label for="pwd">Mot de passe : </label>
	<input type="password" name="pwd" id="pwd"  /><br/>
	<input type="submit" value="Valider"/>
	Pas de compte VIP ? <a href="<?php echo $urlNewVip->getUrl(); ?>">Créez en un</a> !
</form>