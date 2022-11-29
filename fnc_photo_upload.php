<?php
require_once "../../config.php";

//klassi
/*function check_file_type($file){
  $file_type = 0;
  $image_check = getimagesize($file);
  if($image_check !== false){
    if($image_check["mime"] == "image/jpeg"){
      $file_type = "jpg";
    }
    if($image_check["mime"] == "image/png"){
      $file_type = "png";
    }
    if($image_check["mime"] == "image/gif"){
      $file_type = "gif";
    }
  }
  return $file_type;
}*/

//klassi
/*function create_filename($photo_name_prefix, $file_type){
  $timestamp = microtime(1) * 10000;
  return $photo_name_prefix .$timestamp ."." .$file_type;
}
/*
//klassi
/*function create_image($file, $file_type){
  $temp_image = null;
  if($file_type == "jpg"){
    $temp_image = imagecreatefromjpeg($file);
  }
  if($file_type == "png"){
    $temp_image = imagecreatefrompng($file);
  }
  if($file_type == "gif"){
    $temp_image = imagecreatefromgif($file);
  }
  return $temp_image;
}*/

function resize_photo_thumbnail($temp_photo, $thumbnail_max_w, $thumbnail_max_h) {
	$image_w = imagesx($temp_photo);
	$image_h = imagesy($temp_photo);
	$new_w_thumbnail = $thumbnail_max_w;
	$new_h_thumbnail = $thumbnail_max_h;
	if($image_w / $thumbnail_max_w > $image_h / $thumbnail_max_h){
		$new_h_thumbnail = round($image_h / ($image_w / $thumbnail_max_w));
	} else {
		$new_w_thumbnail = round($image_w / ($image_h / $thumbnail_max_h));
	}
	$temp_image = imagecreatetruecolor($new_w_thumbnail, $new_h_thumbnail);
  imagecopyresampled($temp_image, $temp_photo, 0, 0, 0, 0, $new_w_thumbnail, $new_h_thumbnail, $image_w, $image_h);
	return $temp_image;
}

//klassi
/*function save_photo($image, $target, $file_type){
	$error = null;
	if($file_type == "jpg"){
		if(imagejpeg($image, $target, 95) == false){
			$error = 1;
		}
	}
	if($file_type == "png"){
		if(imagepng($image, $target, 6) == false){
			$error = 1;
		}
	}
	if($file_type == "gif"){
		if(imagegif($image, $target) == false){
			$error = 1;
		}
	}
	return $error;
}*/

//klassi
/*function resize_photo($temp_photo, $w, $h, $keep_orig_proportion = true){
		$image_w = imagesx($temp_photo);
		$image_h = imagesy($temp_photo);
		$new_w = $w;
		$new_h = $h;
		//uued muutujad, mis on seotud proportsioonide muutmisega, kärpimisega (crop)
		$cut_x = 0;
		$cut_y = 0;
		$cut_size_w = $image_w;
		$cut_size_h = $image_h;
		if ($keep_orig_proportion){//säilitan originaalproportsioonid
			if($image_w / $w > $image_h / $h){
				$new_h = round($image_h / ($image_w / $w));
			} else {
				$new_w = round($image_w / ($image_h / $h));
			}
		} else { //kui on vaja kindlat suurust, kärpimist
			if($image_w > $image_h){
				$cut_size_w = $image_h;
				$cut_x = round(($image_w - $cut_size_w) / 2);
			} else {
				$cut_size_h = $image_w;
				$cut_y = round(($image_h - $cut_size_h) / 2);
			}
		}
		$temp_image = imagecreatetruecolor($new_w, $new_h);
		//säilitame vajadusel läbipaistvuse (png ja gif piltide jaoks
        imagesavealpha($temp_image, true);
        $trans_color = imagecolorallocatealpha($temp_image, 0, 0, 0, 127);
        imagefill($temp_image, 0, 0, $trans_color);
		//teeme originaalist väiksele koopia
		imagecopyresampled($temp_image, $temp_photo, 0, 0, $cut_x, $cut_y, $new_w, $new_h, $cut_size_w, $cut_size_h);
		return $temp_image;
	}*/

  function store_photo_data($file_name, $alt, $privacy){
  	$notice = null;
  	$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
  	$conn->set_charset("utf8");
  	$stmt = $conn->prepare("INSERT INTO vp_photos (userid, filename, alttext, privacy) VALUES (?, ?, ?, ?)");
  	echo $conn->error;
  	$stmt->bind_param("issi", $_SESSION["user_id"], $file_name, $alt, $privacy);
  	if($stmt->execute() == false){
  	  $notice = "Pildi andmebaasi salvestamine ebaõnnestus!";
  	}
  	$stmt->close();
  	$conn->close();
  	return $notice;
  }

	function store_profile_photo($file_name){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("INSERT INTO vp_userprofilephotos (userid, file_name) VALUES (?, ?)");
		echo $conn->error;
		$stmt->bind_param("is", $_SESSION["user_id"], $file_name);
		if($stmt->execute() == false){
			$notice = "Pildi andmebaasi salvestamine ebaõnnestus!";
		} else {
			$_SESSION["picture_id"] = $conn->insert_id;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	/*function save_profile_photo(){
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("INSERT INTO vp_userprofiles (picture) WHERE userid = ?");
		echo $conn->error;
		$stmt->bind_param("i", $_SESSION["user_id"]);
		if($stmt->execute()){
			$pfp = $conn->insert_id;
		}
		$stmt->error;
		$stmt->close();
		$conn->close();
	}
	*/

	function read_pfp(){
		$photo_html = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id, userid, file_name FROM vp_userprofilephotos WHERE id = (SELECT max(id) FROM vp_userprofilephotos WHERE userid = ? AND deleted IS NULL)");
		echo $conn->error;
		$stmt->bind_param("i", $_SESSION["user_id"]);
		$stmt->bind_result($id_from_db, $userid_from_db, $filename_from_db);
		$stmt->execute();
		if($stmt->fetch()){
	//<img src="kataloog/fail" alt="tekst">
	//<img src="show_public_photo.php?74" alt="tekst">
			$photo_html = '<img src="' .$GLOBALS["gallery_photo_profile_folder"] .$filename_from_db .'" alt="profiili pilt">' ."\n";
			//$photo_html = '<img src="user_profile.php?photo=' .$id_from_db .'" alt="profiili pilt">' ."\n";
			if(empty($userid_from_db)){
				$photo_html = "<p>Avalikke pilte pole!</p>";
		}
		}
		$stmt->close();
		$conn->close();
		return $photo_html;
	}