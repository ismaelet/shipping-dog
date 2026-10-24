@props(['title', 'body', 'footer', 'method' => 'POST', 'action' => '', 'size' => 'md'])

@push('styles')
	<style>
		main {
			--spacing-content: 60ch;

			&:has(.form[data-size="lg"]) {
				--spacing-content: 80ch;
			}

			&:has(.form[data-size="xl"]) {
				--spacing-content: 100ch;
			}
		}

		.form-body {
			border-block: 1px solid var(--color-neutral-border);
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

	<x-heading
		variant="section"
		class="form-title text-center"
	>{{ $title }}</x-heading>

	<div class="form-body grid gap-4 py-4">
		{{ $body }}
	</div>

	<div class="form-footer grid gap-4">
		{{ $footer }}
	</div>
</form>
