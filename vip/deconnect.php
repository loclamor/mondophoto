<?php
unset($_SESSION['vip']['isConnect']);
unset($_SESSION['vip']['id']);

$urlAction = new Url(true);
$urlAction->addParam('page', 'accueil');
redirect($urlAction->getUrl());
echo 'Vous avez �t� d�connect�.<br/><a href="'.$urlAction->getUrl().'">retour accueil</a>';