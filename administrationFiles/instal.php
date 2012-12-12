<?php

$req = "ALTER TABLE `vip_user` 
ADD `mail` VARCHAR( 255 ) NOT NULL DEFAULT 'null',
ADD `actif` TINYINT NOT NULL DEFAULT '1',
ADD `banni` TINYINT NOT NULL DEFAULT '0';";
SQL::getInstance()->exec($req);
$req = "ALTER TABLE `vip_user` 
CHANGE `password` `password` VARCHAR( 255 ) NOT NULL ";
SQL::getInstance()->exec($req);
$req = "ALTER TABLE `vip_user` CHANGE `nom` `nom` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `prenom` `prenom` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `password` `password` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `mail` `mail` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'null';";

//mise à jour pour avoir les mdp en md5
$membres = GestionUsersVip::getInstance()->getUsersVip();
if($membres) {
	foreach ($membres as $uVip) {
		if($uVip instanceof VipUser){
			$uVip->setPassword(md5($uVip->getPassword()));
			$uVip->enregistrer(array('password'));
		}
	}
}
echo 'mise à jour effectuée.';