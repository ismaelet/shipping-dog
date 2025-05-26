@php

	$userAgent = request()->header('User-Agent');
	$url = request()->getRequestUri();

	Log::error('419 expired ' . $url, [
	    'url' => $url,
	    'user_agent' => $userAgent,
	    'ip_address' => request()->ip(),
	]);

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
			<x-typography.main-heading>Errore 419</x-typography.main-heading>
			<x-typography.main-subheading>Page expired</x-typography.main-subheading>
			<x-button
				href="/"
				variant="secondary"
			>
				Torna alla home
			</x-button>
		</div>
	</div>
</x-layouts.empty>
