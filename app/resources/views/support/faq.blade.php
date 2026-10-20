@pushOnce('styles')
	<style>
		details::details-content {
			display: block;
			content-visibility: visible;
		}

		.answer-wrapper {
			display: grid;
			grid-template-rows: 0fr;
			transition: grid-template-rows 0.3s ease;
		}

		details[open] .answer-wrapper {
			grid-template-rows: 1fr;
		}

		.answer {
			min-height: 0;
			overflow: hidden;
		}
	</style>
@endpushOnce

@pushOnce('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const search = document.getElementById('faq-search');
			const items = Array.from(document.querySelectorAll('[data-faq-item]'));
			const sections = Array.from(document.querySelectorAll('[data-faq-section]'));
			const emptyState = document.getElementById('faq-empty-state');

			if (!search || !emptyState) return;

			search.addEventListener('input', () => {
				const query = search.value.trim().toLocaleLowerCase();
				let visibleCount = 0;

				for (const item of items) {
					const matches = item.textContent.toLocaleLowerCase().includes(query);
					item.hidden = !matches;

					if (matches) visibleCount++;
				}

				for (const section of sections) {
					section.hidden = !Array.from(section.querySelectorAll('[data-faq-item]'))
						.some(item => !item.hidden);
				}

				emptyState.hidden = visibleCount > 0;
			});
		});
	</script>
@endPushOnce

<x-layouts.main
	:$title
	:$location
	color-scheme="dark"
>
	<div class="full-width min-h-full bg-[#0f172b]">
		<div class="mx-auto w-full max-w-[1500px] px-5 pt-5">
			<section
				aria-labelledby="faq-title"
				class="relative min-h-[300px] overflow-hidden rounded-[32px] bg-[#020618] sm:min-h-[384px]"
			>
				<img
					src="{{ Vite::image('faq-hero.png') }}"
					alt=""
					class="absolute inset-0 size-full object-cover opacity-55"
				>
				<div class="absolute inset-0 bg-gradient-to-r from-[#020618]/90 via-[#020618]/65 to-[#020618]/30"></div>

				<div class="relative z-10 flex min-h-[300px] items-center px-6 py-12 sm:min-h-[384px] sm:px-16">
					<div class="max-w-[672px]">
						<p class="font-sans text-xs font-bold uppercase tracking-[2.4px] text-[#ff8904]">
							Centro assistenza
						</p>
						<h1
							id="faq-title"
							class="font-heading mt-4 text-6xl font-semibold leading-none tracking-[-0.05em] text-white sm:text-7xl lg:text-[6rem]"
						>
							FAQ
						</h1>
						<p class="mt-5 max-w-xl font-sans leading-7 text-white/70 sm:text-base">
							Risposte rapide alle domande più frequenti su ordini, spedizioni e vendita dei tuoi design.
						</p>
					</div>
				</div>
			</section>
		</div>

		<div class="mx-auto w-full max-w-[1024px] px-4 py-14 sm:px-8 sm:py-20">
			<label
				class="mx-auto flex max-w-[672px] items-center gap-3 rounded-full border border-white/10 bg-[#1d293d] px-5 py-4 shadow-sm focus-within:border-orange-400 focus-within:ring-2 focus-within:ring-orange-500/30"
			>
				<img
					src="{{ Vite::image('faq-search.svg') }}"
					alt=""
					class="shrink-0"
				>
				<span class="sr-only">Cerca una domanda</span>
				<input
					id="faq-search"
					type="search"
					placeholder="Cerca una domanda…"
					aria-controls="faq-categories"
					class="placeholder min-w-0 flex-1 border-0 bg-transparent p-0 font-sans text-[#f5f5f4] focus:outline-none focus:ring-0"
				>
			</label>

			<div
				id="faq-categories"
				class="mt-14 space-y-16"
			>
				@foreach ($faqGroups as $group)
					<section
						data-faq-section
						class="space-y-6"
						aria-label="{{ $group['title'] }}"
					>
						<div class="flex items-center gap-5">
							<div class="h-px flex-1 bg-white/10"></div>
							<div class="shrink-0 text-center">
								<p class="{{ $group['accent'] }} font-sans text-xs font-bold uppercase tracking-[2.16px]">
									{{ $group['eyebrow'] }}
								</p>
								<h2 class="font-heading mt-2 text-2xl font-semibold leading-9 text-[#f5f5f4] sm:text-[30px]">
									{{ $group['title'] }}
								</h2>
							</div>
							<div class="h-px flex-1 bg-white/10"></div>
						</div>

						<div class="overflow-hidden rounded-3xl border border-white/10 bg-[#1d293d] shadow-sm">
							@foreach ($group['items'] as $faq)
								<details
									data-faq-item
									class="group border-b border-white/10 last:border-b-0"
								>
									<summary
										class="flex min-h-16 cursor-pointer list-none items-center justify-between gap-4 px-4 py-4 transition-colors hover:bg-white/[0.03] focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-orange-400 sm:px-7 sm:py-5"
									>
										<span class="flex min-w-0 items-center gap-3">
											<img
												src="{{ Vite::image($group['icon']) }}"
												alt=""
												class="shrink-0"
											>
											<span class="font-sans font-semibold leading-6 text-[#f5f5f4] sm:text-base">
												{{ $faq['question'] }}
											</span>
										</span>
										<img
											src="{{ Vite::image('faq-chevron.svg') }}"
											alt=""
											class="shrink-0 transition-transform duration-200 group-open:rotate-90"
										>
									</summary>

									<div class="answer-wrapper">
										<div class="answer">
											<div class="bg-background-400 sm:pl-15 py-6 pl-12 pr-4 font-sans leading-7 sm:pr-7">
												<p>
													@if (isset($faq['answer']))
														{{ $faq['answer'] }}
													@else
														{{ $faq['answer_before'] }}
														<a
															href="{{ $faq['link']['url'] }}"
															class="font-semibold text-[#00bcff] underline decoration-transparent underline-offset-4 transition-colors hover:decoration-current focus-visible:decoration-current"
														>{{ $faq['link']['text'] }}</a>{{ $faq['answer_after'] }}
													@endif
												</p>
											</div>
										</div>
									</div>
								</details>
							@endforeach
						</div>
					</section>
				@endforeach
			</div>

			<p
				id="faq-empty-state"
				role="status"
				aria-live="polite"
				hidden
				class="py-12 text-center font-sans"
			>
				Nessuna domanda trovata.
			</p>

			<section class="mt-16 rounded-3xl bg-[#ff6900] px-6 py-10 text-center sm:px-12 sm:py-12">
				<img
					src="{{ Vite::image('faq-contact.svg') }}"
					alt=""
					class="mx-auto"
				>
				<h2 class="font-heading mt-4 text-xl font-semibold text-white sm:text-2xl">
					Non hai trovato la risposta?
				</h2>
				<p class="mx-auto mt-2 max-w-xl font-sans leading-6 text-white/80">
					Il nostro team è a disposizione per aiutarti con ordini, spedizioni e gestione del negozio.
				</p>

				<a
					href="{{ route('contacts') }}"
					class="mt-5 inline-flex items-center gap-2 rounded-full bg-white px-5 py-2.5 font-sans font-bold text-[#0f172b] transition-colors hover:bg-[#f5f5f4] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
				>
					Contattaci
					<img
						src="{{ Vite::image('faq-cta-arrow.svg') }}"
						alt=""
					>
				</a>
			</section>
		</div>
	</div>
</x-layouts.main>
