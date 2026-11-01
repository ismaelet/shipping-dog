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
                    section.hidden = !Array.from(section.querySelectorAll('[data-faq-item]')).some(
                        (item) => !item.hidden,
                    );
                }

                emptyState.hidden = visibleCount > 0;
            });
        });
    </script>
@endPushOnce

<x-layouts.main :$title :$location>
    <div class="full-width min-h-full">
        <div class="mx-auto w-full max-w-375 px-5 pt-5">
            <section
                aria-labelledby="faq-title"
                class="bg-surface-400 relative min-h-75 overflow-hidden rounded-4xl sm:min-h-96"
            >
                <img
                    src="{{ Vite::image('faq/hero.webp') }}"
                    alt=""
                    class="absolute inset-0 size-full object-cover opacity-80"
                />
                <div class="from-surface-400/40 via-surface-400/20 to-surface-400/0 absolute inset-0 bg-linear-to-r"></div>

                <div class="relative z-10 flex min-h-75 items-center px-6 py-12 sm:min-h-96 sm:px-16">
                    <div class="max-w-2xl">
                        <x-eyebrow> Centro assistenza </x-eyebrow>
                        <h1
                            id="faq-title"
                            class="font-heading text-heading-400 mt-4 text-6xl leading-none font-semibold tracking-tighter sm:text-7xl lg:text-[6rem]"
                        >
                            FAQ
                        </h1>
                        <p class="text-heading-400 mt-5 max-w-xl font-sans leading-7 sm:text-base">
                            Risposte rapide alle domande più frequenti su ordini, spedizioni e vendita dei tuoi design.
                        </p>
                    </div>
                </div>
            </section>
        </div>

        <div class="mx-auto w-full max-w-5xl px-4 py-14 sm:px-8 sm:py-20">
            <label class="card border-border-400 mx-auto flex max-w-2xl cursor-text items-center gap-3 rounded-full border px-5 py-4 outline-2 outline-offset-2 outline-transparent transition-colors focus-within:outline-orange-400">
                <x-icon-search class="size-5 shrink-0" />
                <span class="sr-only">Cerca una domanda</span>
                <input
                    id="faq-search"
                    type="search"
                    placeholder="Cerca una domanda…"
                    aria-controls="faq-categories"
                    class="placeholder text-heading-400 min-w-0 flex-1 border-0 bg-transparent p-0 font-sans focus:ring-0 focus:outline-none"
                />
            </label>

            <div id="faq-categories" class="mt-14 space-y-16">
                @foreach ($faqGroups as $group)
                    <section data-faq-section class="space-y-6" aria-label="{{ $group['title'] }}">
                        <div class="flex items-center gap-5">
                            <div class="h-px flex-1 bg-white/10"></div>
                            <div class="shrink-0 text-center">
                                <p class="{{ $group['accent'] }} font-sans text-xs font-bold uppercase tracking-[2.16px]">
                                    {{ $group['eyebrow'] }}
                                </p>
                                <h2 class="font-heading text-heading-400 mt-2 text-2xl leading-9 font-semibold sm:text-[30px]">
                                    {{ $group['title'] }}
                                </h2>
                            </div>
                            <div class="h-px flex-1 bg-white/10"></div>
                        </div>

                        <div class="card border-border-400 overflow-hidden rounded-3xl border">
                            @foreach ($group['items'] as $faq)
                                <details data-faq-item class="group border-b-border-400 border-b last:border-b-0">
                                    <summary class="hover:bg-surface-500 flex min-h-16 cursor-pointer list-none items-center justify-between gap-4 px-4 py-4 transition-colors focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-orange-400 sm:px-7 sm:py-5">
                                        <span class="flex min-w-0 items-center gap-3">
                                            <img src="{{ Vite::image($group['icon']) }}" alt="" class="shrink-0" />
                                            <span class="text-heading-400 font-sans leading-6 font-semibold sm:text-base">
                                                {{ $faq['question'] }}
                                            </span>
                                        </span>
                                        <img
                                            src="{{ Vite::image('faq/faq-chevron.svg') }}"
                                            alt=""
                                            class="shrink-0 transition-transform duration-200 group-open:rotate-90"
                                        />
                                    </summary>

                                    <div class="answer-wrapper">
                                        <div class="answer">
                                            <div class="bg-surface-400 py-6 pr-4 pl-12 font-sans leading-7 sm:pr-7 sm:pl-15">
                                                <p>
                                                    @if (isset($faq['answer']))
                                                        {{ $faq['answer'] }}
                                                    @else
                                                        {{ $faq['answer_before'] }}
                                                        <a
                                                            href="{{ $faq['link']['url'] }}"
                                                            class="font-semibold text-[#00bcff] underline decoration-transparent underline-offset-4 transition-colors hover:decoration-current focus-visible:decoration-current"
                                                            >{{ $faq['link']['text'] }}</a
                                                        >{{ $faq['answer_after'] }}
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

            <p id="faq-empty-state" role="status" aria-live="polite" hidden class="py-12 text-center font-sans">
                Nessuna domanda trovata.
            </p>

            <section class="bg-primary-400 dark mt-16 rounded-3xl px-6 py-10 text-center sm:px-12 sm:py-12">
                <img src="{{ Vite::image('faq/faq-contact.svg') }}" alt="" class="mx-auto" />
                <h2 class="font-heading mt-4 text-xl font-semibold text-white sm:text-2xl">
                    Non hai trovato la risposta?
                </h2>
                <p class="mx-auto mt-2 max-w-xl font-sans leading-6 text-white/80">
                    Il nostro team è a disposizione per aiutarti con ordini, spedizioni e gestione del negozio.
                </p>

                <x-button href="{{ route('contacts') }}" color="neutral" class="animated mx-auto mt-8 w-fit">
                    Contattaci
                    <x-icon-arrow />
                </x-button>
            </section>
        </div>
    </div>
</x-layouts.main>
