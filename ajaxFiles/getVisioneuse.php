<?php
$idLieu = $_GET['idLieu'];

$photos = GestionPhotos::getInstance()->getLieuPhotos($idLieu);
$lieu = GestionLieux::getInstance()->getLieu($idLieu);

echo "<ul id='slider'>";
if($photos){
	foreach ($photos as $photo) {
		if($photo instanceof Photo) {
			echo "<li><h2>";
			if($photo->isAffiche()){
				echo firstchartoupper($photo->getNom());
			}
			else {
				echo $lieu->getPronom() . ' ' .firstchartoupper($lieu->getNom());
			}
			echo "</h2><div class='preload' style='height: 450px'><img src='".$photo->getUrlPhotoMiniature(25)."' class='img_preload' height='450px' /><img src='".$photo->getUrlPhoto()."' class='img_load' height='450px' /></div><br/>";
			echo '&copy; '.$photo->getProprietaire().' - '.$photo->getDatePriseVue(); 
			echo "</li>";
		}
	}
}
else {
	echo "<li><p>Aucunes photos</p></li>";
}

echo "</ul>";
echo "<link rel='stylesheet' href='css/anythingslider.css'>";
//echo "<link rel='stylesheet' href='css/theme-metallic.css'>";
echo "<style>
div.anythingSlider .anythingControls{
	display: block;
}
div.anythingSlider .anythingWindow {
	border: 3px solid #7C9127;
	width: 694px;
}
</style>";
echo "<script>
	$(function(){
		
		$('#slider').anythingSlider({theme: 'default', resizeContents: false}); //expand: true,
		//$(#slider img').lazyload();
	});
</script>";

