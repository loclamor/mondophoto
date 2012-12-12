<?php
$lien = GestionLiens::getInstance()->getLien($_GET['id']);
$result = array('res' => 'fail', 'id' => $_GET['id']);

$lien->setValide(true);
if($lien->enregistrer(array('valide'))) {
	$result['res'] = 'done';
}

echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);