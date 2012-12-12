<?php
$result = array('res' => 'fail', 'id' => 0, 'nom' => '', 'url' => '', 'idLieu' => $_GET['idLieu']);
if(isset($_POST['nom']) and !empty($_POST['nom'])
	and isset($_POST['url']) and !empty($_POST['url'])
	and isset($_GET['idLieu']) and !empty($_GET['idLieu'])) {
	$lien = new Lien();
	$lien->setIdLieu($_GET['idLieu']);
	$lien->setNom($_POST['nom']);
	$lien->setUrl($_POST['url']);
	$lien->setType($_POST['type']);
	$lien->setValide(true);
	$id = $lien->enregistrer();
	
	if($id) {
		$result['res'] = 'done';
		$result['id'] = $id;
		$result['nom'] = $lien->getNom();
		$result['url'] = $lien->getUrl();
	}
}
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);