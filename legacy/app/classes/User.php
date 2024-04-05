<?php

class User {

	private $data = [];

	public $id = null;
	public $buyerId;
	public $username;
	public $fname;
	public $lname;
	public $email;
	public $admin = false;
	public $manager = false;
	public $privacy;
	public $level;
	public $rank;
	public $rank_str;

	function __construct(int $id = null) {
		if ($row = DBOld::row('* FROM users WHERE ID = ?', [$id])) {
			$this->id = $id;
			$this->buyerId = $this->id;
			$this->username = $row['username'];
			$this->fname = $row['firstname'];
			$this->lname = $row['lastname'];
			$this->email = $row['email'];
			$this->privacy = $row['privacy'];
			$this->level = $row['level'];
			$this->rank_str = $this->level == 1 ? 'Artista' : 'Utente';
			$this->admin = $row['admin'];
			$this->manager = $this->admin == 2;
		} else if (isset($_SESSION['customerId'])) {
			$this->buyerId = $_SESSION['customerId'] ?? null;
		}
	}

	function __get($name) {
		if (array_key_exists($name, $this->data)) {
			return $this->data[$name];
		} else {
			switch ($name) {
				case 'icon':
					return $this->data['icon'] = $this->icon();
				case 'cover':
					return $this->data['cover'] = $this->cover();

					// retro
				case 'buyerID':
					return $this->buyerId;
			}
		}
	}

	// get the profile icon
	private function icon(): string {
		$icon = '/users/icons/' . $this->id;

		if (file_exists(public_storage_path($icon . '.webp'))) {
			return $icon . '.webp';
		}

		if (file_exists(public_storage_path($icon . '.jpg'))) {
			return $icon . '.jpg';
		}

		return '/users/icons/0.jpg';
	}

	// get the cover image
	private function cover() {
		$covers = [];

		// get the HD cover image
		$cover = '/uploads/shops/covers/' . $this->id;

		if (!file_exists(public_server_path($cover))) {
			$cover .= '.jpg';

			if (!file_exists(public_server_path($cover))) {
				$cover = '/uploads/shops/covers/0.jpg';
			}
		}

		$covers[] = $cover;

		// get the LD cover image
		$cover = '/uploads/shops/covers/' . $this->id . '_m';

		if (!file_exists(public_server_path($cover))) {
			$cover .= '.jpg';

			if (!file_exists(public_server_path($cover))) {
				$cover = '/uploads/shops/covers/0.jpg';
			}
		}

		$covers[] = $cover;

		return $covers;
	}

	// check if the user is a seller
	function is_seller() {
		return DBOld::exists('stores WHERE userID = ?', [$this->id]);
	}
}
