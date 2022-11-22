<?php 
	require_once "classes/SessionManager.class.php";
	//järgnev rida tõmbab käima static funktsiooni
	SessionManager::sessionStart("vp", 0, "~arulmere/vp/", "greeny.cs.tlu.ee");
	if(!isset($_SESSION["user_id"])){
		//jõuga viiakse page.php
		header("Location: page.php");
		exit();
	}
	//logime välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
		exit();
	}
	//tegelen küpsistega
	
	$last_visitor = "Pole teada";
	
	if((isset($_COOKIE["lastvisitor"])) and !empty($_COOKIE["lastvisitor"])){
		$last_visitor = $_COOKIE["lastvisitor"];
	}
	
	//salvestan küpsise 
	//nimi, väärtust, aegumistähtaeg, veebikataloog, domeen, https kasutamine,
	//https isset($_SERVER["HTTPS"])
	setcookie("lastvisitor", $_SESSION["firstname"] ." " .$_SESSION["lastname"], time()+ (60 * 60 * 24 * 8), "~arulmere/vp/", "greeny.cs.tlu.ee", true, true);
	//küpsise kustutamine: expire ehk aegumistähtaeg pannakse minevikus time() - 3000
	
	require_once "header.php";
	
	//echo "<p>Sisse loginud: " $_SESSION["firstname"]." ".$_SESSION["lastname"] .".</p> \n";
	if($last_visitor != $_SESSION["firstname"]." ".$_SESSION["lastname"]){
		echo "<p> Viimati oli sisse loginud: " .$last_visitor ."</p> \n";
	}
?>
<ul>
	<p> Sisse logitud: <?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"]; ?>
	<li>Logi <a href="?logout=1">välja</li>
	<li>Fotode galeriisse <a href="gallery_photo_upload.php">lisamine</a></li>
	<br>
	<li><a href="add_new_film.php">Siit saad lisada uusi filme</a></li>
	<li><a href="display_film.php">Vaata lisatud filme</a></li>
	<li><a href="read_daycomments.php">Vaata lisatud päevakommentaare</a></li>
	<li><a href="gallery_public.php">Vaata lisatud pilte</a></li>
	<li><a href="gallery_own.php">Minu fotod</a></li>
</ul>

<?php require_once "footer.php"; ?>