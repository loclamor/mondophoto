<?php

$res = SQL::getInstance()->exec('SELECT count(*) AS nbPhotos FROM photo WHERE img_ppal = 1 ORDER BY idPhoto DESC');
$nbPhotos = 0;
if($res) {
	$nbPhotos = $res[0]['nbPhotos'];
}

if($nbPhotos > 0) {
	echo '<button id="start">Démarer</button>';
}
else {
	echo 'Pas de photos';
}

?>

<div id="progressbar"></div>
<div id="progressNumber">0 / <?php echo $nbPhotos;?></div>
<tt id="results"></tt>

<script>
$(function() {
	$( "#progressbar" ).progressbar({ value: 0 });

	$('#start').click(function(){
		$('#start').attr('disabled',"disabled");
		$('#start').html('Quittez la page pour arréter l\'opération');
		var start = 0;
		var max = <?php echo $nbPhotos;?>;
		var incr = 1; //nombre de fichiers traités en même temps par le script

		miniaturize(start,incr,max);
		
	});

});
function miniaturize(start,incr,max){
	if(start <= max) {
		$( "#progressNumber" ).html(start + ' / ' + max);
		$.get('ajax.php?page=miniaturize&start='+ start + '&nb='+ incr, function(data) {
			//traitement affichage
			$('#results').html($('#results').html()+data);
			$("#results").prop('scrollTop', $("#results").prop("scrollHeight"));
			$( "#progressbar" ).progressbar({ value: Math.floor(start/max*100) });
			miniaturize(start + incr, incr, max);
		});
	}
}
</script>
<style>
#results {
	display: block;
	width: 900px;
	height: 500px;
	background-color: black;
	color: white;
	overflow: auto;
}
#progressbar {
	width: 895px;
	height: 20px;
}
#progressNumber {
	text-align: center;
	width: 900px;
}
</style>