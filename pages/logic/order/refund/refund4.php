<?php

use Ecommerce\Product\Type;

$stepId = 4;

include_once('setup.php');

//not processed yet
if (!$processed) {

	if ($chan) {

		$row_cart = DB::row('* FROM carts WHERE ID = ?', [$id]);

		$buyer = $row_cart['buyer'];
		$shipID = $row_cart['shipID'];
		$countID = $row_cart['countID'];

		$qty = DB::count('refunds_ord WHERE cartID = ? AND type=1', [$id]);

		$newID = DB::add(
			'carts 
			(buyer, shipID, countID, payID, shipping, status, step, time) VALUES
			(?, ?, ?, 1, 6.49, 1, 5, NOW())',
			[$buyer, $shipID, $countID]
		);

		DB::update('refunds SET newID = ? WHERE cartID = ?', [$newID, $id]);

		//copy old address
		$row_addr = DB::row('* FROM addresses WHERE cartID = ?', [$id]);
		$aname = $row_addr['aname'];
		$address = $row_addr['address'];
		$num = $row_addr['number'];
		$zip = $row_addr['zip'];
		$city = $row_addr['city'];
		$region = $row_addr['region'];
		$country = $row_addr['country'];
		$email = $row_addr['email'];
		$phone = $row_addr['phone'];

		DB::add(
			'addresses 
			(buyer, cartID, aname, address, number, zip, city, region, country, email, phone, exchange) VALUES
			(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)',
			[$buyer, $newID, $aname, $address, $num, $zip, $city, $region, $country, $email, $phone]
		);

		//add comment
		DB::add('carts_data (cartID, comm_vip) VALUES (?, ?)', [$newID, 'Cambio taglia']);
	}

	//add orders
	$res_ref_ord = DB::select('* FROM refunds_ord WHERE cartID = ? AND type = 1', [$id]);

	while ($row_ref_ord = $res_ref_ord->fetch_assoc()) {

		$ordID = $row_ref_ord['ordID'];

		$row_ord = DB::row('* FROM orders WHERE ID = ?', [$ordID]);

		$productID = $row_ord['itemID'];
		$desID = DB::find('desID FROM items WHERE ID = ?', [$productID]);
		$typeID = $row_ord['typeID'];
		$class = (new Type($typeID))->class;
		$gendID = $row_ord['gendID'];
		$posID = $row_ord['posID'];
		$extID = $row_ord['extID'];

		DB::add(
			'inventory 
			(designId, typeId, colorId, sizeId, positionId, logoId)',
			[$desID, $typeID, $row_ord['colorID'], $row_ord['sizeID'], $posID, $extID]
		);

		if ($chan) {

			$sizeID = $row_ref_ord['size_new'];
			$colorID = $row_ref_ord['color_new'];
			$qty = $row_ord['quantity'];

			$ord_newID = DB::add(
				'orders 
				(buyer, cartID, itemID, typeID, colorID, sizeID, gendID, posID, extID, fullProductPrice, productGain, quantity)',
				[$buyer, $newID, $productID, $typeID, $colorID, $sizeID, $gendID, $posID, $extID, 0, 0, $qty]
			);

			DB::update('refunds_ord SET newID = ? WHERE ordID = ?', [$ord_newID, $ordID]);
		}
	}

	//set as processed
	DB::update('refunds SET processed=1 WHERE cartID = ?', [$id]);

	redirect();
}

/*if (isset($_POST['cancel'])) {
	
	DB::update('refunds SET status=1, processed=0 WHERE cartID = ?', [$id]);
	DB::delete('refunds_ord WHERE cartID = ?', [$id]);
	
	redirect('/refund');
}*/