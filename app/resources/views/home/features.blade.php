@pushOnce('styles')
	<style>
		.features {
			--td: 500ms;
			--tf: cubic-bezier(0.34, 1.28, 0.82, 1);
		}

		.feature {
			transition: translate var(--td) var(--tf);

			&:hover {
				translate: 0 -0.25rem;
			}
		}
	</style>
@endpushOnce

<section class="features mx-auto grid grid-cols-1 gap-3 py-8 md:grid-cols-3">
	@foreach ($features as $key => $feature)
		<div
			class="feature text-neutral-heading flex items-center gap-4 rounded-2xl border border-slate-950/10 bg-white p-5 dark:border-white/10 dark:bg-slate-800"
		>
			{{-- Icon --}}
			<div
				class="dark:text-primary-300 grid size-12 shrink-0 place-items-center rounded-full bg-orange-100 text-orange-600 dark:bg-orange-500/15 [&_svg]:size-6"
			>
				<x-dynamic-component :component="'icon-' . $feature['icon']" />
			</div>

			{{-- Content --}}
			<div class="min-w-0 flex-1">
				<div class="flex items-center justify-between gap-3">
					<h3 class="font-semibold">
						{{ $feature['title'] }}
					</h3>

					<span class="text-[10px] font-bold tracking-widest text-slate-300 dark:text-slate-600">
						{{ sprintf('%02d', $key + 1) }}
					</span>
				</div>

				<p class="mt-1 text-sm leading-6">
					{{ $feature['description'] }}
				</p>
			</div>
		</div>
	@endforeach
</section>
