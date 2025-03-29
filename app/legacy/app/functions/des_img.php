<?php

use App\Models\Ecommerce\Design;

function des_img_gen(int $design_id): void {

	$design = Design::find($design_id);

	//design path
	$des_path = public_storage('designs/' . $design->id . '.png');

	//outputs
	$dst_image_path = public_storage('designs/previews/' . $design->id . '.webp');
	$dst_image_path_m = public_storage('designs/previews/' . $design->id . '_m.webp');

	if (file_exists($des_path)) {

		//make directory if doesn't exist
		if (!file_exists($dir = public_storage('designs/previews'))) {
			mkdir($dir, 0777, true);
		}

		//SAVE HD
		$w = $h = 1024;
		$w_m = $h_m = 400;

		$dst_image = imagecreatetruecolor($w, $h);

		//bgr
		$src_image = imagecreatetruecolor($w, $h);

		if (isset($design->color)) $design->color = \Legacy\System\Classes\LegacyDB::find('color FROM designs WHERE id = ?', [$design->id]);

		$rgba = parse_rgba(\Legacy\System\Classes\LegacyDB::find('color FROM designs WHERE id = ?', [$design->id]));
		$color = imagecolorallocate($src_image, $rgba[0], $rgba[1], $rgba[2]);

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
		imagewebp($dst_image, $dst_image_path, 90);

		imagedestroy($src_image);
		imagedestroy($dst_image);

		//SAVE LD
		$src_image = imagecreatefromwebp($dst_image_path);
		$dst_image = imagecreatetruecolor($w_m, $h_m);

		imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $w_m, $h_m, $w, $h);
		imagewebp($dst_image, $dst_image_path_m, 80);

		imagedestroy($src_image);
		imagedestroy($dst_image);
	}
}
