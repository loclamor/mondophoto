<?php
if(isset($_POST['idPaysSVG']) and !empty($_POST['idPaysSVG'])) {
	// 1- mettre en relation les pays etrangers
	if(isset($_POST['idStrangerSVG'])) {
		//tableau de pays etranger
		foreach ($_POST['idStrangerSVG'] as $strangerSVG) {
			$temp = explode('/', $strangerSVG);
			$idPath = $temp[0];
			$idPays = $temp[1];
			$paysPath = new PaysSVG($idPath);
			$paysPath->setIdPaysStranger($idPays);
			$paysPath->enregistrer(array('idStranger'));
		}
	}
	if(isset($_POST['idStrangerSVG[]'])) {
		//un seul pays etranger
		$temp = explode('/',$_POST['idStrangerSVG[]']);
		$idPath = $temp[0];
		$idPays = $temp[1];
		$paysPath = new PaysSVG($idPath);
		$paysPath->setIdPaysStranger($idPays);
		$paysPath->enregistrer(array('idStranger'));
	}
	// 2- ensuite les villes
	if(isset($_POST['idVillesSVG'])) {
		//plusieurs villes
		foreach ($_POST['idVillesSVG'] as $villeSVG) {
			$temp = explode('/', $villeSVG);
			$cx = $temp[0];
			$cy = $temp[1];
			$idVille = $temp[2];
			if(substr($idVille,0,1) == ':'){
				$ville = new Lieu(substr($idVille,1));
				$ville->setCx($cx);
				$ville->setCy($cy);
				$ville->enregistrer(array('cx','cy'));
			}
			else {
				$ville = new Lieu();
				$ville->setIdPays($_GET['idPays']);
				$ville->setNom($idVille);
				$ville->setType('ville');
				$ville->setCx($cx);
				$ville->setCy($cy);
				$ville->enregistrer();
			}
		}
	}
	if(isset($_POST['idVillesSVG[]'])) {
		//une seule ville ?
		$temp = explode('/',$_POST['idVillesSVG[]']);
		$cx = $temp[0];
		$cy = $temp[1];
		$idVille = $temp[2];
		if(substr($idVille,0,1) == ':'){
			$ville = new Lieu(substr($idVille,1));
			$ville->setCx($cx);
			$ville->setCy($cy);
			$ville->enregistrer(array('cx','cy'));
		}
		else {
			$ville = new Lieu();
			$ville->setIdPays($_GET['idPays']);
			$ville->setNom($idVille);
			$ville->setType('ville');
			$ville->setCx($cx);
			$ville->setCy($cy);
			$ville->enregistrer();
		}
	}
	
	//3- enfin on associe le pays
	$paysPath = new PaysSVG($_POST['idPaysSVG']);
	$paysPath->setIdPaysStranger($_GET['idPays']);
	$paysPath->enregistrer(array('idStranger'));
	
	$url = new Url(true);
	echo 'Enregistré.<br/><a href="'.$url->getUrl().'">retour accueil</a>';
}