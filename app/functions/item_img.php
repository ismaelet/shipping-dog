<?php

/* OLD
//generate missing images
function item_imgs_gen($product) {

	if (!DB::find('custom FROM designs WHERE ID = ?', [$product->designID])) {

		//if no special mockups or base too
		if (empty($product->type->mockups) || in_array('base', $product->type->mockups)) {

			item_img_gen($product, 'gry');
		}

		//if special mockups
		if (!empty($product->type->mockups)) foreach ($product->type->mockups as $mockup) {

			if ($mockup != 'base') item_img_gen($product, 'gry', $mockup);
		}
	}
}

//generate a specific missing image
function item_img_gen($product, $bgr, $mockup = '') {

	$desID = $product->design->id ?? DB::find('desID FROM items WHERE ID = ?', [$product->id]);

	//get mockup data
	$base = $product->type->slug . $mockup;
	$row_mock = DB::row('* FROM items_mockups WHERE base=?', [$base]);

	//fixed images
	$bgr_path = SERVER_ROOT . '/app/assets/mockups/bgr/' . $bgr . '.png';

	if ($product->color->dark && file_exists(SERVER_ROOT . '/uploads/designs/' . $desID . '_dark.png')) $des_path = SERVER_ROOT . '/uploads/designs/' . $desID . '_dark.png';
	else $des_path = SERVER_ROOT . '/uploads/designs/' . $desID . '.png';

	//clothing
	$clo_path = SERVER_ROOT . '/app/assets/mockups/' . $product->type->slug . '/' . $product->color->slug . $mockup . '.png';
	$det_path = SERVER_ROOT . '/app/assets/mockups/' . $product->type->slug . '/' . $product->color->slug . $mockup . '_det.png';

	//outputs
	$out_path = SERVER_ROOT . '/uploads/items/' . $product->type->slug . '/' . $product->id . '_' . $product->color->slug . $mockup . '.jpg';
	$out_path_m = SERVER_ROOT . '/uploads/items/' . $product->type->slug . '/' . $product->id . '_' . $product->color->slug . $mockup . '_m.jpg';

	if (file_exists($clo_path) && file_exists($des_path)) {

		$product->preview = new Ecommerce\Product_Preview(DB::row('size, pos_x, pos_y FROM items WHERE ID = ?', [$product->id]));

		//make directory if doesn't exist
		if (!file_exists(SERVER_ROOT . '/uploads/items/' . $product->type->slug)) mkdir(SERVER_ROOT . '/uploads/items/' . $product->type->slug, 0777, true);

		//imagecopyresized ( resource $des_image , resource $src_image , int $des_x , int $des_y , int $src_x , int $src_y , int $des_w , int $des_h , int $src_w , int $src_h )

		//SAVE HD
		$x = $y = 1024;

		$out = imagecreatetruecolor($x, $y);

		//add background
		$img = imagecreatefrompng($bgr_path);
		$src_size = getimagesize($bgr_path);
		$src_w = $src_size[0];
		$src_h = $src_size[1];

		imagecopyresampled($out, $img, 0, 0, 0, 0, $x, $y, $src_w, $src_h);

		//add clothing
		$img = imagecreatefrompng($clo_path);
		$src_size = getimagesize($clo_path);
		$src_w = $src_size[0];
		$src_h = $src_size[1];

		imagecopyresampled($out, $img, 0, 0, 0, 0, $x, $y, $src_w, $src_h);

		//add design
		$img = imagecreatefrompng($des_path);
		$src_size = getimagesize($des_path);
		$src_w = $src_size[0];
		$src_h = $src_size[1];

		$sX = $sY = strpos($mockup, 'scu') ? 1 : $product->preview->size / 100;
		$pX = strpos($mockup, 'scu') ? .5 : $product->preview->pos_x / 100;
		$pY = strpos($mockup, 'scu') ? .5 : $product->preview->pos_y / 100;
		$pX_min = $row_mock['pos_x_min'] / 100;
		$pY_min = $row_mock['pos_y_min'] / 100;
		$sX_max = $row_mock['siz_x_max'] / 100;
		$sY_max = $row_mock['siz_y_max'] / 100;

		$ratio = $src_w / $src_h;
		$land = ($ratio > $sX_max / $sY_max) ? true : false;

		if ($land) {
			$des_w = $x * $sX_max * $sX;
			$des_h = $des_w / $ratio;
		} else {
			$des_h = $y * $sY_max * $sY;
			$des_w = $des_h * $ratio;
		}

		$des_x = ($x * $pX_min) + ($x * $sX_max - $des_w) * $pX;
		$des_y = ($y * $pY_min) + (($y * $sY_max - $des_h)) * $pY;

		imagecopyresampled($out, $img, $des_x, $des_y, 0, 0, $des_w, $des_h, $src_w, $src_h);

		//add details
		if (file_exists($det_path)) {
			$img = imagecreatefrompng($det_path);
			$src_size = getimagesize($det_path);
			$src_w = $src_size[0];
			$src_h = $src_size[1];

			imagecopyresampled($out, $img, 0, 0, 0, 0, $x, $y, $src_w, $src_h);
		}

		//generate image
		imagejpeg($out, $out_path, 90);
		imagedestroy($out);

		//SAVE LD
		$x_m = $y_m = 400;

		$out = imagecreatetruecolor($x_m, $y_m);
		$des = imagecreatefromjpeg($out_path);

		imagecopyresampled($out, $des, 0, 0, 0, 0, $x_m, $y_m, $x, $y);
		imagejpeg($out, $out_path_m, 80);
		imagedestroy($out);
	}
}
*/