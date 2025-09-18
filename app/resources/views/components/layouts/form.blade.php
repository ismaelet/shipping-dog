@props(['title', 'body', 'footer', 'method' => null, 'action' => null, 'size' => 'md'])

@push('styles')
	<style>
		main {
			--content-width: 60ch;
		}

		.form-body {
			border-block: var(--border);
		}
	</style>
@endpush

<x-layouts.main :$title>
	<x-form
		:$title
		:$body
		:$footer
		:$action
		:$method
		:$size
	/>
</x-layouts.main>
