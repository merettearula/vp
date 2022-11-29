<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title> Merette Arula, veebiprogrammeerimine</title>
	<style>
		body{
			background-color: <?php echo $_SESSION["user_bg_color"]; ?>;
			color: <?php echo $_SESSION["user_txt_color"]; ?>;
		}
	</style>
	<?php
	
		if(isset($style_sheets) and !empty($style_sheets)){
			foreach($style_sheets as $style){
			//<link rel="stylesheet" href="styles/gallery.css">
				echo '<link rel="stylesheet" href="' .$style .'">' ."\n";
			}
		}
		if(isset($javascripts) and !empty($javascripts)){
			foreach($javascripts as $js){
			//<link rel="stylesheet" href="styles/gallery.css">
				echo '<script src="' .$js .'" defer></script>' ."\n";
			}
		}
	?>
</head>
</head>
<body>
	<img src="pics/vp_banner_gs.png" alt="Veebiprogrammeerimine">
	<h1>Veebisüsteem</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsist informatsiooni. </p>