<?php
$lien = GestionLiens::getInstance()->getLien($_GET['id']);
$result = array('res' => 'fail', 'id' => $_GET['id']);

	if(remote_file_exists($lien->getUrl())){
		$result['res'] = 'done';
	}
	else {
		$lien->setValide(false);
		$lien->enregistrer(array('valide'));
	}

echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
