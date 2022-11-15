<?php 
	
	session_start();
	
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
	require_once "../../config.php";
	require_once "classes/Photoupload.class.php";
	
	//kontrollin pildi valikud
	$file_type = null;
	$photo_error = null;
	$file_name = null;
	$photo_file_size_limit = 1.5 * 1024 * 1024;
	$normal_photo_max_w = 800;
	$normal_photo_max_h = 450;
	$user_id = null;
	$created_on = null;
	$alttext = null;
	$privacy = null;
	
	//echo $normal_photo_max_w;
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["photo_submit"])){
			//var_dump($_POST);
			//kas on üldse pildifail ja mis tüüpi
			if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){
				$file_type = check_file_type($_FILES["photo_input"]["tmp_name"]);
				if($file_type==0){
					$photo_error = "Valitud fail pole sobivat tüüpi";
				}
			} else {
				$photo_error = "Pildifail on valimata";
			}
			//faili suurus
			if(empty($photo_error)){
				if($_FILES["photo_input"]["size"]>$photo_file_size_limit){
					$photo_error = "Valitud fail on liiga suur";
				}
			}
			if(empty($photo_error)){
				
				//loon uue failinime
				$file_name = create_filename($photo_name_prefix, $file_type);
				//teen väiksema normaalmõõdus pildi
				
				//klass
				$upload = new Photoupload($_FILES["photo_input"]);
				
				
				//loome pikslikogumi ehk image objekti
				//$temp_photo = create_image($_FILES["photo_input"]["tmp_name"], $file_type);
				//teeme väiksemaks
				//$normal_photo = resize_photo($temp_photo, $normal_photo_max_w, $normal_photo_max_h);
				$upload->resize_photo($normal_photo_max_w, $normal_photo_max_h);
				
				//salvestan väiksemaks tehtud pildi
				//save_photo($normal_photo, "photo_upload_normal/" .$file_name, $file_type);
				
				$upload->save_photo($gallery_photo_normal_folder .$file_name, $upload->file_type);
				
				// //tõstan ajutise pildifaili oma soovitud kohta
				// move_uploaded_file($_FILES["photo_input"]["tmp_name"], "photo_upload_original/" .$file_name);
				
				// $thumbnail_photo = resize_photo_thumbnail($temp_photo, $thumbnail_photo_w, $thumbnail_photo_h);
				// //salvestan väiksemaks tehtud pildi
		
				
				// //tõstan ajutise pildifaili oma soovitud kohta
				// save_photo($thumbnail_photo, "photo_upload_thumbnail/" .$file_name, $file_type);
				// move_uploaded_file($_FILES["photo_input"]["tmp_name"], "photo_upload_thumbnail/" .$file_name);
								
				// $photo_to_db_error = null;
				// $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
				// $conn->set_charset("utf8");
				// $stmt = $conn->prepare("INSERT INTO vp_photos (userid, filename, alttext, privacy) VALUES(?,?,?,?)");
				// echo $conn->error;			
				
				$user_id = $_SESSION["user_id"];
				$file_name = $file_name;
				//$created_on = microtime(1)*10000;
				$alt = $_POST["alt_input"];
				$privacy = $_POST["privacy_input"];
				
				/*if(!empty($user_id) and !empty($file_name) and !empty($alttext) and !empty($privacy)){
					$stmt->bind_param("issi", $user_id, $file_name, $alttext, $privacy);
					$stmt->execute();
				} else {
					$photo_to_db_error = "Ei õnnestunud fotot andmebaasi sisestada";
				}*/
				if(empty($upload->error)){
					//$photo_error = store_photo_data($file_name, $alt, $privacy);
					$upload->resize_photo($thumbnail_photo_w, $thumbnail_photo_h, false);
					$upload->save_photo($gallery_photo_thumbnail_folder .$file_name, $upload->file_type);
				}
				if(empty($upload->error)){
					// ajutine fail: $_FILES["photo_input"]["tmp_name"]
					if(move_uploaded_file($_FILES["photo_input"]["tmp_name"], $gallery_photo_original_folder .$file_name) == false){
						$photo_error = 1;
					}
				}
				if(empty($upload->error)){
					$photo_error = store_photo_data($file_name, $alt, $privacy);
				}
				if(empty($photo_error)){
					$photo_error = "Pilt edukalt üles laetud!";
					$alt = null;
					$privacy = 1;
				} else {
					$photo_error = "Pildi üleslaadimisel tekkis tõrkeid!";
				}
				
				unset($upload);
				
				// if(empty($photo_error)){
					// $photo_error = "Pilt edukalt üles laetud!";
					// $alt = null;
					// $privacy = 1;
				// } else {
					// $photo_error = "Pildi üleslaadimisel tekkis tõrkeid!";
				// }
				// echo $stmt->error;
				// $stmt->close();
				// $conn->close();
				
			} //if empty error
		} //if photo submit
	}//if post
	
	
	require_once "header.php";
?>
<ul>
	<p> Sisse logitud: <?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"]; ?>
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
		<span><?php echo $photo_error;?>
	</form>
	<hr>
	<a href="gallery_public.php">Vaata lisatud pilte</a>
</ul>

<?php require_once "footer.php"; ?>


