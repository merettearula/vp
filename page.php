<?php
	//loen sisse konfiguratsioonifailid
	require_once "../config.php";
	$author_name = "Merette Arula";
	//echo $author_name;
	$full_time_now = date("d.m.Y H:i:s");
	$weekday_names_et = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	//echo $weekday_names_et[1];
	$weekday_now = date("N");
	$hour_now = date("H");
	$part_of_day = "suvaline hetk";
	$proverbs_et = ["Hästi tehtud on parem, kui hästi öeldud.", "Parem väike tuli, mis soojendab, kui suur tuli, mis kõrvetab.", "Viga, mida ei tunnistata, on kahekordne viga.", "Kui uskuda kõike, on seda liiga palju; kui mitte midagi uskuda, on seda liiga vähe.", "Kui sa õigel ajal teele ei asu, pole kiirest jooksmisest kasu."];

	
	
	if ($weekday_now>0 and $weekday_now<5){
		if($hour_now<7 and $hour_now>23){
			$part_of_day = "uneaeg";
		}
		if ($hour_now>7 and $hour_now<8){
			$part_of_day = "ettevalmistused koolipäevaks";
		}
		if($hour_now>=8 and $hour_now<15){
			$part_of_day = "koolipäev";
		}
		if ($hour_now>=15 and $hour_now<19){
			$part_of_day = "tööaeg";
		}
		
		if ($hour_now>=19 and $hour_now<20) {
			$part_of_day = "trenniaeg";
		}
		
		if ($hour_now>20 and $hour_now<23) {
			$part_of_day = "koolitööd või puhkeaeg";
		}
	}
	
	
	if ($weekday_now==5) {
		if($hour_now<7 and $hour_now>23){
			$part_of_day = "uneaeg";
		}
		if ($hour_now >= 8 and $hour_now <= 12) {
			$part_of_day = "kooliaeg";
		}
		if ($hour_now>12 and $hour_now<15) {
			$part_of_day = "sõit Tartusse";
		}
		if ($hour_now>=15 and $hour_now<=18) {
			$part_of_day = "tööaeg";
		}
		if ($hour_now>16 and $hour_now<00) {
			$part_of_day = "aeg sõprade ja lähedastega";
		}
	}
	if ($weekday_now == 7) {
		if($hour_now<9 and $hour_now>00){
			$part_of_day = "uneaeg";
		}
		if ($hour_now >= 10 and $hour_now <= 12) {
			$part_of_day = "rahulik ärkamine ja hiline hommikusöök";
		}
		if ($hour_now>12 and $hour_now<16) {
			$part_of_day = "koolitööde aeg";
		}
		if ($hour_now>16 and $hour_now<00) {
			$part_of_day = "aeg sõprade ja lähedastega";
		}
	}
	if ($weekday_now ==6){
		if($hour_now<9 and $hour_now>23){
			$part_of_day = "uneaeg";
		}
		if ($hour_now >= 10 and $hour_now <= 12) {
			$part_of_day = "rahulik ärkamine ja hiline hommikusöök";
		}
		if ($hour_now>12 and $hour_now<18) {
			$part_of_day = "aeg lähedastega";
		}
		if ($hour_now>=18 and $hour_now<21) {
			$part_of_day = "sõit Tallinnasse";
		}
		if ($hour_now>21 and $hour_now<23) {
			$part_of_day = "ettevalmistused koolinädalaks";
		}
	}
	

	//vaatame semestri pikkust ja kulgemist
	$semester_begin = new DateTime("2022-09-05");
	$semester_end = new DateTime("2022-12-18");
	$semester_duration = $semester_begin->diff($semester_end);
	$semester_duration_days = $semester_duration->format("%r%a");
	$from_semester_begin = $semester_begin->diff(new DateTime("now")); 
	$from_semester_begin_days = $from_semester_begin->format("%r%a");
	
	//loendan massiivi (array liikmeid)
	//echo count($weekday_names_et);
	//juhuslik arv
	//echo mt_rand(1,9)
	//juhuslik element massiivist
	//echo $weekday_names_et[mt_rand(0, count($weekday_names_et)-1)];
	//loeme fotode kataloogi sisu
	
	$photo_dir = "photos/";
	//$all_files = scandir($photo _dir);
	//uus massiv = array_slice(massiiv, mis kohast alates);
	$all_files = array_slice(scandir($photo_dir), 2);
	//echo $all_files
	//var_dump($all_files);

	// <img src="kataloog/fail" alt="tekst">
	//tsükkel
	/*for($i=0;$i < count($all_files); $i ++){
		echo $all_files[$i] ." ";
	}*/
	/*foreach ($all_files as $file_name){
		echo $file_name ." | ";
	}*/
	
	
	
	//loetlen lubatud failitüübid (jpg, png)
	//MIME tüübid
	$allowed_photo_types = ["image/jpeg", "image/png"];
	$photo_files = [];

	foreach ($all_files as $file_name){
		$file_info = getimagesize($photo_dir .$file_name);
		if (isset($file_info["mime"])){
			if(in_array($file_info["mime"], $allowed_photo_types)){
				array_push($photo_files, $file_name); 
			}
		}
	}
	
	
	//$_POST -> massiiv, sisseehitatud muutuja
	//var_dump($_POST);
	$adjective_html = null;
	
	if(isset($_POST["todays_adjective_input"]) and !empty($_POST["todays_adjective_input"])){
		$adjective_html = "<p>Tänase kohta on arvatud: " .$_POST["todays_adjective_input"] .".</p>";
	}
	/*$photo_number = $photo_files[mt_rand(0, count($photo_files)-1)]

	
	//var_dump($photo_files);
	//$photo_html = '<img src="' .$photo_dir .$photo_files[mt_rand(0, count($photo_files)-1)].'" alt="Tallinna pilt">';
	$photo_html = null;
	$photo_html = '<img src="' .$photo_dir . $photo_files[$photo_number];
	
	//teen fotode rippmenüü
	$select_html = '<option value="" selected disabled>Vali pilt</option>';
	if($i = 0; $i < count($photo_files); $i ++){
		$select_html ='<option value=""' .$i .'""';
		if ($i == $photo_number) {
			$select_html = .= " selected";
		}
		$select_html .= '>';
		$select_html .= $photo_files[$i];
		$select_html .= "</option> \n";
	}
	
	
	
	if ((isset($_POST["photo_select"])) and ($_POST["photo_select"]>=0)) {
		//kõik mis teha tahame...
		$photo_html = '<img src="' .$photo_dir .$photo_files[$photo_number] .'" alt="Tallinna pilt">';
	}*/
	
	$comment_error = null;
	$grade=7;
	//tegeleme päevale antud hinde ja kommentaariga
	if(isset($_POST["comment_submit"])){
		if (isset($_POST["comment_submit"]) and !empty($_POST["comment_submit"])){
			$comment = $_POST["comment_input"];
		} else {
			$comment_error = "Kommentaar jäi lisamata!";
		}
		$grade = $_POST["grade_input"];
		
		if(empty($comment_error)){
			//loome andmebaasi ühenduse
			$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
			//määrame suhtlemisel kasutatava kooditabeli
			$conn->set_charset("utf8");
			//valmistame ette SQL keeles päringu
			$stmt = $conn->prepare("INSERT INTO vp_day_comment (comment, grade) VALUES(?,?)");
			echo $conn->error;
			// seome SQL päringu pärisandmetega
			//määrame andmetüübid: i -integer, d - decimal, s -string
			$stmt->bind_param("si",$comment, $grade);
			if($stmt->execute()){
				$grade = 7;
			};
			echo $stmt->error;
			//sulgeme päringu
			$stmt->close();
			//sulgeme andmebaasiühenduse
			$conn->close();
		}
	}
	
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $author_name?>, veebiprogrammeerimine</title>
</head>
<body>
	<img src="pics/vp_banner_gs.png" alt="Veebiprogrammeerimine">
	<h1><?php echo $author_name ?>, veebiprogrammeerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsist informatsiooni. </p>
	<p> Õppetöö toimus <a href="https://www.tlu.ee">Tallinna Ülikoolis</a> Digitehnoloogiate instituudis.</p>
	<p> Lehe avamise hetk: <?php echo $weekday_names_et[$weekday_now-1].", ".$full_time_now;?>.</p>
	<p>Praegu on <?php echo $part_of_day?>.</p>
	<p>Semester edeneb: <?php echo $from_semester_begin_days."/".$semester_duration_days;?></p>
	<a href="https://www.tlu.ee">
		<img src="pics/tlu_38.jpg" alt="Tallinna Ülikooli õppehoone">
	</a>
	<p>Ma olen esimese kursuse informaatika tudeng digitaalse meedia suunal.</p>
	
	<!--päeva kommentaaride lisamise vorm-->
	
	<form method ="POST">
		<label for="comment_input">Kommentaar tänase päeva kohta: </label>
		<br>
		<textarea id="comment_input" name="comment_input" cols="70" rows="2" placeholder="kommentaar"></textarea>
		<br>
		<label for="grade_input">Hinne tänasele päevale (0...10): </label>
		<input type="number" id="grade_input" name="grade_input" min="0" max="10" step=
		"1" <?php echo $grade; ?>">
		<br>
		<input type="submit" id="comment_submit" name="comment_submit" value="Salvesta">
		<span> <?php echo $comment_error; ?>
	</form>
	
	<p>Tänane tarkusetera: <?php echo $proverbs_et[mt_rand(0, count($proverbs_et)-1)];?></p>
	<!--Siin on väike omadussõnade vorm-->
	<form method="POST">
		<input type="text" id="todays_adjective_input" name="todays_adjective_input" placeholder="omadussõna tänase kohta">
		<input type="submit" id="todays_adjective_submit" name="todays_adjective_submit" value="Saada omadussõna">
	</form>
	<?php echo $adjective_html; ?>
	<hr>
	<form method="POST">
		<select id="photo_select" name="photo_select">
		<?php echo $select_html; ?>
		</select>
		<input type="submit" id="photo_submit" name="photo_submit" value="OK">
			<?php echo $photo_html; ?>
	</hr>

</body>
</html>