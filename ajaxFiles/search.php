<?php
$arraySearch = array();

$pronoms = array('le ', 'la ', 'les', 'l\'');
$recherche = $_GET['term'];
trim($recherche);
if(strlen($recherche)>3){
	foreach ($pronoms as $p){
		$pos = strpos($recherche, $p);
		if($pos !== false && $pos <= 2){
			$begin = substr($recherche, 0, 3);
			$end = substr($recherche, 3);
			$begin = str_ireplace($p, " ", $begin);
			$recherche = trim($begin.$end);
		}
	}
	trim($recherche);
}
$_GET['term'] = $recherche;

$lieux = GestionLieux::getInstance()->getLieuxLike(noSpecialChar2($_GET['term']));
if($lieux){
	foreach ($lieux as $lieu){
		if($lieu instanceof Lieu) {
			$arraySearch[] = array('label'=>str_ireplace($_GET['term'], '<strong>'.$_GET['term'].'</strong>', firstchartoupper($lieu->getNom())),'value'=>firstchartoupper($lieu->getNom()));//html_entity_decode
		}
	}
}

echo json_encode($arraySearch);

// [ { "id": "Grus virgo", "label": "Demoiselle Crane", "value": "Demoiselle Crane" } ]