<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Layout extends Component {

	public ?string $title;
	public string $appName;
	public ?string $appDescription;
	public string $colorScheme;

	/**
	 * Create a new component instance.
	 */
	public function __construct(?string $title = null, ?string $colorScheme = null) {
		$this->title = $title;
		$this->appName = config('app.name');
		$this->appDescription = config('custom.meta_data.description');
		$this->colorScheme = $colorScheme ?: (isset($_COOKIE['color-scheme']) && in_array($_COOKIE['color-scheme'], ['light', 'dark']) ? $_COOKIE['color-scheme'] : config('custom.meta_data.default_color_scheme'));
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render(): View {
		return view('components.layout');
	}
}
