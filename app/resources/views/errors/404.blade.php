@php

	$userAgent = request()->header('User-Agent');
	$isBot = preg_match('/bot|crawl|slurp|spider|mediapartners/i', $userAgent) ? true : false;
	$url = request()->getRequestUri();

	$whiteList = ['/media', '/system', '/wp-', '/uploads', '.well-known', '/store'];

	foreach ($whiteList as $path) {
	    if (strpos($url, $path) !== false) {
	        $isBot = true;
	        break;
	    }
	}

	if (!$isBot) {
	    Log::error('404 Not Found', [
	        'url' => $url,
	        'user_agent' => $userAgent,
	        'ip_address' => request()->ip(),
	    ]);
	}

@endphp

<x-layouts.empty>
	<div
		class="stack-content grid"
		data-local-color-scheme="dark"
	>
		<div
			class="cover-background opacity-20"
			style="background-image: url({{ Vite::image('404-background.webp') }})"
		></div>

		<div class="relative grid content-center justify-items-center gap-8">
			<x-typography.main-heading>Errore 404</x-typography.main-heading>
			<x-typography.main-subheading>Pagina non trovata</x-typography.main-subheading>
			<x-button
				href="/"
				variant="secondary"
			>
				Torna alla home
			</x-button>
		</div>
	</div>
</x-layouts.empty>
