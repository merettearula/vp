<?php 
	require_once "../../config.php";

	require_once "classes/SessionManager.class.php";
	//järgnev rida tõmbab käima static funktsiooni
	SessionManager::sessionStart("vp", 0, "~arulmere/vp/", "greeny.cs.tlu.ee");
	
	if(!isset($_SESSION["user_id"])){
		//viiakse page.php
		header("Location: page.php");
		exit();
	}
	//logime välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
		exit();
	}
	
	require_once "fnc_gallery.php";
	require_once "fnc_general.php";
	//kontrollin pildi valikud
	
	$photo_error = null;
	$alt = null;
	$privacy = 1;
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["photo_submit"])){
			//var_dump($_POST);
			//kas on üldse pildifail ja mis tüüpi
			//filtervar->mis tüüpi fail
			$alt = test_input($_POST["alt_input"]);
			$privacy = filter_var($_POST["privacy_input"], FILTER_VALIDATE_INT);
			//andmete uuendamise osa
			$id = $_POST["photo_input"];
            $photo_error = edit_own_photo_data($alt, $privacy, $id);
			if(empty($photo_error)){
				$photo_error = "Andmed muudetud!";
			} else {
				$photo_error = "Pildi andmeid ei õnnestunud muuta!";
		} //if photo submit
	} else {
		$photo_error = "Pildi andmeid ei saanud muuta";
		}//if post
	}
	
	
	
	if(isset($_POST["photo_delete_submit"])) {
		//mida me siin kontrollime?
		if(isset($_POST["photo_input"]) and filter_var($_POST["photo_input"], FILTER_VALIDATE_INT)){
			$id = filter_var($_POST["photo_input"], FILTER_VALIDATE_INT);
			$photo_error = delete_own_photo_data($id);
			if (empty($photo_error)) {
				$photo_error = "Pilt sai kustutatud";
			} else {
				$photo_error = "Pole luba pilti kustutada. Saate kustutada vaid enda faile.";
			} 
		} else {
		$photo_error = "Pilti ei saanud kustutada";
		}
	}
	
	echo $photo_error;
	
	if(isset($_GET["id"]) and !empty($_GET["id"]) and filter_var($_GET["id"], FILTER_VALIDATE_INT)) {
		$photo_data = read_own_photo_data($_GET["id"]);
		$alt = $photo_data["alt"];
		$privacy = $photo_data["privacy"];	
	}

	
	require_once "header.php";
?>
<ul>
	<p> Sisse logitud: <?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"]; ?>
	<li>Logi <a href="?logout=1">välja</li>
	<li>Tagasi avalehele <a href="home.php"></a></li>
	<li><a href="<?php echo "gallery_own.php?=page" .$_SESSION["gallery_own_page"]; ?>">Tagasi oma fotode galeriisse</a></li>
	
	
</ul>
	<hr>
	<h2>Fotode andmete muutmine</h2>
	<?php
	
	echo '<img src="' .$gallery_photo_normal_folder .$photo_data["filename"] .'" alt="' .$alt .'">' ."\n";
	?>
	<br>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ."?id=" .$id;?>">
		<input type="hidden" name="photo_input" id="photo_input" value="<?php echo $_GET["id"]; ?> ">
		<br>
		<label for="alt_input">Alternatiivtekst (alt): </label>
		<input type="text" name="alt_input" id="alt_input" placeholder="alternatiivtekst..."><?php echo $alt; ?>
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_1" value="1">
		<label for="privacy_input_1">Privaatne (ainult ise näen)</label> <?php echo $privacy; ?>
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_2" value="2">
		<label for="privacy_input_2">Sisseloginud kasutajatele</label> <?php echo $privacy; ?>
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_3" value="3">
		<label for="privacy_input_3">Avalik (kõik näevad)</label> <?php echo $privacy; ?>
		<br>
		<input type="submit" name="photo_submit" id="photo_submit" value="Muuda">
		<span><?php echo $photo_error;?> 
	</form>
	<hr>
	<!-- Lisan kustutamise formi -->
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<input type="hidden" name="photo_input" value="<?php echo $_GET["id"]; ?>">
		<input type="submit" name="photo_delete_submit" id="photo_delete_submit" value="Kustuta">
	</form>
	<hr>
	<a href="gallery_public.php">Vaata lisatud pilte</a>
	<hr>
	
</ul>

<?php require_once "footer.php"; ?>


