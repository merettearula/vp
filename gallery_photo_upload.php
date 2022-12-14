<?php 
	
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
	
	require_once "fnc_photo_upload.php";
	require_once "fnc_general.php";
	require_once "../../config.php";
	require_once "classes/Photoupload.class.php";
	
	//kontrollin pildi valikud
	$file_type = null;
	$photo_error = null;
	//$file_name = null;
	$photo_file_size_limit = 1.5 * 1024 * 1024;
	$normal_photo_max_w = 800;
	$normal_photo_max_h = 450;
	$user_id = null;
	$created_on = null;
	$alttext = null;
	$privacy = null;
	$watermark = "pics/vp_logo_w100_overlay.png";
	
	//echo $normal_photo_max_w;
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["photo_submit"])){
			$alt = test_input($_POST["alt_input"]);
			$privacy = filter_var($_POST["privacy_input"], FILTER_VALIDATE_INT);
			
			if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){
				$upload = new Photoupload($_FILES["photo_input"]);
				if(empty($upload->error)){
					$upload->check_file_size($photo_file_size_limit);
				}
				if(empty($upload->error)){
					$upload->create_filename($photo_name_prefix);
				}
				if(empty($upload->error)){
					$upload->resize_photo($normal_photo_max_w, $normal_photo_max_h);
					//lisan vesimärgi
					$upload->add_watermark($watermark);
					$upload->save_photo($gallery_photo_normal_folder .$upload->file_name);
				}
				if(empty($upload->error)){
					$upload->resize_photo($thumbnail_photo_w, $thumbnail_photo_h, false);
					$upload->save_photo($gallery_photo_thumbnail_folder .$upload->file_name);
				}
				if(empty($upload->error)){
					$upload->move_original_photo($gallery_photo_original_folder .$upload->file_name);
				}
				if(empty($upload->error)){
					$photo_error = store_photo_data($upload->file_name, $alt, $privacy);
				}
				if(empty($photo_error) and empty($upload->error)){
					$photo_error = "Pilt edukalt üles laetud!";
				} else {
					$photo_error .= $upload->error;
				}
				unset($upload);

			} else {
				$photo_error = "Pildifail on valimata!";
			}
			
		}//if photo_submit
	}//if POST
	
	//htmli sidumine javascriptiga->tavaliselt päises "headis"
	//<script>src="javascript.js" defer</script>
	
	$javascripts = ["javascript/check_file_size.js"];
	
	require_once "header.php";
	
	echo "<p>Sisse loginud: " .$_SESSION["firstname"] ." " .$_SESSION["lastname"] .".</p> \n";
?>

<ul>
	<li>Logi <a href="?logout=1">välja</li>
	<li>Tagasi avalehele <a href="home.php"></a></li>
</ul>
	<hr>
	<h2>Fotode galeriisse laadimine</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label for="photo_input">Vali pildifail: </label>
		<input type="file" name="photo_input" id="photo_input">
		<br>
		<label for="alt_input">Alternatiivtekst (alt): </label>
		<input type="text" name="alt_input" id="alt_input" placeholder="alternatiivtekst...">
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_1" value="1">
		<label for="privacy_input_1">Privaatne (ainult ise näen)</label>
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_2" value="2">
		<label for="privacy_input_2">Sisseloginud kasutajatele</label>
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_3" value="3">
		<label for="privacy_input_3">Avalik (kõik näevad)</label>
		<br>
		<input type="submit" name="photo_submit" id="photo_submit" value="Lae üles">
		<span id="infoPlace"> <?php echo $photo_error;?>
	</form>
	<hr>
	<a href="gallery_public.php">Vaata lisatud pilte</a>
</ul>

<?php require_once "footer.php"; ?>


