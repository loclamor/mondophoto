<?php
$lieuxVip = GestionLieuxVip::getInstance()->getLieuxVip('id',true);
$urlAction = new Url();
$urlAction->addParam('page', 'traitementGestionPhotoVip');
$i = 0;
$nb = 0;

if(!isset($_GET['nop']) or empty($_GET['nop'])){
	$_GET['nop'] = 1;
}

$nbByPage = 5;
$curPage = $_GET['nop'] - 1;

$urlBefore = new Url();
$urlBefore->addParam('nop', $_GET['nop'] - 1);
$urlNext = new Url();
$urlNext->addParam('nop', $_GET['nop'] + 1);
if($curPage > 0){
	echo '<a href="'.$urlBefore->getUrl().'"><<<</a>';
}
echo '&nbsp;page '.$_GET['nop'].' &nbsp;';

if(count($lieuxVip) > ($curPage*$nbByPage + $nbByPage))
{
	echo '<a href="'.$urlNext->getUrl().'">>>></a>';
}

echo '<br/>Affichage de '.($curPage*$nbByPage).' à '.($curPage*$nbByPage + $nbByPage).' sur '.count($lieuxVip);

foreach ($lieuxVip as $lv) {
	if($lv instanceof VipLieu and ($curPage*$nbByPage) <= $nb and $nb < ($curPage*$nbByPage + $nbByPage)) {
		$PhotosLieux = GestionPhotosVip::getInstance()->getPhotosLieuVip($lv->getId());
		$nbPhotosLieux = count($PhotosLieux);
		if($nbPhotosLieux > 0 && $PhotosLieux) {
			$uv = GestionUsersVip::getInstance()->getUserVip($lv->getIdVip());
			echo '<fieldset><legend>' . $uv->getNom() . ' ' . $uv->getPrenom(). ' : ' . $lv->getNomPays() . ' : ' . $lv->getNomVille() . ' : ' . code($lv->getNomLieu()) . '</legend>';
			
			
			echo '<form action="'.$urlAction->getUrl().'" method="POST" >';
			
			echo '<input type="hidden" name="idLieuVip" value="'.$lv->getId().'" />';
			
			$pays = GestionPays::getInstance()->getAllPays('nom');
			echo '<select class="chxPays" name="chxPays" alt="'.$i.'">';
				echo '<option value="0">Choisir...</option>';
			foreach ($pays as $p){
				if($p instanceof Pays){
					echo '<option value="'.$p->getId().'">'.$p->getNom().'</option>';
				}
			}
			echo '</select>';
			echo '<select class="chxLieu" id="chxLieu_'.$i.'" name="chxLieu" alt="'.$i.'"></select>';
			echo '<select class="chxLieuLie" id="chxLieuLie_'.$i.'" name="chxLieuLie"></select>';
			
			echo '<input type="submit" id="subbutton_'.$i.'" value="Valider" />';
			
			echo '</form>';
			
			$urlZip = new Url();
			$urlZip->addParam('page', 'getZipVip');
			$urlZip->addParam('noDisplay', 'noDisplay');
			$urlZip->addParam('idLieuVip', $lv->getId());
			echo '<a href="'.$urlZip->getUrl().'">zip</a>';
			
			echo '<br/>';

			foreach ($PhotosLieux as $pl) {
				if($pl instanceof VipPhoto) {
					echo '<img src="'.$pl->getUrlImage().'" height="100px" />&nbsp;';
				}
			}
			echo '</fieldset>';
			$i++;
			
		}
		else {
			$lv->supprimer();
		}
	}
	$nb++;
}
	?>
		<script>
			$(document).ready(function(){
				$('input').hide();
			});
			$('.chxPays>option').click(function(){
			//	alert ($(this).attr('value'));
				var alt = $(this).parent().attr('alt');
			
				$.get('ajax.php?page=listeSelectLieu&idPays='+$(this).attr('value'), function(data) {
					$('#chxLieu_'+alt).html('<option value="0">Choisir...</option>' + data);
					$('#chxLieuLie_'+alt).html("");
					$('#subbutton_'+alt).hide();
				});
			});
			$('.chxLieu>option').live('click',function(){
			//	alert ($(this).attr('value'));
				var alt = $(this).parent().attr('alt');
				$.get('ajax.php?page=listeSelectLieuLie&idLieu='+$(this).attr('value'), function(data) {
					$('#chxLieuLie_'+alt).html(data);
					$('#subbutton_'+alt).show();
				});
			});
		</script>
			<?php
