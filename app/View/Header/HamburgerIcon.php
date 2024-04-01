<?php

namespace App\Livewire\Navigation\Header;

use Livewire\Component;

class HamburgerIcon extends Component {

	public bool $open = false;

	public function openMenu() {
		dump('asd');
	}

	public function render() {
		return view('livewire.navigation.header.hamburger-icon');
	}
}
