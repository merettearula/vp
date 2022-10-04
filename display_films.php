<?php
require_once "../config.php";
//loome andmebaasiühenduse
$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
//määrame suhtlemisel kasutatava kooditabeli
$conn->set_charset("utf8");
//valmistame ette SQL keeles päringu
$stmt = $conn->prepare("SELECT pealkiri, aasta, kestus, zanr, tootja, lavastaja FROM film");
echo $conn->error;
//seome loetavad andmed muutujatega
$stmt->bind_result($title, $year, $duration, $genre, $studio, $director);
//täidame käsu
$stmt->execute();
echo $stmt->error;

$film_html = null;
while($stmt->fetch()){
	$film_html .= "<h3>" .$title ."</h3>"
  ."<ul>"
  ."<li>Valmimisaasta:" .$year ."</li>"
    ."<li>Kestus:" .$duration ."</li>"
  ."<li>Žanr:" .$genre ."</li>"
    ."<li>Tootja:" .$studio ."</li>"
    ."<li>Lavastaja:" .$director ."</li>"
  ."</ul>";
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Merette Arula, veebiprogrammeerimine</title>
</head>
<body>
	<h1>Merette Arula, veebiprogrammeerimine</h1>
	<img src="pics/vp_banner_gs.png" alt="Veebiprogrammeerimine">
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsist informatsiooni. </p>
	<p> Õppetöö toimus <a href="https://www.tlu.ee">Tallinna Ülikoolis</a> Digitehnoloogiate instituudis.</p>
	<a href="https://www.tlu.ee">
		<img src="pics/tlu_38.jpg" alt="Tallinna Ülikooli õppehoone">
	</a>
	<p>Ma olen esimese kursuse informaatika tudeng digitaalse meedia suunal.</p>
	<?php echo film_html; ?>
</body>
</html>