@pushOnce('styles')
    <style>
        #body {
            padding-top: 8rem;
            padding-bottom: 7rem;
        }

        #grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 3rem;
            align-items: start;
        }

        .data-txt {
            margin-bottom: 10px;
        }

        .form-label {
            padding-top: 1rem;
            transform-origin: left;
            transform: translateY(2rem) scale(1);
            line-height: 1rem;
            pointer-events: none;
            z-index: 1;
            transition:
                transform 0.2s,
                color 0.2s;
        }

        .contact-form-field:has(:focus) .form-label,
        .contact-form-field:has(input:not(:placeholder-shown)) .form-label,
        .contact-form-field:has(textarea:not(:placeholder-shown)) .form-label {
            color: var(--color-primary-400);
            transform: translateY(0.5rem) scale(0.9);
        }

        .form-inp,
        .form-textarea {
            display: inline-block;
            background-color: transparent;
            width: 100%;
            border: none;
            border-bottom: 1px solid var(--color-neutral-400);
            border-radius: 0;
            padding: 0;
            margin: 0;
            color: var(--color-heading-400);
            font-size: 0.9rem;
            box-sizing: border-box;
            outline: none !important;
            transition: border-color 0.2s;
            line-height: 3rem;
        }

        .form-textarea {
            height: 11rem;
            padding-top: 1rem;
            resize: none;
        }

        .form-inp:-webkit-autofill,
        .form-textarea:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000vw var(--color-background-400) inset;
            -webkit-text-fill-color: var(--color-heading-400);
        }

        .contact-form-field:has(:focus) .form-inp,
        .contact-form-field:has(:focus) .form-textarea {
            border-color: var(--color-primary);
        }

        @media (max-width: 1024px) {
            #body {
                padding: 0;
            }

            #grid {
                display: flex;
                flex-direction: column;
                gap: 0;
            }

            .grid-cell {
                width: 600px;
                max-width: 100%;
                margin: 0 auto;
            }

            #form {
                order: 1;
                padding: 4rem 0;
                border-bottom: 2px solid var(--color-border-400);
            }

            #data {
                order: 2;
                padding: 4rem 0;
            }
        }
    </style>
@endpushOnce

<x-layouts.main :$title :$location>
    <div class="center-content fill-width bg-surface-400 relative grid px-8 py-20 sm:py-40">
        <img
            class="absolute h-full w-full object-cover opacity-90"
            src="{{ Vite::image('contacts/header-background.webp') }}"
        />

        <x-heading variant="main" class="relative text-center">Conta<span class="text-primary">tt</span>i</x-heading>
    </div>

    <!-- Body -->
    <div id="body">
        <div class="main-wrap">
            <div id="grid" class="grid2 gap3 al-top">
                <!-- Contact data -->
                <section id="data" class="grid-cell grid grid-cols-1 content-start gap-4">
                    <div class="">
                        <x-heading variant="section">Shipping Dog</x-heading>
                        <p class="data-txt mt-4">Andrea Trentini</p>
                    </div>

                    <p class="data-txt">
                        <a class="text-link" href="{{ config('custom.contacts.email.url') }}">
                            {{ config('custom.contacts.email.label') }}
                        </a>
                    </p>
                    <p class="data-txt">Piazza Scopoli, 9<br />38033 Cavalese (TN)</p>

                    <section id="contacts" class="flex gap-4">
                        <a
                            href="{{ config('custom.contacts.whatsapp.url') }}"
                            class="hover:text-primary-500 text-heading-400 transition-colors"
                        >
                            <x-icon-whatsapp class="h-5 w-5" />
                        </a>
                        <a
                            href="{{ config('custom.contacts.instagram.url') }}"
                            class="hover:text-primary-500 text-heading-400 transition-colors"
                        >
                            <x-icon-instagram class="h-5 w-5" />
                        </a>
                    </section>

                    <div class="border-border-400 my-4 border-b"></div>

                    <p class="">
                        <a href="{{ route('support.refund.search') }}" class="text-link">Cambio taglia o reso</a>
                    </p>
                </section>

                <!-- Contact form -->
                <section id="form" class="grid-cell">
                    <form method="POST" autocomplete="off" class="grid grid-cols-1 gap-4">
                        @csrf

                        <x-heading variant="section" class="text-center">Scrivici</x-heading>

                        @if (session('success'))
                            <x-alert type="success">{{ session('success') }}</x-alert>
                        @endif

                        @if ($errors->any())
                            <x-alert type="error">{!! $errors->first() !!}</x-alert>
                        @endif

                        <div class="">
                            <div class="contact-form-field">
                                <p class="form-label">Nome e cognome</p>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    class="form-inp"
                                    required
                                    placeholder=" "
                                />
                            </div>

                            <div class="contact-form-field">
                                <p class="form-label">Indirizzo email</p>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="form-inp"
                                    required
                                    placeholder=" "
                                />
                            </div>

                            <div class="contact-form-field">
                                <p class="form-label">Testo del messaggio</p>
                                <textarea
                                    name="text"
                                    class="form-textarea"
                                    maxlength="1000"
                                    required
                                    placeholder=" "
                                >{{ old('text') }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <x-button type="submit" class="mt-4"
                                >Invia
                                <x-icon-send class="max-w-[0.75em]" />
                            </x-button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</x-layouts.main>
