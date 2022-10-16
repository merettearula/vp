<?php
	session_start();
	//loen sisse konfiguratsioonifailid

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
	require_once "header.php";


	require_once "../../config.php";
	$film_error = null;
	$film = null;
	$title = null;
	$title_error = null;
	$year = null;
	$year_error = null;
	$duration = null;
	$duration_error = null;
	$genre = null;
	$genre_error = null;
	$studio = null;
	$studio_error = null;
	$director = null;
	$director_error = null;

//Kui esineb vigu, siis oleks hea kõiki korrektseid sisestusi ikkagi "meeles pidada" ja neid väljasid siis õigesti täidetuna hoida.

/*if (isset($_POST["film_submit"]) and !empty($_POST["film_submit"])){
			$film = $_POST["film_submit"];
		} else {
			$film_error = "Film jäi lisamata";
		}*/
		
if(isset($_POST["film_submit"])){
		if (isset($_POST["title_input"]) and !empty($_POST["title_input"])){
			$title = $_POST["title_input"];
		} else {
			$title_error = "Pealkiri jäi sisestamata";
		}
		if (isset($_POST["year_input"]) and !empty($_POST["year_input"])){
			$year = $_POST["year_input"];
		} else {
			$year_error = "Aasta jäi sisestamata";
		}
		if (isset($_POST["duration_input"]) and !empty($_POST["duration_input"])){
			$duration = $_POST["duration_input"];
		} else {
			$duration_error = "Kestvus jäi sisestamata";
		}
		if (isset($_POST["genre_input"]) and !empty($_POST["genre_input"])) {
			$genre = $_POST["genre_input"];
		} else {
			$genre_error = "Žanr jäi sisestamata";
		}
		if (isset($_POST["studio_input"]) and !empty($_POST["studio_input"])) {
			$studio = $_POST["studio_input"];
		} else {
			$studio_error = "Stuudio jäi sisestamata";
		}
		if (isset($_POST["director_input"]) and !empty($_POST["director_input"])) {
			$director = $_POST["director_input"];
		} else {
			$director_error = "Režissöör jäi sisetamata";
		}
			
		if (empty($title_error) and empty($year_error) and empty($genre_error) and empty($studio_error) and empty($director_error)){
			//loome andmebaasi ühenduse
			$connection = new mysqli($server_host, $server_user_name, $server_password, $database);
			$connection->set_charset("utf8");
			$stmt = $connection->prepare("INSERT INTO film (pealkiri, aasta, kestus, zanr, tootja, lavastaja) VALUES(?,?,?,?,?,?)");
			echo $connection->error;
			// seome SQL päringu pärisandmetega
			//määrame andmetüübid: i -integer, d - decimal, s -string
			$stmt->bind_param("siisss",$title, $year, $duration, $genre, $studio, $director);
			$stmt->execute();			
			echo $stmt->error;
			//sulgeme päringu
			$stmt->close();
			//sulgeme andmebaasiühenduse
			$connection->close();
		}
	}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Merette Arula, veebiprogrammeerimine</title>
</head>
<body>
	<ul>
		<li>Logi <a href="?logout=1">välja</li>
	</ul>
	<h1>Merette Arula, veebiprogrammeerimine</h1>
	<img src="pics/vp_banner_gs.png" alt="Veebiprogrammeerimine">
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsist informatsiooni. </p>
	<p> Õppetöö toimus <a href="https://www.tlu.ee">Tallinna Ülikoolis</a> Digitehnoloogiate instituudis.</p>
	<a href="https://www.tlu.ee">
		<img src="pics/tlu_38.jpg" alt="Tallinna Ülikooli õppehoone">
	</a>
	<p>Ma olen esimese kursuse informaatika tudeng digitaalse meedia suunal.</p>
	 <form method="POST">
        <label for="title_input">Filmi pealkiri</label>
        <input type="text" name="title_input" id="title_input" placeholder="filmi pealkiri">
		<?php echo $title_error; ?>
        <br>
        <label for="year_input">Valmimisaasta</label>
        <input type="number" name="year_input" id="year_input" min="1912">
		<?php echo $year_error; ?>
        <br>
        <label for="duration_input">Kestus</label>
        <input type="number" name="duration_input" id="duration_input" min="1" value="60" max="600">
		<?php echo $duration_error; ?>
        <br>
        <label for="genre_input">Filmi žanr</label>
        <input type="text" name="genre_input" id="genre_input" placeholder="žanr">
		<?php echo $genre_error; ?>
        <br>
        <label for="studio_input">Filmi tootja</label>
        <input type="text" name="studio_input" id="studio_input" placeholder="filmi tootja">
		<?php echo $studio_error; ?>
        <br>
        <label for="director_input">Filmi režissöör</label>
        <input type="text" name="director_input" id="director_input" placeholder="filmi režissöör">
		<?php echo $director_error; ?>
        <br>
        <input type="submit" name="film_submit" value="Salvesta">
    </form>

</body>
</html>