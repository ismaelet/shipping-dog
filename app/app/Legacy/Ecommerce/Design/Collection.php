<?php

namespace App\Legacy\Ecommerce\Design;

use App\Legacy\System\DB;

class Collection {

	public $id;
	public $seller_id;
	public $name;
	public $img;
	public $img_m;
	public $img_cust = true;
	public $color;
	public $url;
	public $status;

	function __construct($id) {

		$row = DB::row('* FROM collections WHERE id=' . $id);

		$this->id = $id;

		//get name
		$this->name = $row['name'];

		//get the seller ID
		$this->seller_id = $row['user_id'];

		//get the status
		$this->status = $row['status'];

		//get the images
		$this->img();

		//get the urls
		$this->url =  '/artist/collection?id=' . $this->id;
	}

	function img() {

		$color = '#aaa';

		$img = '/shops/collections/' . $this->id . '.jpg';

		if (!file_exists(public_storage($img))) {

			$this->img_cust = false;

			$res = DB::select('id, color FROM designs WHERE collection=? ORDER BY id DESC LIMIT 1', [$this->id]);

			if ($res->num_rows) {

				$row = $res->fetch_assoc();

				$color = $row['color'];
				$img = '/designs/' . $row['id'] . '.png';
			} else $img = false;
		}

		$this->img = $img;
		$this->img_m = str_replace('.', '_m.', $img);
		$this->color = $color;
	}
}
