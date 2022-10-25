<?php
require_once "../../config.php";

function check_file_type($file){
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
}

function create_filename($photo_name_prefix, $file_type) {
	//väljendab praegust ajahetke
	$timestamp = microtime(1)*10000;
	return $photo_name_prefix .$timestamp .".".$file_type;
}

function create_image($file, $file_type){
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
}
function resize_photo($temp_photo, $normal_photo_max_w, $normal_photo_max_h){
	//originaal pildi suurus
	$image_w = imagesx($temp_photo);
	$image_h = imagesy($temp_photo);
	$new_w = $normal_photo_max_w;
	$new_h = $normal_photo_max_h;
	//säilitan proportsiooni
	if($image_w / $normal_photo_max_w > $image_h / $normal_photo_max_h){
		$new_h = round($image_h / ($image_w /$normal_photo_max_w));
	} else {
		$new_w = round($image_w / ($image_h /$normal_photo_max_h));
	}
	$temp_image = imagecreatetruecolor($new_w, $new_h);
	//1.mis image objektile, 2.mis objektist, 3-4.mis koordinaatidele x,y, 5.mis koordinaatidelt kärpida/võtta x,y, 6.destination laius kust võtame,7.destination kõrgus kust võtame, 8-9 originaal pildi mõõtmed
	imagecopyresampled($temp_image, $temp_photo, 0, 0, 0, 0, $new_w, $new_h, $image_w, $image_h);
	return $temp_image;
}

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
	//1.mis image objektile, 2.mis objektist, 3-4.mis koordinaatidele x,y, 5.mis koordinaatidelt kärpida/võtta x,y, 6.destination laius kust võtame,7.destination kõrgus kust võtame, 8-9 originaal pildi mõõtmed
	imagecopyresampled($temp_image, $temp_photo, 0, 0, 0, 0, $new_w_thumbnail, $new_h_thumbnail, $image_w, $image_h);
	return $temp_image;
}

function save_photo($photo, $target, $file_type){
	if($file_type == "jpg"){
		imagejpeg($photo, $target, 95);
	}
	if($file_type == "png") {
		imagepng($photo, $target, 6);
	}
	if($file_type == "gif") {
		imagegif($photo, $target);
	}
}

