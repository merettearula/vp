<?php 
	session_start();
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
	require_once "header.php";
?>
<ul>
	<p> Sisse logitud: <?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"]; ?>
	<li>Logi <a href="?logout=1">välja</li>
	<br>
	<li><a href="add_new_film.php">Siit saad lisada uusi filme</a></li>
	<li><a href="display_film.php">Vaata lisatud filme</a></li>
	<li><a href="read_daycomments.php">Vaata lisatud päevakommentaare</a></li>
</ul>

<?php require_once "footer.php"; ?>