<?php
$urlAction = new Url();
$urlAction->addParam('page', 'traitementAjoutVip');
echo '<h2>Ajouter une utilisateur VIP</h2>';
echo '<form class="formAJVip" action="'.$urlAction->getUrl().'" method="POST">';

echo '<label for="nom">Nom : </label><input type="text"  maxlength="255" name="nom" id="nom"/><br/>';
echo '<label for="prenom">Prenom : </label><input type="text"  maxlength="255" name="prenom" id="prenom"/><br/>';
echo '<label for="mail">Mail : </label><input type="text"  maxlength="255" name="mail" id="mail"/><br/>';
echo '<label for="pwd">Mot de passe : </label><input type="text"  maxlength="15" name="pwd" id="pwd"/><br/>';

echo '<input type="submit" value="Valider"/>';
echo '</form>';