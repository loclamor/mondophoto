<?php
$lien = GestionLiens::getInstance()->getLien($_GET['id']);
$result = array('res' => 'done', 'id' => $_GET['id']);

$lien->setValide(false);
$lien->enregistrer(array('valide'));

echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
