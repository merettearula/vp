<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title> Merette Arula, veebiprogrammeerimine</title>
	<style>
		body {
			background-color: <?php echo $_SESSION["user_bg_color"]; ?>;
			color: <?php echo $_SESSION["user_txt_color"]; ?>;
			
		}
	</style>
	
	<?php 
        if(isset($style_sheets) and !empty($style_sheets)){
			//<link rel="stylesheet" href="styles/gallery.css">
			foreach($style_sheets as $style){
				echo '<link rel="stylesheet" href="' .$style .'">' ."\n";
			}
		}
	?>
</head>
<body>
	<img src="pics/vp_banner_gs.png" alt="Veebiprogrammeerimine">
	<h1>Veebisüsteem</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsist informatsiooni. </p>