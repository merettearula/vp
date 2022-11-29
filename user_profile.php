<?php
  require_once "../../config.php";
  require_once "fnc_user.php";
  require_once "classes/Photoupload.class.php";
  require_once "fnc_photo_upload.php";
  require_once "classes/SessionManager.class.php";
 
  SessionManager::sessionStart("vp", 0, "~arulmere/vp/", "greeny.cs.tlu.ee");

	if(!isset($_SESSION["user_id"])){
		//jõuga viiakse page.php lehele
		header("Location: page.php");
		exit();
	}

	//logime välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
		exit();
	}

  if(isset($_POST["color_submit"]) and !empty(isset($_POST["color_submit"]))){
    $description = $_POST["user_description"];
    $bgcolor = $_POST["bg_color_input"];
    $txtcolor = $_POST["txt_color_input"];
    echo profile_colors($description, $bgcolor, $txtcolor);
  }
  
  $profile_photo_max_w = 300;
  $profile_photo_max_h = 300;
  $photo_error = null;

  if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["photo_submit"])){

			if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){
				$upload = new Photoupload($_FILES["photo_input"]);
				if(empty($upload->error)){
					$upload->check_file_size($photo_file_size_limit);
				}
				if(empty($upload->error)){
					$upload->create_filename($photo_name_prefix);
				}
				if(empty($upload->error)){
					$upload->resize_photo($profile_photo_max_w, $profile_photo_max_h, false);
				}
				if(empty($upload->error)){
					$upload->save_photo($gallery_photo_profile_folder .$upload->file_name);
				}
				if(empty($upload->error)){
					$upload->move_original_photo($gallery_photo_profile_folder .$upload->file_name);
					}
				if(empty($upload->error)){
					$photo_error = store_profile_photo($upload->file_name);
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

  require_once "header.php";

echo "<p>Sisse loginud: " .$_SESSION["firstname"] ." " .$_SESSION["lastname"] .".</p> \n";
  #echo save_profile_photo();

  ?>
  <ul>
  	<li><a href="?logout=1">Logi välja</a></li>
  	<li><a href="home.php">Avalehele</a></li>
  </ul>
  <form method="POST">
    <input type="hidden" name="profile_input" id="profile_input" value="<?php echo $_SESSION["user_id"];?>">
    <label for="user_description">Muuda profiili</label>
    <br>
    <p>Vali tausta värv:</p>
    <input type="color" name="bg_color_input" id="bg_color_input">
    <br>
    <p>Vali teksti värv:</p>
    <input type="color" name="txt_color_input" id="txt_color_input">
    <br>
    <br>
    <textarea name="user_description" id="user_description" rows="5" cols="51" placeholder="Minu lühikirjeldus"></textarea>
    <br>
    <input type="submit" id="color_submit" name="color_submit" value="Salvesta">
  </form>

  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
    <h2>Profiili pildi muutmine</h2>
    <label for="photo_input">Vali pildifail: </label>
    <input type="file" name="photo_input" id="photo_input">
    <br>
    <input type="submit" name="photo_submit" id="photo_submit" value="Lae üles">
    <span><?php echo $photo_error; ?></span>
  </form>
  <br>
  <?php echo read_pfp(); ?>
  <?php require_once "footer.php"; ?>