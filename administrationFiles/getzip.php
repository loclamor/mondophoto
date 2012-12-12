<?php
require_once '../functions.php';
$z = new ZipArchive();
$z->open("test.zip", ZIPARCHIVE::CREATE);
$z->addFile('../CGU_MondoPhoto.pdf');
$z->close();

$fic = './test.zip';

forceDownload($fic);