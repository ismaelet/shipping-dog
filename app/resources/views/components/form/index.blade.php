@props(['title', 'body', 'footer', 'method' => 'POST', 'action' => ''])

@push('styles')
	<style>
		main {
			--content-width: 60ch;
		}

		.form-title {
			font-size: 2.5rem;
		}

		.form-body {
			border-block: var(--border);
		}
	</style>
@endpush

<form
	class="form grid content-center gap-8 py-16"
	action="{{ $action }}"
	method="{{ $method == 'GET' ?: 'POST' }}"
	enctype="multipart/form-data"
>
	@method($method)

	@csrf

	<x-typography.section-heading class="form-title text-center">{{ $title }}</x-typography.section-heading>

	<div class="form-body grid gap-4 py-4">
		{{ $body }}
	</div>

	<div class="form-footer grid gap-4">
		{{ $footer }}
	</div>
</form>
