<?php
if(!empty($_FILES['cartePays']['size'])	) {
		
	
	$nomCarte = noSpecialChar($_FILES['cartePays']['name']);
	$emplacement = 'images/'.$nomCarte;
	move_uploaded_file($_FILES['cartePays']['tmp_name'],$emplacement);
	
//	include $emplacement;
	
	$dom = new DOMDocument();
	if (!$dom->load($emplacement)) {
	    echo "Impossible de charger le fichier";
	}
	
	$newDom = new DOMDocument('1.0','UTF-8');
	$newG1 = $newDom->createElement('g');
	$gList = $dom->getElementsByTagName("g");
	// le premier G est celui contenant tout le reste
	if($gList->item(0) instanceof DOMNode){
	
		$g = $gList->item(0);
		$gChilds = $g->childNodes;
		$lastPath = null;
		$pathType = 'pays';
		$hasId = false;
		$nbPays = 0;
		$nbVilles = 0;
		foreach ($gChilds as $gc){
			if($gc instanceof DOMNode){
				
				if($gc->nodeName != '#text') {
				//	echo $gc->nodeName . '<br/>';
					$attributes = $gc->attributes;
					
					foreach ($attributes as $attr){
						if($attr instanceof DOMAttr) {
							switch($attr->nodeName) {
								case 'id':
									$hasId = true;
									$id = $attr->nodeValue;
									break;
								case 'd':
									$d = $attr->nodeValue;
									break;
								case 'xlink:href':
									$href = $attr->nodeValue;
									break;
								case 'x':
									$x = $attr->nodeValue;
									break;
								case 'y':
									$y = $attr->nodeValue;
									break;
							}
						}
					}
					switch($gc->nodeName){
						case 'path':
							
							$paysSVG = new MappemondePaysSVG();
							$paysSVG->setCoordonnees($d);
							$id = $paysSVG->enregistrer();
							
							$newPath = $newDom->createElement('path');
							$newPath->setAttribute('d', $d);
							$newPath->setAttribute('stroke-width', '0.1px');
							$newPath->setAttribute('fill', 'white');
							$newPath->setAttribute('id', $id);
							$newPath->setAttribute('class', 'pays');
							$newPath->setAttribute('stroke', 'black');
							$newG1->appendChild($newPath);	
							break;
						
					}
				}
			}
		}
	}
	$viewbox = '';
	//dernière chose, on récupère les attributs de l'élément SVG pour conaitre le sens
	$svgList = $dom->getElementsByTagName("svg");
	if($svgList->item(0) instanceof DOMNode){
		$svg = $svgList->item(0);
		$attributes = $svg->attributes;
		foreach ($attributes as $attr){
			if($attr instanceof DOMAttr) {
			//	echo $attr->nodeName . '<br/>';
				if($attr->nodeName == 'viewBox') {
					$viewbox = $attr->nodeValue;
				}
			}
		}
	}
	if($viewbox == '0 0 210 297') {
		$sens = 1;
		$width = '210mm';
		$height = '297mm';
	}
	else {
		$sens = 0;
		$width = '297mm';
		$height = '210mm';
	}
	
	
	$newDom->appendChild($newG1);

	echo '<svg xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="'.$viewbox.'" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" height="'.$height.'" width="'.$width.'" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">'
		. $newDom->saveHTML()
		. '</svg>';

}
else {
	debug($_POST);
	$urlAction = new Url();
	$urlAction->addParam('page', 'loadMappemonde');
	echo '<form action="'.$urlAction->getUrl().'" method="POST" enctype="multipart/form-data" >';
	
	echo '<label for="cartePays">Carte du pays : </label><input type="file" size="75" name="cartePays" id="cartePays"/><br/>';

	echo '<input id="submit" type="submit" value="valider"/>';
	echo '</form>';

}