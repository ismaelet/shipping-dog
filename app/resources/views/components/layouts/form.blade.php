@props(['title', 'body', 'footer', 'method' => null, 'action' => null])

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
	/>
</x-layouts.main>
