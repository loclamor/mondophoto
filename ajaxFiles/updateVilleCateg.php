<?php
$ville = GestionLieux::getInstance()->getLieu($_GET['id']);
$result = array('res' => 'fail', 'id' => $_GET['id']);
if($ville instanceof Lieu){
	if(isset($_GET['categ'])){
		$ville->setCategorie($_GET['categ']);
		$ville->enregistrer(array('categorie'));
		$result['res'] = 'done';
	}
}
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);