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
	<li>Logi <a href="?logout=1">välja</li>
</ul>

<?php require_once "footer.php"; ?>