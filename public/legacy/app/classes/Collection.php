<?php

class Collection {

	public $id;
	public $sellerId;
	public $name;
	public $img;
	public $img_m;
	public $img_cust = true;
	public $color;
	public $url;
	public $status;

	function __construct($id) {

		$row = DB::row('* FROM collections WHERE ID=' . $id);

		$this->id = $id;

		//get name
		$this->name = $row['name'];

		//get the seller ID
		$this->sellerId = $row['userID'];

		//get the status
		$this->status = $row['status'];

		//get the images
		$this->img();

		//get the urls
		$this->url = CLIENT_ROOT . '/artist/collection?id=' . $this->id;
	}

	function img() {

		$color = '#aaa';

		$img = '/uploads/shops/collections/' . $this->id . '.jpg';

		if (!file_exists(SERVER_ROOT . $img)) {

			$this->img_cust = false;

			$res = DB::select('ID, color FROM designs WHERE collection=? ORDER BY ID DESC LIMIT 1', [$this->id]);

			if ($res->num_rows) {

				$row = $res->fetch_assoc();

				$color = $row['color'];
				$img = '/uploads/designs/' . $row['ID'] . '.png';
			} else $img = false;
		}

		$this->img = $img;
		$this->img_m = str_replace('.', '_m.', $img);
		$this->color = $color;
	}
}
