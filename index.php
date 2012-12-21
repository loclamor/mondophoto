<?php
require_once 'conf/init.php';

$site = new Site();
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title><?php echo $site->getTitle();?></title>
		<link type="image/x-icon" href="style/favicon.ico" rel="shortcut icon"/>
<!-- 	<link rel="stylesheet" media="screen" type="text/css" title="style" href="style/style.css" />  -->
<!--	<link rel="stylesheet" media="screen" type="text/css" title="style" href="style/menu.css" />  -->
		<link rel="stylesheet" media="screen" type="text/css" title="style" href="css/bootstrap.css" />
		<link rel="stylesheet" media="screen" type="text/css" title="style" href="style/supplement.css" />
		<link media="all" type="text/css" href="css/smoothness/jquery-ui-1.8.21.custom.css" rel="stylesheet">
		
	<link rel="stylesheet" media="screen" type="text/css" title="style AS" href="css/anythingslider.css" /> 
		<script src="js/jquery-1.7.1.min.js" type="text/javascript" language="javascript" ></script>
		<script src="js/jquery.anythingslider.js" type="text/javascript" language="javascript" ></script>
		<script src="js/jquery-ui-1.8.21.custom.min.js" type="text/javascript" language="javascript" ></script>
		<script src="js/functions.js" type="text/javascript" language="javascript" ></script>
		<script src="js/encoder.js" type="text/javascript" language="javascript" ></script>
		<script src="js/jquery.svg.js" type="text/javascript" language="javascript" ></script>
<!-- 		<script src="js/jquery.svgdom.js" type="text/javascript" language="javascript" ></script>
		 jquery.lazyload.min.js-->
		<script src="js/jquery.lazyload.min.js" type="text/javascript" language="javascript" ></script>
		<script src="js/bootstrap.js" type="text/javascript" language="javascript" ></script>
		<?php if(APPLICATION_ENV == 'prod') {?>
		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-30105797-1']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>
		<?php }?>
		<meta property="og:title" content="MondoPhoto" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="http://www.mondophoto.fr" />
		<meta property="og:image" content="http://www.mondophoto.fr/style/bitmap.png" />
		<meta property="og:site_name" content="MondoPhoto" />
		<meta property="fb:admins" content="1277041996" />
	</head>
	<body>
		
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		
		
		<div id="head" class="row-fluid" style="background-color: <?php echo $site->couleur;?>">
		<?php echo $site->getHead();?>
			<script>
					$(document).ready(function(){
				//	$("#logo").show("slide",{},500);
					$("#logo").hide();
					$("#logo").show(500); //,{},500);
				//	$("img.photoPub").lazyload();
				});
				
			</script>
		</div>
		<nav class="navbar">
			<div class="navbar-inner">
				<div class="container" id="menu">
		
					<ul id="onglets" class="nav">
					<?php echo $site->getMenu();?>
					</ul>
					<?php 
					$urlRecherche = new Url(true);
					$urlRecherche->addParam('page', 'recherche');
					?>
					
					<form action='<?php echo $urlRecherche->getUrl(); ?>' class='navbar-search form-search pull-right' method='POST'>
						<div class='input-append'>
							<input type='hidden' name='page' value='recherche' />
							<input type='text' name='query' id='searchQuery' size='10' style='color: #AAA;' class='search-query' value='Rechercher...' />
							<button type='submit' class='btn btn-primary btn-navbar-search' ><i class="icon-white icon-search"></i></button>
						</div>
					</form>
					
					<div class="fb-like-container pull-right">
						<div class="fb-like pull-right"  data-href="http://www.mondophoto.fr" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>
					</div>
				</div>
			</div>
		</nav>
		<div class="row-fluid filariane">
			<div id="filariane" class="span12">Vous êtes ici : <?php echo $site->getFilAriane();?></div>
		</div>
		<div id="content" class="row-fluid">
			<div class="span12 content-container">
				<div class="row-fluid">
					<div id="tooltip"></div>
					<?php echo $site->getContent();?>
					<?php if(APPLICATION_ENV == 'prod') {?>
						<div id="pub">
							<script type="text/javascript"><!--
							google_ad_client = "ca-pub-0193377168886945";
							/* Site voyage */
							google_ad_slot = "7331631247";
							google_ad_width = 468;
							google_ad_height = 60;
							//-->
							</script>
							<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
						</div>
					<?php }?>
				</div>
			</div>
		</div>
		<div id="foot" class="row-fluid" style="background-color: <?php echo $site->couleur;?>">
			<div class="span12 footer"><?php echo $site->getFoot();?></div>
		</div>
		<input type="hidden" id="currentLoaded" value="0"/>
		<input type="hidden" id="current" value="0"/>
		<script>
		
		$('#mapCont>area').mouseenter(function(){
			$('#tooltip').addClass('tooltipShow');
			$('#tooltip').html('<img src="style/loader_16.gif" alt="Chargement..."/>');
			
			$.get('ajax.php?page=getTooltipOnContinent&idPays='+$(this).attr('alt'), function(data) {
				$('#tooltip').html(data);
			});
			
		});

		$('path').mouseenter(function(){
			$('#tooltip').addClass('tooltipShow');
			$('#tooltip').html($(this).attr('name'));
			
		});

		$('#mapPays>area, .imgVilleOff').live('mouseenter',function(){
			$('#tooltip').html('<img src="style/loader_16.gif" alt="Chargement..."/>');
			$('#tooltip').addClass('tooltipShow');
			$.get('ajax.php?page=getTooltipOnPays&idLieu='+$(this).attr('alt'), function(data) {
				$('#tooltip').html(data + '&nbsp;<img src="style/loader_16.gif" alt="Chargement..."/>');
			});
		});
		
		$('area, .imgVilleOff, path, circle').live('mouseleave',function(){
			$('#tooltip').removeClass('tooltipShow');
		});

		$("map, .imgVilleOff").live('mousemove',function(event) {
			$('#tooltip').css('left',event.pageX + 15);
			$('#tooltip').css('top',event.pageY + 25);
		});

		$("path").live('mousemove',function(event) {
			$('#tooltip').css('left',event.pageX + 15);
			$('#tooltip').css('top',event.pageY + 25);

			$('#currentLoaded').attr('value','');
			
			var cont = $('#tooltip').html();
			if(parseInt($('#current').attr('value')) != parseInt($(this).attr('alt')))
			{
				$('#tooltip').html($(this).attr('name'));
				$('#current').attr('value', $(this).attr('alt'));
				var cont = $('#tooltip').html();
				if(cont == "") {
					$('#tooltip').removeClass('tooltipShow');
				}
				else {
					$('#tooltip').addClass('tooltipShow');
				}
			} 
		});

		$("circle.ville, circle.merveille, circle.monument").live('mousemove',function(event) {
			$('#tooltip').css('left',event.pageX + 15);
			$('#tooltip').css('top',event.pageY + 25);
			var cont = $('#tooltip').html();
			$(this).attr("r",1);

			$('#tooltip').addClass('tooltipShow');
			
			
			if(parseInt($('#current').attr('value')) != parseInt($(this).attr('alt')) )
			{
				$('#tooltip').html($(this).attr('name'));
				$('#current').attr('value', $(this).attr('alt'));
				var cont = $('#tooltip').html();
				if(cont == "") {
					$('#tooltip').removeClass('tooltipShow');
				}
				else {
					$('#tooltip').addClass('tooltipShow');
					$.get('ajax.php?page=getTooltipOnPays&idLieu='+$(this).attr('alt'), function(data) {
						$('#tooltip').html(data + '&nbsp;<img src="style/loader_16.gif" alt="Chargement..."/>');
					});
				}
			} 
			
		});

		$('circle.ville, circle.merveille, circle.monument').live('mouseleave',function(){
			$(this).attr("r",0.55);
		});
/*
		$(".cadrePhoto").click(function(){
		//	$("#fondVisioneuse").css('visibility','visible');
		//	$("#fondVisioneuse").hide().fadeIn('slow');
			$("#visioneuse").html("<img id='loader' src='style/loader_128.gif' alt='Chargement...'/>");
			$(this).attr("id");
			$.get('ajax.php?page=getVisioneuse&idLieu='+$(this).attr('id'), function(data) {
				$('#visioneuse').html(data);
			});
			$("#fondVisioneuse").fadeIn(500);
		});

		$(".merveille, .monument").click(function(){
				$("#visioneuse").html("<img id='loader' src='style/loader_128.gif' alt='Chargement...'/>");
				$(this).attr("alt");
				$.get('ajax.php?page=getVisioneuse&idLieu='+$(this).attr('alt'), function(data) {
					$('#visioneuse').html(data);
				});
				$("#fondVisioneuse").fadeIn(500);
			});
		
		$("#cadreVisioneuse>#closer").live('click',function(){
		//	$("#fondVisioneuse").css('visibility','hidden');
		//	$("#fondVisioneuse").show().fadeOut('slow');
			$.anythingSlider("init");
			$("#fondVisioneuse").fadeOut(500);
		});
*/
		$(document).ready(function(){
			var coordImage = $('#paysCarte').offset();
			$('.villeOff').each(function(){
				var coordVille = $(this).attr('coords').split(',');
			//	alert(coordVille +  coordVille[0] + coordVille[1] + coordVille[2]);
				var left = parseFloat(coordImage.left) - 2 + parseFloat(coordVille[0]);
				var top = parseFloat(coordImage.top) - 2 + parseFloat(coordVille[1]);
				$('body').append('<img src="style/villeOff.png" alt="'+$(this).attr('alt')+'" style="position:absolute; left:'+left+'px; top:'+top+'px;" class="imgVilleOff"/>');
			});

			$('#dialog-contact').css('display', 'none');
			
			$('.contact').click(function(){
				$('#dialog-contact').dialog({height: 500,
					width: 600,
					modal: true,
					resizable: false,
					hide: 'fadeOut',
					buttons: {
						Annuler: function() {
							$( this ).dialog( "close" );
							
						},
						Envoyer: function() {
							// vérifications
							if($('#nom').val() != ''){
								if($('#adresse_mail').val() != ''){
									if($('#sujet').val() != ''){
										if($('#text_contact').val() != ''){
	
											var Pnom = $('#nom').val();
											var Pmail = $('#adresse_mail').val();
											var Psujet = $('#sujet').val();
											var Ptext = $('#text_contact').val();
											var PverifBot = $('#verifBot').val();
											
											$('#dialog-contact').html('<div><img src="style/loader_128.gif"/></div>');
											$.post("ajax.php?page=traitementPostMessageContact", { nom: Pnom, adresse_mail: Pmail, sujet: Psujet, text_contact: Ptext, verifBot: PverifBot }, function(data) {
												var result = eval('(' + data + ')');
												if(result.res == "done"){
													$('#dialog-contact').html('<div><img src="style/check.png"/> Message envoyé</div>');
												}
												else {
													$('#dialog-contact').html('<div><img src="style/warning.png"/> Erreur lors de l\'envoie</div>');
												}
												
											});
											
											$( this ).delay(1500).queue(function() {
												$(this).dialog( "close" );
											});
										}
										else {
											alert('Merci de renseigner le corp du message !');
										}
									}
									else {
										alert('Merci de renseigner le sujet de votre message !');
									}
								}
								else {
									alert('Merci de renseigner votre adresse mail !');
								}
							}
							else {
								alert('Merci de renseigner votre nom !');
							}
							
						}
					}
				});
				return false;
			});
			
		});
		</script>
		<style>
			.ui-autocomplete-loading { background: white url('css/smoothness/images/ui-anim_basic_16x16.gif') right center no-repeat; }
		</style>
		<script>
		$(function() {
			function log( message ) {
				$( "#log" ).text( message ).prependTo( "#log" );
				$( "#log" ).scrollTop( 0 );
			}
	
			$( "#searchQuery" ).autocomplete({
				source: "ajax.php?page=search",
				minLength: 2,
				select: function( event, ui ) {
					//alert(ui.item.value);
					$( "#searchQuery" ).attr('value',Encoder.htmlDecode(ui.item.value));
					$( "#searchQuery" ).parent('form').submit();
					/*	log( ui.item ?
							"Selected: " + ui.item.value + " aka " + ui.item.id :
							"Nothing selected, input was " + this.value );
					*/
					//alert(ui.item.value);
				},
				open: function( event, ui ) {
					
					$('.ui-autocomplete>.ui-menu-item>a').each(function(){
						 $(this).html(Encoder.htmlDecode( $(this).html()));
					});
				}
			});
			$( "#searchQuery" ).focus(function(){
				if( $( "#searchQuery" ).attr('value') == "Rechercher..."){
					$( "#searchQuery" ).attr('value', "");
					$( "#searchQuery" ).attr('style', "");
				}
				$( "#searchQuery" ).attr('value',Encoder.htmlDecode($( "#searchQuery" ).attr('value')));
			});
			$( "#searchQuery" ).focusout(function(){
				if( $( "#searchQuery" ).attr('value') == ""){
					$( "#searchQuery" ).attr('value', "Rechercher...");
					$( "#searchQuery" ).attr('style', "color: #AAA;");
				}
			});
		});
		</script>
		<div id="fondVisioneuse"><div id="cadreVisioneuse"><div id="closer"></div><div id="visioneuse"></div></div></div>
		
		
	</body>
</html>
<?php 
require_once 'conf/fini.php';?>