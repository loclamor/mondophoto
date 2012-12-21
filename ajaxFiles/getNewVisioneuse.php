<?php
$idLieu = $_GET['idLieu'];

$photos = GestionPhotos::getInstance()->getLieuPhotos($idLieu);
$lieu = GestionLieux::getInstance()->getLieu($idLieu);

$isAjaxQuery = false;
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  /* special ajax here */
	$isAjaxQuery = true;
}

if(!$isAjaxQuery){
?>
<html>
	<head>
		<link rel="stylesheet" media="screen" type="text/css" title="style" href="css/bootstrap.css" />
		<link rel="stylesheet" media="screen" type="text/css" title="style" href="style/supplement.css" />
		<script src="js/jquery-1.7.1.min.js" type="text/javascript" language="javascript" ></script>
		<script src="js/bootstrap.js" type="text/javascript" language="javascript" ></script>
	</head>
	<body>
<?php 
}

?>
		<div id="carousel_<?php echo $idLieu ?>" class="carousel slide">
			<div class="carousel-inner">
			
			
			<?php 
			$i = 1;
			
			if($photos){
				$nbPhoto = count($photos);
				foreach ($photos as $photo) {
					if($photo instanceof Photo) {
						$class="";
						if($i == 1){
							$class="active";
						}
						echo '<div class="item '.$class.'">';
							echo "<img src='".$photo->getUrlPhoto()."' class='img_load' height='450px' />";
							echo '<div class="carousel-caption"><p>';
							if($photo->isAffiche()){
								echo firstchartoupper($photo->getNom());
							}
							else {
								echo $lieu->getPronom() . ' ' .firstchartoupper($lieu->getNom());
							}
						
							echo ' - &copy; '.$photo->getProprietaire().' - '.$photo->getDatePriseVue();
							echo '<span class="pull-right">'.$i.'/'.$nbPhoto.'</span>';
						echo "</p></div></div>";
						$i++;
					}
				}
			}
			else {
				echo '<div class="item active">Pas de photos</div>';
			}
			?>
				
			</div>
<?php 
	if($i > 2){
?>
			<a class="carousel-control left" data-slide="prev" href="#carousel_<?php echo $idLieu ?>">&lsaquo;</a>
			<a class="carousel-control right" data-slide="next" href="#carousel_<?php echo $idLieu ?>">&rsaquo;</a>
<?php 
	}
?>
		</div>
<?php
if(!$isAjaxQuery){
?>			
	</body>
</html>
<?php
}
?>

<script>
$(function (){
    $('#carousel_<?php echo $idLieu ?>').carousel('pause');
    
});  
</script>
