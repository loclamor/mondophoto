<?php
$lien = GestionLiens::getInstance()->getLien($_GET['idLien']);
$result = array('res' => 'fail', 'id' => $_GET['idLien']);
if($lien instanceof Lien){
	$lien->supprimer();
	$result['res'] = 'done';
}
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
