<?php
echo '<h2>Liste des messages</h2>';

$messages = GestionMessageContact::getInstance()->getMessagesContact('id',true);

$tableMessages = '<table id="tableMessages">'
	. '<tr><th>Nom</th><th>Sujet</th><th>Date</th></tr>';
if($messages) {
	foreach ($messages as $message) {
		if($message instanceof MessageContact) {
			$estLu = $message->isLu()?'Lu':'NonLu';
			
			$vip = $message->getNom();
			if($message->getIdUser()!=null){
				$vipu = GestionUsersVip::getInstance()->getUserVip($message->getIdUser());
				$vip = "VIP : ".$vipu->getNom().' '.$vipu->getPrenom();
			}
			
			$tableMessages .= '<tr class="message'.$estLu.'" alt="'.$message->getId().'">';
				$tableMessages .= '<td class="nom">'.$vip.'</td>';
				$tableMessages .= '<td class="sujet">'.$message->getsujet().'</td>';
				$tableMessages .= '<td class="date">'.$message->getDate().'</td>';
			$tableMessages .= '</tr>';
		}
	}
	$tableMessages .= '</table>';
}
else {
	$tableMessages = 'Pas de messages';
}
echo '<div id="listeMessage">'.$tableMessages.'</div>';
echo '<div id="message"><span style="text-align: center; display: block;">cliquez sur un message pour l\'afficher</span></div>';

?>
<script>
	$('#tableMessages tr').click(function(){
		$('#message').html('<img src="style/loader_16.gif" alt="Chargement..."/>');
	//	$('.reading').removeClass('messageNonLu');
	//	$('.reading').addClass('messageLu');
		$('#tableMessages tr').removeClass('reading');
		$(this).addClass('reading');
		
		var id = $(this).attr('alt');
		$.get('ajax.php?page=getMessageContact&idMessage='+id, function(data) {
			$('#message').html(data);
			$('.reading').removeClass('messageNonLu');
			$('.reading').addClass('messageLu');
		});
	});
</script>