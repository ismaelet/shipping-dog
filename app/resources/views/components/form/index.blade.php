@props(['title', 'body', 'footer', 'method' => 'POST', 'action' => '', 'size' => 'md'])

@push('styles')
	<style>
		main {
			--content-width: 60ch;

			&:has(.form[data-size="lg"]) {
				--content-width: 80ch;
			}

			&:has(.form[data-size="xl"]) {
				--content-width: 100ch;
			}
		}

		.form-title {
			font-size: var(--text-750)
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
	data-size="{{ $size }}"
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
