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

<x-templates.main :title="$title">
	<form
		class="form grid content-center gap-8 py-16"
		action=""
		method="POST"
		enctype="multipart/form-data"
	>
		@csrf

		<x-typography.section-heading class="form-title text-center">{{ $title }}</x-typography.section-heading>

		<div class="form-body grid gap-4 py-4">
			{{ $body }}
		</div>

		<div class="form-footer grid gap-4">
			{{ $footer }}
		</div>
	</form>
</x-templates.main>
