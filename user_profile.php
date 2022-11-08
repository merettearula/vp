<?php 
	session_start();
	require_once "fnc_user.php";
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
	
	if(isset($_POST["color_submit"]) and !empty($_POST["color_submit"])) {
		$userid = $_SESSION["user_id"];
		$description = $_POST["user_description"];
		$bgcolor = $_POST["bg_color_input"];
		$txtcolor = $_POST["txt_color_input"];
		echo profile_colors($userid, $description, $bgcolor, $txtcolor);
	}
	
	require_once "header.php";
?>
<form method="POST">
	<input type="hidden" name="profile_input" id="profile_input" value="<?php echo $_SESSION["user_id"]; ?> ">
	<br>
	<label for="user_description">Muuda kasutaja profiili: </label>
	<br>
	<input type="color" name="bg_color_input" id="bg_color_input">
	<br>
	<input type="color" name="txt_color_input" id="txt_color_input">
	<br>
	<textarea name="user_description" id="user_description" rows="5" cols="51" placeholder="Minu lühikirjeldus"></textarea>
	<br>
	<input type="submit" id="color_submit" name="color_submit" value="Salvesta">
</form>



<?php require_once "footer.php"; ?>

