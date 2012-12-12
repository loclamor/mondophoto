<?php
require_once 'conf/init.php';
echo '<ul>';
$AllPays = GestionPays::getInstance()->getAllPays('nom');

foreach ($AllPays as $pays) {
	if($pays instanceof Pays) {
		$url = new Url();
		$url->addParam('page', 'pays');
		$url->addParam('id', $pays->getId());
		echo '<li><a href="'.$url->getUrl().'">'.$pays->getNom().'</a></li>';
	}
}
echo '</ul>';