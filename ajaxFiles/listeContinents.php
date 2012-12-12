<?php
require_once 'conf/init.php';
echo '<ul>';
$continents = GestionContinents::getInstance()->getContinents('nom');

foreach ($continents as $continent) {
	if($continent instanceof Continent) {
		$url = new Url();
		$url->addParam('page', 'continent');
		$url->addParam('id', $continent->getId());
		echo '<li><a href="'.$url->getUrl().'">'.$continent->getNom().'</a></li>';
	}
}
echo '</ul>';