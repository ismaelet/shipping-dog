<?php

/*
//generate a specific missing image
function des_img_gen($design) {

	//design path
	$des_path = SERVER_ROOT . '/uploads/designs/' . $design->id . '.png';

	//outputs
	$dst_image_path = SERVER_ROOT . '/uploads/designs/previews/' . $design->id . '.jpg';
	$dst_image_path_m = SERVER_ROOT . '/uploads/designs/previews/' . $design->id . '_m.jpg';

	if (file_exists($des_path)) {

		//make directory if doesn't exist
		if (!file_exists($dir = SERVER_ROOT . '/uploads/designs/previews')) mkdir($dir, 0777, true);

		//SAVE HD
		$w = $h = 1024;
		$w_m = $h_m = 400;

		$dst_image = imagecreatetruecolor($w, $h);

		//bgr
		$src_image = imagecreatetruecolor($w, $h);

		if (isset($design->color)) $design->color = DBOld::find('color FROM designs WHERE ID = ?', [$design->id]);

		$color_hex = DBOld::find('color FROM designs WHERE ID = ?', [$design->id]);
		if ($color_hex == '#FFFFFF') $color = imagecolorallocate($src_image, 255, 255, 255);
		else $color = imagecolorallocate($src_image, 0, 0, 0);

		imagefilledrectangle($src_image, 0, 0, $w - 1, $h - 1, $color);

		imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $w, $h, $w, $h);

		//design
		$src_image = imagecreatefrompng($des_path);

		$old_size = getimagesize($des_path);
		$src_w = $old_size[0];
		$src_h = $old_size[1];
		$ratio = $src_w / $src_h;

		if ($ratio > 1) {
			$dst_w = $w;
			$dst_h = $h / $ratio;
			$dst_x = 0;
			$dst_y = ($h - $dst_h) / 2;
		} else {
			$dst_w = $w * $ratio;
			$dst_h = $h;
			$dst_x = ($w - $dst_w) / 2;
			$dst_y = 0;
		}

		imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
		imagejpeg($dst_image, $dst_image_path, 90);

		imagedestroy($src_image);
		imagedestroy($dst_image);

		//SAVE LD
		$src_image = imagecreatefromjpeg($dst_image_path);
		$dst_image = imagecreatetruecolor($w_m, $h_m);

		imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $w_m, $h_m, $w, $h);
		imagejpeg($dst_image, $dst_image_path_m, 80);

		imagedestroy($src_image);
		imagedestroy($dst_image);
	}
}
*/